<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KycController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = session()->get('pegawai');

        // Nama dan NIK Petugas / Operator
        $agentName = $request->query('nama') ?: ($pegawai ? $pegawai->nama : 'Petugas Faskes');
        $agentNik  = $request->query('nik') ?: ($pegawai ? ($pegawai->nik ?: $pegawai->no_ktp ?? '') : '');

        // Baca konfigurasi SatuSehat
        $iniPath = base_path('kyc/satusehat.ini');
        if (file_exists($iniPath)) {
            $init = parse_ini_file($iniPath);
            $clientId     = $init['client_id'] ?? config('satusehat.client_id');
            $clientSecret = $init['client_secret'] ?? config('satusehat.client_secret');
            $authUrl      = $init['auth_url'] ?? config('satusehat.auth_url', 'https://api-satusehat.kemkes.go.id/oauth2/v1');
            $apiUrl       = $init['api_url'] ?? 'https://api-satusehat.kemkes.go.id/kyc/v1/generate-url';
            $environment  = $init['environment'] ?? 'production';
        } else {
            $clientId     = config('satusehat.client_id');
            $clientSecret = config('satusehat.client_secret');
            $authUrl      = config('satusehat.auth_url', 'https://api-satusehat.kemkes.go.id/oauth2/v1');
            $isDev        = str_contains((string) $authUrl, '-dev');
            $apiUrl       = $isDev ? 'https://api-satusehat-dev.dto.kemkes.go.id/kyc/v1/generate-url' : 'https://api-satusehat.kemkes.go.id/kyc/v1/generate-url';
            $environment  = $isDev ? 'development' : 'production';
        }

        if (empty($clientId) || empty($clientSecret)) {
            return response()->view('error.kyc', [
                'title'   => 'Konfigurasi SatuSehat Belum Lengkap',
                'message' => 'Client ID dan Client Secret SatuSehat belum diatur pada kyc/satusehat.ini atau .env.',
            ], 500);
        }

        if (empty($agentNik)) {
            return response()->view('error.kyc', [
                'title'   => 'NIK Petugas Tidak Ditemukan',
                'message' => 'NIK petugas/operator belum terisi pada profil pegawai atau parameter URL (?nik=...). Harap lengkapi NIK pegawai terlebih dahulu.',
            ], 400);
        }

        try {
            require_once base_path('kyc/auth.php');
            require_once base_path('kyc/function.php');

            $accessToken = authenticateWithOAuth2($clientId, $clientSecret, $authUrl);
            if (!$accessToken) {
                throw new \Exception('Gagal mendapatkan access token OAuth2 dari SatuSehat.');
            }

            $jsonResult = generateUrl($agentName, $agentNik, $accessToken, $apiUrl, $environment);
            $response = json_decode($jsonResult, true);

            if (!empty($response['data']['url'])) {
                return redirect()->away($response['data']['url']);
            }

            $errMsg = $response['message'] ?? $response['metadata']['message'] ?? 'Gagal generate URL validasi KYC SatuSehat.';
            return response()->view('error.kyc', [
                'title'   => 'Gagal Membuka KYC SatuSehat',
                'message' => $errMsg . ' (' . ($jsonResult ?: 'Respon kosong') . ')',
            ], 500);
        } catch (\Throwable $e) {
            Log::error('KYC SatuSehat Error: ' . $e->getMessage());
            return response()->view('error.kyc', [
                'title'   => 'Terjadi Kesalahan KYC SatuSehat',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
