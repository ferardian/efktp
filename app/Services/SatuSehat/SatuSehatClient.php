<?php

namespace App\Services\SatuSehat;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class SatuSehatClient
{
    protected string $baseUrl;
    protected string $authUrl;
    protected string $clientId;
    protected string $clientSecret;

    public function __construct()
    {
        $this->baseUrl      = config('satusehat.fhir_url');
        $this->authUrl      = config('satusehat.auth_url');
        $this->clientId     = config('satusehat.client_id');
        $this->clientSecret = config('satusehat.client_secret');
    }

    /**
     * Get Access Token from Satu Sehat OAuth2
     */
    protected function getToken(): string
    {
        $cacheKey = 'satusehat_access_token';
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::asForm()->post($this->authUrl . '/accesstoken?grant_type=client_credentials', [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if ($response->failed()) {
            throw new Exception('Satu Sehat Auth Failed: ' . $response->body());
        }

        $data = $response->json();
        $token = $data['access_token'];
        
        // Cache token slightly less than expires_in (usually 3600s)
        Cache::put($cacheKey, $token, $data['expires_in'] - 60);

        return $token;
    }

    /**
     * Send GET request
     */
    public function get(string $endpoint, array $query = []): array
    {
        $token = $this->getToken();
        $response = Http::withToken($token)
            ->get($this->baseUrl . '/' . $endpoint, $query);

        return $this->handleResponse($response);
    }

    /**
     * Send POST request
     */
    public function post(string $endpoint, array $data = []): array
    {
        $token = $this->getToken();
        $response = Http::withToken($token)
            ->post($this->baseUrl . '/' . $endpoint, $data);

        return $this->handleResponse($response);
    }

    /**
     * Send PUT request
     */
    public function put(string $endpoint, array $data = []): array
    {
        $token = $this->getToken();
        $response = Http::withToken($token)
            ->put($this->baseUrl . '/' . $endpoint, $data);

        return $this->handleResponse($response);
    }

    /**
     * Handle API Response
     */
    protected function handleResponse($response): array
    {
        if ($response->failed()) {
            return [
                'status'  => false,
                'message' => $response->reason(),
                'data'    => $response->json() ?? $response->body(),
            ];
        }

        return [
            'status' => true,
            'data'   => $response->json(),
        ];
    }
}
