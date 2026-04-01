<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait APIResponses
{
    /**
     * @param  array<string, mixed>|object  $data
     */
    protected function success(array|object $data, string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function fail(string $message, int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => 'Failed',
            'message' => $message,
        ], $code);
    }

    protected function noContent(): Response
    {
        return response()->noContent();
    }
}
