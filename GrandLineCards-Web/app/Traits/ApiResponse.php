<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Success Response
     * 
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @param array $meta
     * @return JsonResponse
     */
    protected function successResponse($data, $message = null, $code = 200, $meta = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'meta' => $meta,
        ], $code);
    }

    /**
     * Error Response
     * 
     * @param string $message
     * @param int $code
     * @param mixed $data
     * @return JsonResponse
     */
    protected function errorResponse($message, $code = 400, $data = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'meta' => [],
        ], $code);
    }
}
