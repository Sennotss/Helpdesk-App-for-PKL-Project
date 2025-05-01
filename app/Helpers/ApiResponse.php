<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function created($data, $message = 'Resource created')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], 201);
    }

    public static function error($message = 'Error', $code = 500, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, 401);
    }

    public static function forbidden($message = 'Forbidden')
    {
        return self::error($message, 403);
    }

    public static function notFound($message = 'Not Found')
    {
        return self::error($message, 404);
    }

    public static function validationError($errors, $message = 'Validation Error')
    {
        return self::error($message, 422, $errors);
    }
}
