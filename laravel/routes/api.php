<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rotas públicas (sem autenticação)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rotas protegidas por autenticação
Route::middleware('auth:sanctum')->group(function () {
    // Rota de logout (requer autenticação)
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Rotas acessíveis por ambos os roles (user e admin)
    // Produtos - Listagem
    Route::get('/products', [ProductController::class, 'findAll']);
    Route::get('/products/{id}', [ProductController::class, 'findById']);
    
    // Categorias - Listagem
    Route::get('/categories', [CategoryController::class, 'findAll']);
    Route::get('/categories/{id}', [CategoryController::class, 'findById']);
    
    // Rotas acessíveis apenas por usuários com role "user"
    Route::middleware('role:user')->group(function () {
        // Usuário pode ver apenas seu próprio perfil
        Route::get('/profile', function (Request $request) {
            return response()->json([
                'user' => $request->user()
            ]);
        });
    });
    
    // Rotas acessíveis apenas por usuários com role "admin"
    Route::middleware('role:admin')->group(function () {
        // Gerenciamento de usuários
        Route::get('/users', [UserController::class, 'findAll']);
        Route::get('/users/{id}', [UserController::class, 'findById']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'delete']);
        
        // Gerenciamento de categorias
        Route::post('/categories', [CategoryController::class, 'create']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'delete']);
        
        // Gerenciamento de produtos
        Route::post('/products', [ProductController::class, 'create']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'delete']);
    });
});
