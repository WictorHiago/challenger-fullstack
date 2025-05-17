<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rota de teste para verificar se a API está funcionando
Route::get('/test', function() {
    return response()->json(['message' => 'API is working!']);
});

// Rotas públicas de autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas por autenticação
Route::middleware('auth:sanctum')->group(function () {
    // Rotas de autenticação
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Rotas de usuários
    Route::apiResource('users', UserController::class);
    
    // Rotas de categorias
    Route::apiResource('categories', CategoryController::class);
    
    // Rotas de produtos
    Route::apiResource('products', ProductController::class);
    
    // Rota de busca de produtos
    Route::get('/products/search/{term}', [ProductController::class, 'search']);
});
