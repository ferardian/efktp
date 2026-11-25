<?php

namespace App\Services\SatuSehat;

use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SatuSehatRmeService
{
    protected string $authUrl;
    protected string $baseUrl;
    protected string $fhirUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $organizationId;
    protected string $organizationName;

    public function __construct()
    {
        $this->authUrl = rtrim((string) config('satusehat.auth_url', 'https://api-satusehat.kemkes.go.id/oauth2/v1'), '/');
        $this->fhirUrl = rtrim((string) config('satusehat.fhir_url', 'https://api-satusehat.kemkes.go.id/fhir-r4/v1'), '/');

        $configuredBaseUrl = config('satusehat.base_url');
        if (!empty($configuredBaseUrl)) {
            $this->baseUrl = rtrim($configuredBaseUrl, '/');
        } else {
            $isStaging = str_contains($this->fhirUrl, 'stg') || str_contains($this->authUrl, 'stg');
            $this->baseUrl = $isStaging
                ? 'https://api-satusehat-stg.dto.kemkes.go.id/ssrme/v2'
                : 'https://api-satusehat.kemkes.go.id/ssrme/v2';
        }

        $this->clientId = (string) config('satusehat.client_id', '');
        $this->clientSecret = (string) config('satusehat.client_secret', '');
        $this->organizationId = (string) config('satusehat.org_id', '');

        $orgName = config('satusehat.organization_name');
        if (empty($orgName)) {
            try {
                $setting = Setting::first();
                $orgName = $setting?->nama_instansi ?: config('app.name', 'Fasyankes');
            } catch (Exception $e) {
                $orgName = config('app.name', 'Fasyankes');
            }
        }
        $this->organizationName = trim(str_replace('|', ' ', (string) $orgName));
    }

    /**
     * Dapatkan OAuth 2.0 Access Token dari SATUSEHAT (dengan caching otomatis).
     */
    public function getAccessToken(): ?string
    {
        $cacheKey = 'satusehat_access_token_' . md5($this->clientId . $this->authUrl);

        return Cache::remember($cacheKey, 3000, function () {
            if (empty($this->clientId) || empty($this->clientSecret)) {
                throw new Exception('SATUSEHAT Client ID atau Client Secret belum dikonfigurasi di .env');
            }

            $endpoint = $this->authUrl . '/accesstoken?grant_type=client_credentials';

            $response = Http::asForm()->timeout(15)->post($endpoint, [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            if ($response->failed()) {
                Log::error('SATUSEHAT Auth Failed: ' . $response->body());
                throw new Exception('Gagal mendapatkan token SATUSEHAT: ' . ($response->json('fault.faultstring') ?? $response->body()));
            }

            return $response->json('access_token');
        });
    }

    /**
     * Resolusi NIK Pasien ke SATUSEHAT Patient ID (IHS ID).
     */
    public function getPatientIdByNik(string $nik): ?string
    {
        $nik = trim($nik);
        if (empty($nik)) {
            return null;
        }

        if (str_starts_with(strtoupper($nik), 'P')) {
            return $nik;
        }

        $cacheKey = 'satusehat_patient_id_' . $nik;
        return Cache::remember($cacheKey, 86400 * 30, function () use ($nik) {
            $token = $this->getAccessToken();
            $url = $this->fhirUrl . '/Patient?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik;

            $response = Http::withToken($token)->timeout(15)->get($url);

            if ($response->successful()) {
                $entry = $response->json('entry');
                if (!empty($entry) && isset($entry[0]['resource']['id'])) {
                    return $entry[0]['resource']['id'];
                }
            }

            return $nik;
        });
    }

    /**
     * Resolusi NIK Dokter ke SATUSEHAT Practitioner ID.
     */
    public function getPractitionerIdByNik(string $nik): ?string
    {
        $nik = trim($nik);
        if (empty($nik)) {
            return null;
        }

        if (strlen($nik) <= 12 && is_numeric($nik)) {
            return $nik;
        }

        $cacheKey = 'satusehat_practitioner_id_' . $nik;
        return Cache::remember($cacheKey, 86400 * 30, function () use ($nik) {
            $token = $this->getAccessToken();
            $url = $this->fhirUrl . '/Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik;

            $response = Http::withToken($token)->timeout(15)->get($url);

            if ($response->successful()) {
                $entry = $response->json('entry');
                if (!empty($entry) && isset($entry[0]['resource']['id'])) {
                    return $entry[0]['resource']['id'];
                }
            }

            return $nik;
        });
    }

    /**
     * API SHLink: Membuka RME Nasional (Viewer SSRME).
     * Endpoint: POST /ssrme/v2/ntl/shl
     */
    public function generateShlink(array $params): array
    {
        $token = $this->getAccessToken();
        $endpoint = $this->baseUrl . '/ntl/shl';

        $payload = [
            'patient_id' => $params['patient_id'],
            'patient_name' => $params['patient_name'],
            'practitioner_id' => $params['practitioner_id'],
            'practitioner_name' => $params['practitioner_name'],
            'organization_id' => $params['organization_id'] ?? $this->organizationId,
            'organization_name' => $params['organization_name'] ?? $this->organizationName,
        ];

        if (!empty($params['type_medical_summary'])) {
            $payload['type_medical_summary'] = $params['type_medical_summary'];
        } elseif (!empty($params['is_emergency'])) {
            $payload['type_medical_summary'] = 'EMERGENCY';
        }

        Log::info('SATUSEHAT SHL Payload:', $payload);

        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->timeout(20)
            ->post($endpoint, $payload);

        Log::info('SATUSEHAT SHL Response:', ['status' => $response->status(), 'body' => $response->json()]);

        if ($response->status() === 200 && $response->json('success') === true) {
            return [
                'status' => 'success',
                'code' => 200,
                'data' => $response->json('data'),
                'shlink_url' => $response->json('data.shlinkUrl'),
            ];
        }

        if ($response->status() === 403 || $response->json('data.code') === 'CONSENT_REQUIRED') {
            return [
                'status' => 'consent_required',
                'code' => 403,
                'message' => 'Persetujuan (Consent) pasien diperlukan sebelum membuka RME Nasional.',
                'data' => $response->json('data'),
            ];
        }

        Log::warning('SATUSEHAT SHL Failed: ' . $response->body(), ['payload' => $payload]);

        return [
            'status' => 'error',
            'code' => $response->status(),
            'message' => $response->json('message') ?? $response->json('fault.faultstring') ?? 'Gagal mengakses RME SATUSEHAT',
            'raw' => $response->json() ?? $response->body(),
        ];
    }

    /**
     * API CHLink: Membuat Consent Health Link (Link/QR verifikasi persetujuan pasien).
     * Endpoint: POST /ssrme/v2/ntl/chl
     */
    public function generateChlink(array $params): array
    {
        $token = $this->getAccessToken();
        $endpoint = $this->baseUrl . '/ntl/chl';

        $payload = [
            'patient_id' => $params['patient_id'],
            'patient_name' => $params['patient_name'],
            'practitioner_id' => $params['practitioner_id'],
            'practitioner_name' => $params['practitioner_name'],
            'organization_id' => $params['organization_id'] ?? $this->organizationId,
            'organization_name' => $params['organization_name'] ?? $this->organizationName,
        ];

        if (!empty($params['type_medical_summary'])) {
            $payload['type_medical_summary'] = $params['type_medical_summary'];
        } elseif (!empty($params['is_emergency'])) {
            $payload['type_medical_summary'] = 'EMERGENCY';
        }

        Log::info('SATUSEHAT CHL Payload:', $payload);

        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->timeout(20)
            ->post($endpoint, $payload);

        Log::info('SATUSEHAT CHL Response:', ['status' => $response->status(), 'body' => $response->json()]);

        if ($response->status() === 200 && $response->json('success') === true) {
            return [
                'status' => 'success',
                'code' => 200,
                'data' => $response->json('data'),
                'verification_url' => $response->json('data.verificationUrl'),
                'shlink_id' => $response->json('data.shlinkId'),
                'expired_at' => $response->json('data.expiredAt'),
            ];
        }

        Log::warning('SATUSEHAT CHL Failed: ' . $response->body(), ['payload' => $payload]);

        return [
            'status' => 'error',
            'code' => $response->status(),
            'message' => $response->json('message') ?? $response->json('fault.faultstring') ?? 'Gagal membuat Consent Health Link',
            'raw' => $response->json() ?? $response->body(),
        ];
    }
}

