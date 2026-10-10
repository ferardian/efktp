<?php

// Tampilkan error jika dalam mode debug
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);

try {
    // 1. Cek vendor autoload
    if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
        throw new Exception("Library phpseclib belum terinstal di folder kyc. Silakan jalankan 'composer install' di direktori kyc.");
    }

    include_once(__DIR__ . '/auth.php');
    include_once(__DIR__ . '/function.php');

    $client_id = null;
    $client_secret = null;
    $auth_url = 'https://api-satusehat.kemkes.go.id/oauth2/v1';
    $api_url = 'https://api-satusehat.kemkes.go.id/kyc/v1/generate-url';
    $environment = 'production';

    // 2. Baca dari .env root terlebih dahulu jika ada
    $envPath = __DIR__ . '/../.env';
    $envVars = [];
    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) continue;
            if (strpos($line, '=') !== false) {
                list($k, $v) = explode('=', $line, 2);
                $envVars[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
            }
        }
        $client_id     = $envVars['SATUSEHAT_CLIENT_ID'] ?? null;
        $client_secret = $envVars['SATUSEHAT_CLIENT_SECRET'] ?? null;
        $auth_url      = $envVars['SATUSEHAT_AUTH_URL'] ?? $auth_url;
        $isDev         = strpos($auth_url, '-dev') !== false;
        $api_url       = $isDev ? 'https://api-satusehat-dev.dto.kemkes.go.id/kyc/v1/generate-url' : 'https://api-satusehat.kemkes.go.id/kyc/v1/generate-url';
        $environment   = $isDev ? 'development' : 'production';
    }

    // 3. Fallback ke satusehat.ini jika di .env belum ada
    if (empty($client_id) || empty($client_secret)) {
        $iniPath = __DIR__ . '/satusehat.ini';
        if (file_exists($iniPath)) {
            $init = parse_ini_file($iniPath);
            if ($init !== false) {
                $client_id     = $init['client_id'] ?? $client_id;
                $client_secret = $init['client_secret'] ?? $client_secret;
                $auth_url      = $init['auth_url'] ?? $auth_url;
                $api_url       = $init['api_url'] ?? $api_url;
                $environment   = $init['environment'] ?? $environment;
            }
        }
    }

    if (empty($client_id) || empty($client_secret)) {
        throw new Exception("Credential SatuSehat (SATUSEHAT_CLIENT_ID & SATUSEHAT_CLIENT_SECRET) belum diatur di file .env atau satusehat.ini.");
    }

    $agent_name = $_GET['nama'] ?? 'Petugas Faskes';
    $raw_nik    = $_GET['nik'] ?? '';
    $agent_nik  = $raw_nik;

    // 4. Jika NIK bukan 16 digit angka (misal kode dokter D0000005), coba lookup no_ktp ke database
    if (!empty($agent_nik) && (!is_numeric($agent_nik) || strlen($agent_nik) !== 16)) {
        if (!empty($envVars['DB_HOST']) && !empty($envVars['DB_DATABASE'])) {
            try {
                $host = $envVars['DB_HOST'];
                $port = $envVars['DB_PORT'] ?? '3306';
                $db   = $envVars['DB_DATABASE'];
                $user = $envVars['DB_USERNAME'] ?? 'root';
                $pass = $envVars['DB_PASSWORD'] ?? '';
                $pdo  = new PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 3
                ]);
                $stmt = $pdo->prepare("SELECT no_ktp FROM pegawai WHERE nik = :nik LIMIT 1");
                $stmt->execute(['nik' => $agent_nik]);
                $no_ktp = $stmt->fetchColumn();
                if ($no_ktp && is_numeric(trim($no_ktp)) && strlen(trim($no_ktp)) === 16) {
                    $agent_nik = trim($no_ktp);
                }
            } catch (Throwable $eDb) {
                // Ignore DB error, proceed to validation check below
            }
        }
    }

    if (empty($agent_nik) || !is_numeric($agent_nik) || strlen($agent_nik) !== 16) {
        throw new Exception("NIK Petugas/Operator ('" . htmlspecialchars($raw_nik) . "') bukan 16 digit angka KTP. SatuSehat KYC mewajibkan NIK KTP yang valid. Harap lengkapi kolom No. KTP pada data pegawai di Master Pegawai.");
    }

    // 5. Auth ke SatuSehat OAuth2
    $auth_result = authenticateWithOAuth2($client_id, $client_secret, $auth_url);
    if (!$auth_result) {
        throw new Exception("Gagal mendapatkan access token OAuth2 dari SatuSehat. Periksa kembali Client ID dan Client Secret Anda.");
    }

    // 6. Generate URL KYC
    $json = generateUrl($agent_name, $agent_nik, $auth_result, $api_url, $environment);
    $validation_web = json_decode($json, true);

    if (empty($validation_web['data']['url'])) {
        $msg = $validation_web['message'] ?? $validation_web['metadata']['message'] ?? 'Respon SatuSehat tidak memuat URL validasi.';
        throw new Exception($msg . " (" . $json . ")");
    }

    $url = $validation_web['data']['url'];

    // 7. Langsung redirect jika request browser biasa
    header("Location: " . $url);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Gagal Membuka KYC SatuSehat</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #1e293b; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box; }
            .card { background: #fff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); max-width: 520px; width: 100%; padding: 32px; text-align: center; border: 1px solid #e2e8f0; }
            .icon { width: 56px; height: 56px; margin: 0 auto 16px; background: #fee2e2; color: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; }
            h2 { margin: 0 0 12px; font-size: 20px; color: #0f172a; }
            p { font-size: 14px; line-height: 1.6; color: #64748b; margin: 0 0 24px; text-align: left; background: #f1f5f9; padding: 12px 16px; border-radius: 8px; word-break: break-word; }
            .btn { display: inline-block; padding: 10px 20px; font-size: 14px; font-weight: 500; text-decoration: none; border-radius: 8px; background: #2563eb; color: #fff; cursor: pointer; border: none; }
            .btn:hover { background: #1d4ed8; }
        </style>
    </head>
    <body>
        <div class="card">
            <div class="icon">⚠️</div>
            <h2>Gagal Membuka KYC SatuSehat</h2>
            <p><?php echo htmlspecialchars($e->getMessage()); ?></p>
            <a href="javascript:window.close()" class="btn">Tutup Halaman</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}