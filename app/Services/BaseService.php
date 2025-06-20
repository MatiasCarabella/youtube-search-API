<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class BaseService
{
    /**
     * @param int $code
     * @param string $message
     * @return JsonResponse
     */
    public function errorResponse(int $code, string $message): JsonResponse
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
