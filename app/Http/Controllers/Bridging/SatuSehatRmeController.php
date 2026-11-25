<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Pegawai;
use App\Models\RegPeriksa;
use App\Services\SatuSehat\SatuSehatRmeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SatuSehatRmeController extends Controller
{
    protected SatuSehatRmeService $rmeService;

    public function __construct(SatuSehatRmeService $rmeService)
    {
        $this->rmeService = $rmeService;
    }

    /**
     * Buka RME SATUSEHAT untuk pasien berdasarkan no_rawat.
     */
    public function openRme(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat' => 'required|string',
            'kd_dokter' => 'nullable|string',
            'is_emergency' => 'nullable',
        ]);

        $isEmergency = $request->boolean('is_emergency') || ($request->input('is_emergency') == '1');

        try {
            $regPeriksa = RegPeriksa::with(['pasien', 'dokter.pegawai'])
                ->where('no_rawat', $request->no_rawat)
                ->first();

            if (!$regPeriksa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data registrasi pasien tidak ditemukan.',
                ], 404);
            }

            $pasien = $regPeriksa->pasien;
            if (!$pasien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data rekam medis pasien tidak ditemukan.',
                ], 404);
            }

            // Dapatkan Data Dokter
            $dokter = null;
            if ($request->filled('kd_dokter')) {
                $dokter = Dokter::with('pegawai')->where('kd_dokter', $request->kd_dokter)->first();
            }
            if (!$dokter) {
                $dokter = $regPeriksa->dokter;
            }

            // Fallback: Cek session pegawai jika dokter belum terpilih (misal di IGD)
            $pegawaiSession = session()->get('pegawai');
            if (!$dokter && $pegawaiSession) {
                $dokter = Dokter::with('pegawai')->where('kd_dokter', $pegawaiSession->nik)->first();
            }

            if (!$dokter && !$pegawaiSession) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dokter pemeriksa tidak ditemukan.',
                ], 404);
            }

            // Validasi NIK Pasien
            $nikPasien = trim((string) ($pasien->no_ktp ?? ''));
            if (empty($nikPasien) || strlen($nikPasien) < 16) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIK Pasien belum diisi atau tidak valid (harus 16 digit). Silakan periksa data kependudukan pasien di Master Pasien.',
                ], 422);
            }

            // Validasi NIK Dokter / Praktisi
            $nikDokter = '';
            $namaDokter = '';
            if ($dokter) {
                $nikDokter = trim((string) ($dokter->pegawai->no_ktp ?? ''));
                if (empty($nikDokter)) {
                    $pegawai = Pegawai::where('nik', $dokter->kd_dokter)->first();
                    $nikDokter = trim((string) ($pegawai->no_ktp ?? $dokter->kd_dokter ?? ''));
                }
                $namaDokter = $dokter->nm_dokter;
            } elseif ($pegawaiSession) {
                $nikDokter = trim((string) ($pegawaiSession->no_ktp ?? $pegawaiSession->nik ?? ''));
                $namaDokter = $pegawaiSession->nama ?? 'Dokter';
            }

            if (empty($nikDokter)) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIK Dokter belum terdaftar pada data kepegawaian.',
                ], 422);
            }

            // Resolusi ID SATUSEHAT
            $patientId = $this->rmeService->getPatientIdByNik($nikPasien);
            $practitionerId = $this->rmeService->getPractitionerIdByNik($nikDokter);

            $payload = [
                'patient_id' => $patientId,
                'patient_name' => $pasien->nm_pasien,
                'practitioner_id' => $practitionerId,
                'practitioner_name' => $namaDokter,
                'is_emergency' => $isEmergency,
            ];

            if ($isEmergency) {
                $payload['type_medical_summary'] = 'EMERGENCY';
            }

            // 1. Coba Buka SHLink (National RME Viewer)
            $shlResult = $this->rmeService->generateShlink($payload);

            if ($shlResult['status'] === 'success') {
                return response()->json([
                    'success' => true,
                    'status' => 'ready',
                    'is_emergency' => $isEmergency,
                    'shlink_url' => $shlResult['shlink_url'],
                    'patient_name' => $pasien->nm_pasien,
                    'practitioner_name' => $namaDokter,
                    'message' => 'Tautan RME Nasional berhasil dibuka.',
                ]);
            }

            // 2. Jika Consent Diperlukan (HTTP 403), Otomatis Coba Buat CHLink untuk Verifikasi Pasien
            if ($shlResult['status'] === 'consent_required') {
                $chlResult = $this->rmeService->generateChlink($payload);
                $hasQr = ($chlResult['status'] === 'success' && !empty($chlResult['verification_url']));

                return response()->json([
                    'success' => true,
                    'status' => 'consent_required',
                    'is_emergency' => $isEmergency,
                    'has_qr' => $hasQr,
                    'verification_url' => $hasQr ? $chlResult['verification_url'] : null,
                    'expired_at' => $hasQr ? ($chlResult['expired_at'] ?? null) : null,
                    'patient_name' => $pasien->nm_pasien,
                    'message' => $isEmergency
                        ? 'Formulir persetujuan emergency diperlukan untuk otorisasi kedaruratan.'
                        : 'Persetujuan (Consent) pasien diperlukan melalui aplikasi SATUSEHAT Mobile.',
                ]);
            }

            return response()->json([
                'success' => false,
                'is_emergency' => $isEmergency,
                'message' => $shlResult['message'] ?? 'Gagal mengakses RME SATUSEHAT.',
                'raw' => $shlResult['raw'] ?? null,
            ], 400);
        } catch (Exception $e) {
            Log::error('SatuSehatRmeController Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Minta ulang tautan persetujuan (Consent Health Link).
     */
    public function requestConsent(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat' => 'required|string',
            'kd_dokter' => 'nullable|string',
        ]);

        try {
            $regPeriksa = RegPeriksa::with(['pasien', 'dokter.pegawai'])
                ->where('no_rawat', $request->no_rawat)
                ->firstOrFail();

            $pasien = $regPeriksa->pasien;
            $dokter = $request->filled('kd_dokter')
                ? Dokter::with('pegawai')->where('kd_dokter', $request->kd_dokter)->first()
                : $regPeriksa->dokter;

            $nikDokter = trim((string) ($dokter->pegawai->no_ktp ?? $dokter->kd_dokter ?? ''));

            $patientId = $this->rmeService->getPatientIdByNik((string) $pasien->no_ktp);
            $practitionerId = $this->rmeService->getPractitionerIdByNik($nikDokter);

            $chlResult = $this->rmeService->generateChlink([
                'patient_id' => $patientId,
                'patient_name' => $pasien->nm_pasien,
                'practitioner_id' => $practitionerId,
                'practitioner_name' => $dokter->nm_dokter ?? 'Dokter',
            ]);

            if ($chlResult['status'] === 'success') {
                return response()->json([
                    'success' => true,
                    'verification_url' => $chlResult['verification_url'],
                    'expired_at' => $chlResult['expired_at'],
                    'message' => 'Tautan verifikasi persetujuan berhasil dibuat.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $chlResult['message'] ?? 'Gagal membuat tautan persetujuan.',
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

