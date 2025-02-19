<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/accountinfo', function (Request $request) {
    return Response::json([
        "status" => "success",
        "message" => "Account information retrieved successfully.",
        "data" => [
            "user_id" => 12345,
            "username" => "matheusbritto",
            "email" => "matheus@exemplo.com",
            "balance" => 100.50,
            "currency" => "USD",
            "account_type" => "reseller",
            "created_at" => "2024-02-19T12:34:56Z",
            "updated_at" => "2024-02-19T15:00:00Z"
        ]
    ]);
})->middleware('auth:sanctum');