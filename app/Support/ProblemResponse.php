<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

/**
 * RFC 7807 problem-details helpers.
 *
 * `Content-Type: application/problem+json` on every error response so SDKs
 * and clients can dispatch on it without sniffing the body shape.
 */
class ProblemResponse
{
    public static function make(int $status, string $title, ?string $detail = null, array $extras = []): JsonResponse
    {
        $body = array_merge([
            'type' => 'about:blank',
            'title' => $title,
            'status' => $status,
        ], $detail !== null ? ['detail' => $detail] : [], $extras);

        return new JsonResponse(
            $body,
            $status,
            ['Content-Type' => 'application/problem+json'],
        );
    }

    public static function unauthorized(string $detail = 'Authentication required.'): JsonResponse
    {
        return self::make(401, 'Unauthorized', $detail);
    }

    public static function notFound(string $detail = 'Resource not found.'): JsonResponse
    {
        return self::make(404, 'Not Found', $detail);
    }

    public static function validation(string $detail, array $errors = []): JsonResponse
    {
        return self::make(422, 'Validation failed', $detail, Arr::whereNotNull(['errors' => $errors ?: null]));
    }

    public static function conflict(string $detail): JsonResponse
    {
        return self::make(409, 'Conflict', $detail);
    }

    public static function unprocessable(string $detail): JsonResponse
    {
        return self::make(422, 'Unprocessable', $detail);
    }
}
