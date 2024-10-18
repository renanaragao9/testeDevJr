<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
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
    Route::post('/login', [AuthController::class, 'login']); // se quiser bloquear o usuario com numeros de tentativas utilzie o ->middleware('login.attempt')
});

// Rotas com autenticação necessaria
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories',    CategoryController::class);
    Route::apiResource('products',      ProductController::class);
    
    Route::middleware('auth:sanctum')->post('/images', [ImageController::class, 'store']);
    Route::middleware('auth:sanctum')->get('/images/{id}/download', [ImageController::class, 'download']);

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Teste de conexão
Route::get('/status', function () {
    $dbStatus = \DB::connection()->getPdo() ? 'Conexão com o banco de dados feita com sucesso!'     : 'Erro ao conectar ao banco de dados.';

    $emailStatus = true; 
    $emailConnectionStatus = $emailStatus   ? 'Conexão com o servidor de e-mail está funcionando!'  : 'Erro ao conectar ao servidor de e-mail.';

    return response()->json([
        'message' => 'Backend está funcionando!',
        'status' => 'success',
        'db_status' => $dbStatus,
        'email_status' => $emailConnectionStatus,
    ]);
});
