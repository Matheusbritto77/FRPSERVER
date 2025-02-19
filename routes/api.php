<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('/accountinfo', function (Request $request) {
    // Obtém o token do cabeçalho Authorization
    $token = $request->bearerToken();

    // Obtém o nome de usuário do cabeçalho ou do corpo da requisição
    $headerUsername = $request->header('X-Username') ?? $request->input('username');

    if (!$token) {
        return response()->json([
            "status" => "error",
            "message" => "Authorization token is required."
        ], 401);
    }

    if (!$headerUsername) {
        return response()->json([
            "status" => "error",
            "message" => "Username is required."
        ], 400);
    }

    // Obtém o usuário autenticado via Sanctum
    $user = $request->user();

    // Compara o username enviado com o username fixo "britto"
    if ($headerUsername !== 'britto') {
        return response()->json([
            "status" => "error",
            "message" => "Invalid username."
        ], 403);
    }

    // Retorna os dados da conta no formato Dhru Fusion
    return Response::json([
        "status" => "success",
        "message" => "Account information retrieved successfully.",
        "data" => [
            "USERID" => $user->id,
            "USERNAME" => $headerUsername,  // Use o username fornecido no cabeçalho
            "EMAIL" => $user->email,
            "CREDIT" => 100.50,  // Ajuste conforme sua lógica de saldo
            "CURRENCY" => "USD",
            "ACCOUNT_TYPE" => "reseller",
            "REGISTEREDDATE" => $user->created_at->toIso8601String(),
            "UPDATEDDATE" => $user->updated_at->toIso8601String(),
        ]
    ]);
})->middleware('auth:sanctum');
 