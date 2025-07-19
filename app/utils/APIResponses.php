<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait APIResponses
{
    protected function success(array|object $data, string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message ?? null,
            'data' => $data,
        ], $code);
    }

    protected function fail(string $message, int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => 'Failed',
            'message' => $message ?? null,
        ], $code);
    }

    protected function noContent(): Response
    {
        return response()->noContent();
    }
}
