<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    /**
     * Standard success response
     *
     * @param mixed $data
     * @param int $code
     * @param string $msg
     * @param string $statusCode
     * @return JsonResponse
     */
    public function sendResponse($data = [], int $code = 200, string $msg = 'OK', string $statusCode = 'success'): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'status_code' => $statusCode,
            'message' => $msg,
            'data' => is_array($data) && count($data) === 1 ? $data[0] : $data,
        ], $code);
    }

    /**
     * Standard error response
     *
     * @param string $msg
     * @param int $code
     * @param array|null $data
     * @return JsonResponse
     */

    public function sendError(string $msg = 'Error', int $code = 400, ?array $data = null): JsonResponse
    {
        $response = [
            'status' => $code,
            'status_code' => 'error',
            'message' => $msg,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
