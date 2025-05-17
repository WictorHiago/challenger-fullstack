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
    
    // Rotas acessíveis por usuários comuns (role: user)
    Route::middleware('role:user')->group(function () {
        // Listar produtos
        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/search/{term}', [ProductController::class, 'search']);
        Route::get('products/{product}', [ProductController::class, 'show']);
        
        // Criar produtos
        Route::post('products', [ProductController::class, 'store']);
        
        // Listar categorias
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('categories/{category}', [CategoryController::class, 'show']);
    });
    
    // Rotas exclusivas para administradores (role: admin)
    Route::middleware('role:admin')->group(function () {
        // CRUD completo de usuários
        Route::apiResource('users', UserController::class);
        
        // Operações de edição e exclusão de produtos
        Route::put('products/{product}', [ProductController::class, 'update']);
        Route::patch('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'destroy']);
        
        // CRUD completo de categorias (exceto listar e mostrar, que já estão disponíveis para users)
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{category}', [CategoryController::class, 'update']);
        Route::patch('categories/{category}', [CategoryController::class, 'update']);
        Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    });
});
