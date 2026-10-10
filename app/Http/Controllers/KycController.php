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
        $rawNik    = $request->query('nik') ?: ($pegawai ? ($pegawai->no_ktp ?: $pegawai->nik ?? '') : '');
        $agentNik  = $rawNik;

        // Jika NIK yang dikirim adalah ID/Kode Pegawai (misal D0000005), cari No. KTP asli di database
        if (!empty($agentNik) && (!is_numeric($agentNik) || strlen((string) $agentNik) !== 16)) {
            try {
                $ktpFromDb = \Illuminate\Support\Facades\DB::table('pegawai')
                    ->where('nik', $agentNik)
                    ->value('no_ktp');
                if (!empty($ktpFromDb) && is_numeric($ktpFromDb) && strlen(trim($ktpFromDb)) === 16) {
                    $agentNik = trim($ktpFromDb);
                }
            } catch (\Throwable $e) {
                // Abaikan error koneksi database
            }
        }

        // 1. Prioritaskan konfigurasi dari .env (config/satusehat.php)
        $clientId     = config('satusehat.client_id');
        $clientSecret = config('satusehat.client_secret');
        $authUrl      = config('satusehat.auth_url') ?: 'https://api-satusehat.kemkes.go.id/oauth2/v1';
        $isDev        = str_contains((string) $authUrl, '-dev');
        $apiUrl       = $isDev ? 'https://api-satusehat-dev.dto.kemkes.go.id/kyc/v1/generate-url' : 'https://api-satusehat.kemkes.go.id/kyc/v1/generate-url';
        $environment  = $isDev ? 'development' : 'production';

        // 2. Fallback ke kyc/satusehat.ini jika di .env belum disetting
        if (empty($clientId) || empty($clientSecret)) {
            $iniPath = base_path('kyc/satusehat.ini');
            if (file_exists($iniPath)) {
                $init = parse_ini_file($iniPath);
                $clientId     = $clientId ?: ($init['client_id'] ?? null);
                $clientSecret = $clientSecret ?: ($init['client_secret'] ?? null);
                $authUrl      = $init['auth_url'] ?? $authUrl;
                $apiUrl       = $init['api_url'] ?? $apiUrl;
                $environment  = $init['environment'] ?? $environment;
            }
        }

        if (empty($clientId) || empty($clientSecret)) {
            return response()->view('error.kyc', [
                'title'   => 'Konfigurasi SatuSehat Belum Lengkap',
                'message' => 'Client ID dan Client Secret SatuSehat belum diatur pada .env atau kyc/satusehat.ini.',
            ], 500);
        }

        if (empty($agentNik) || !is_numeric($agentNik) || strlen((string) $agentNik) !== 16) {
            return response()->view('error.kyc', [
                'title'   => 'NIK Petugas Tidak Valid',
                'message' => 'NIK petugas/operator (' . e($rawNik) . ') bukan 16 digit angka KTP. SatuSehat KYC mewajibkan NIK KTP yang valid. Harap lengkapi kolom No. KTP pada data pegawai di Master Pegawai.',
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
