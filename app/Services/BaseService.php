<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class BaseService
{
    public function errorResponse (int $code, string $message)
    {
        $response = [
            "error" => [
                "code" => $code,
                "message" => $message,
            ],
        ];
        return new JsonResponse($response, $code);
    }
}