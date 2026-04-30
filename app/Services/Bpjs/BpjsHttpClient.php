<?php

namespace App\Services\Bpjs;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BpjsHttpClient
{
    protected string $baseUrl;
    protected string $consId;
    protected string $secretKey;
    protected string $userKey;
    protected string $appCode;
    protected string $username;
    protected string $password;

    protected string $timestamp;
    protected string $signature;
    protected string $authorization;

    public function __construct(array $config)
    {
        $this->baseUrl   = rtrim($config['base_url'], '/');
        $this->consId    = $config['cons_id'];
        $this->secretKey = $config['secret_key'];
        $this->userKey   = $config['user_key'];
        $this->appCode   = $config['app_code'] ?? '095';
        $this->username  = $config['username'] ?? '';
        $this->password  = $config['password'] ?? '';
    }

    protected function prepare(): static
    {
        // UTC timestamp
        date_default_timezone_set('UTC');
        $this->timestamp = strval(time());

        // HMAC-SHA256 signature
        $raw = hash_hmac('sha256', "{$this->consId}&{$this->timestamp}", $this->secretKey, true);
        $this->signature = base64_encode($raw);

        // Basic authorization: username:password:appCode
        $this->authorization = 'Basic ' . base64_encode("{$this->username}:{$this->password}:{$this->appCode}");

        return $this;
    }

    protected function headers(): array
    {
        return [
            'X-cons-id'      => $this->consId,
            'X-timestamp'    => $this->timestamp,
            'X-signature'    => $this->signature,
            'X-authorization'=> $this->authorization,
            'user_key'       => $this->userKey,
            'Content-Type'   => 'application/json',
            'Accept'         => 'application/json',
        ];
    }

    public function get(string $endpoint, array $params = []): array
    {
        $this->prepare();
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        Log::info("[BPJS GET] {$url}", $params);

        try {
            $response = Http::withHeaders($this->headers())
                ->withoutVerifying()
                ->get($url, $params);

            $body = $response->json() ?? [];
            return $this->handleResponse($body);
        } catch (\Exception $e) {
            Log::error("[BPJS GET Error] {$e->getMessage()}");
            return [];
        }
    }

    public function post(string $endpoint, array $data = []): array
    {
        $this->prepare();
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        try {
            $response = Http::withHeaders($this->headers())
                ->withoutVerifying()
                ->withBody(json_encode($data), 'text/plain')
                ->post($url);

            if (!$response->successful()) {
                Log::error("[BPJS POST Error] Status: {$response->status()}, URL: {$url}, Body: {$response->body()}");
            }

            $body = $response->json() ?? [];
            
            if (empty($body) && !empty($response->body())) {
                Log::warning("[BPJS POST Warning] Response is not JSON: " . substr($response->body(), 0, 200));
            }

            return $this->handleResponse($body);
        } catch (\Exception $e) {
            Log::error("[BPJS POST Exception] {$e->getMessage()}");
            return ['metaData' => ['code' => 500, 'message' => $e->getMessage()]];
        }
    }

    public function put(string $endpoint, array $data = []): array
    {
        $this->prepare();
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        Log::info("[BPJS PUT] {$url}", $data);

        try {
            $response = Http::withHeaders($this->headers())
                ->withoutVerifying()
                ->put($url, $data);

            $body = $response->json() ?? [];
            return $this->handleResponse($body);
        } catch (\Exception $e) {
            Log::error("[BPJS PUT Error] {$e->getMessage()}");
            return ['metaData' => ['code' => 500, 'message' => $e->getMessage()]];
        }
    }

    public function delete(string $endpoint): array
    {
        $this->prepare();
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        Log::info("[BPJS DELETE] {$url}");

        try {
            $response = Http::withHeaders($this->headers())
                ->withoutVerifying()
                ->delete($url);

            $body = $response->json() ?? [];
            return $this->handleResponse($body);
        } catch (\Exception $e) {
            Log::error("[BPJS DELETE Error] {$e->getMessage()}");
            return ['metaData' => ['code' => 500, 'message' => $e->getMessage()]];
        }
    }

    /**
     * Otomatis dekripsi respons jika 'response' berupa string acak
     */
    protected function handleResponse($body): array
    {
        if (empty($body)) {
            return [
                'metaData' => [
                    'code'    => 500,
                    'message' => 'Respons kosong dari BPJS. Pastikan URL dan Kredensial benar.'
                ]
            ];
        }

        if (isset($body['response']) && is_string($body['response'])) {
            $decrypted = $this->decrypt($body['response']);
            if ($decrypted) {
                $decoded = json_decode($decrypted, true);
                $body['response'] = $decoded ?: $decrypted;
            }
        }

        return $body;
    }

    /**
     * Logika Dekripsi AES-256-CBC untuk BPJS WS 4.0
     */
    protected function decrypt(string $string): string
    {
        $key        = $this->consId . $this->secretKey . $this->timestamp;
        $key_hash   = hex2bin(hash('sha256', $key));
        $iv         = substr($key_hash, 0, 16);
        
        $output = openssl_decrypt(base64_decode($string), 'AES-256-CBC', $key_hash, OPENSSL_RAW_DATA, $iv);

        return $output ? $this->decompress($output) : '';
    }

    /**
     * Logika Decompress string (BPJS menggunakan LZMA)
     */
    protected function decompress(string $string): string
    {
        return \App\Helpers\BpjsHelper::decompress($string);
    }
}
