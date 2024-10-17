<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rotas para autenticação
Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    //Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Rotas com autenticação necessaria
Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/resource', [ResourceController::class, 'index']);
    // Route::post('/resource', [ResourceController::class, 'store']);
    // Route::put('/resource/{id}', [ResourceController::class, 'update']);
    // Route::delete('/resource/{id}', [ResourceController::class, 'destroy']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::middleware('auth:sanctum')->post('/images', [ImageController::class, 'store']);
    
    // Rota de logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Rota de testes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user(); // Retorna os dados do usuário autenticado
});

Route::get('/teste-rota', function () {
    return response()->json(['message' => 'Backend está funcionando!!']);
});