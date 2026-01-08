<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class CustomResponseJson implements Responsable
{
    protected int $httpCode;
    protected array $data;
    protected string $errorMessage;
    public function __construct(int $httpCode, array $data = [], string $errorMessage = '')
    {
        $this->httpCode = $httpCode;
        $this->data = $data;
        $this->errorMessage = $errorMessage;
    }
    public function toResponse($request)
    {
        $payload = match (true) {
            $this->httpCode >= 500 => [
                'error_message' => 'Server Error'
            ],
            $this->httpCode >= 400 => [
                'error_message' => $this->errorMessage
            ],
            $this->httpCode >= 200 => [
                'data' => $this->data
            ],
        };

        return response()->json(
            data: $payload,
            status: $this->httpCode,
            options: JSON_UNESCAPED_UNICODE
        );
    }

    public static function ok(array $data = [])
    {
        return new self(httpCode: 200, data: $data);
    }
    public static function created(array $data = [])
    {
        return new self(httpCode: 201, data: $data);
    }
    public static function notFound(string $errorMessage = 'Item not found')
    {
        return new self(httpCode: 404, errorMessage: $errorMessage);
    }

    public static function error(int $httpCode, string $errorMessage)
    {
        return new self(httpCode: $httpCode, errorMessage: $errorMessage);
    }

}