<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\MessageBag;

trait HttpResponses
{
    public function successResponse(string $message, int $status, array|JsonResource $data = [])
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }
    public function successAuthResponse(string $message, int $status, array|JsonResource $data = [], string $token)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'toekn' => $token,
        ], $status);
    }

    public function errorResponse(string $message, int $status, array|MessageBag $errors, array $data = [])
    {
        return response()->json([
            "message" => $message,
            "data" => $data,
            'errors' => $errors
        ], $status);
    }
    public function exceptionResponse(string $message, int $status, Exception $errors)
    {
        return response()->json([
            "message" => $message,
            'errors' => $errors->getMessage()
        ], $status);
    }

    public function badCredentialsResponse(string $message = "Credénciais inválidas!", int $status)
    {
        return response()->json([
            "message" => $message,
        ], $status);
    }
}
