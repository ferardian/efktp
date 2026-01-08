<?php

namespace App\Http\Controllers\Bridging;

use GuzzleHttp\Client;
use LZCompressor\LZString;
use App\Traits\PcareConfig;
use Illuminate\Http\Request;
use AamDsam\Bpjs\BpjsService;
use AamDsam\Bpjs\PCare\PcareService;
use App\Http\Controllers\Controller;

class Icare extends Controller
{
    use PcareConfig;
    private $headers;
    private $timestamp;
    private $cons_id;
    private $signature;
    private $user_key;
    private $secret_key;
    private $base_url;
    private $clients;
    private $auth;
    private $key_decrypt;
    private $services;

    public function __construct()
    {

        $this->clients = new Client(['verify' => false]);
        $this->base_url = $this->config()['icare_url'];
        $this->services = new PcareService();
        $this->setTimestamp()->setSignature()->setAuthorization()->setHeaders();
    }

    protected function setHeaders()
    {
        $this->headers = [
            'X-cons-id' => $this->config()['cons_id'],
            'X-timestamp' => $this->timestamp,
            'X-signature' => $this->signature,
            'X-authorization' => $this->auth,
            'user_key' => $this->config()['user_key'],
        ];
        return $this;
    }

    protected function setTimestamp()
    {
        date_default_timezone_set('UTC');
        $this->timestamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        return $this;
    }

    protected function setSignature()
    {
        $data = $this->config()['cons_id'] . '&' . $this->timestamp;
        $signature = hash_hmac('sha256', $data, $this->config()['secret_key'], true);
        $encodedSignature = base64_encode($signature);
        $this->key_decrypt = "{$this->config()['cons_id']}{$this->config()['secret_key']}{$this->timestamp}";
        $this->signature = $encodedSignature;
        return $this;
    }

    protected function setAuthorization()
    {
        $data = "{$this->config()['user_icare']}:{$this->config()['password_icare']}:{$this->config()['app_code']}";
        $encodedAuth = base64_encode($data);
        $this->auth = "Basic {$encodedAuth}";
        return $this;
    }

    protected function get(Request $request)
    {
        $data = [
            'param' => (string)$request->no_peserta,
        ];
        $this->headers['Content-Type'] = 'application/json;utf-8';
        try {
            $response = $this->clients->request(
                'POST',
                $this->base_url,
                [
                    'headers' => $this->headers,
                    'body'    => json_encode($data),
                ]
            )->getBody()->getContents();
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return $this->responseDecoded($response);
    }
    public function responseDecoded($response)
    {
        // ubah ke array
        $responseArray = json_decode($response, true);
        if (!is_array($responseArray)) {
            return [
                "metaData" => [
                    "message" => $response,
                    "code" => 500
                ]
            ];
        }

        if (!isset($responseArray["response"]) || $responseArray['metaData']['code'] == 401) {
            return $responseArray;
        }


        $responseDecrypt = $this->stringDecrypt($responseArray["response"]);
        $responseArrayDecrypt = json_decode($responseDecrypt, true);
        // apabila bukan array
        if (!is_array($responseArrayDecrypt) || $responseDecrypt == '') {
            return $responseArray;
        }
        $responseArray["response"] = $responseArrayDecrypt;
        return $responseArray;
    }
    function stringDecrypt($string)
    {
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $this->key_decrypt));
        $iv = substr(hex2bin(hash('sha256', $this->key_decrypt)), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        return \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
    }
}
