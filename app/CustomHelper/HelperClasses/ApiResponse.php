<?php

namespace App\CustomHelper\HelperClasses;


class ApiResponse
{
    public static function successMessage($data = null, $message = 'OK', $status = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function errorMessage($message = 'Error', $status = 400, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}


