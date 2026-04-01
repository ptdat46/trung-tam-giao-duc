<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class Common
{
    // Role constants
    const ADMIN   = 'admin';
    const TEACHER = 'teacher';
    const STUDENT = 'student';

    /**
     * Format a success API response.
     *
     * @param  string  $message
     * @param  mixed   $data
     * @param  int     $statusCode
     * @return JsonResponse
     */
    public static function successResponse($message, $data = [], $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data'    => $data,
            'status'  => $statusCode,
        ], $statusCode);
    }

    /**
     * Format an error API response.
     *
     * @param  string        $message
     * @param  mixed        $errors
     * @param  int          $statusCode
     * @return JsonResponse
     */
    public static function errorResponse($message, $errors = null, $statusCode = 500): JsonResponse
    {
        $response = [
            'message' => $message,
            'status'  => $statusCode,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
