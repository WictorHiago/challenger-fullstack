<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Personalizar a resposta para erros de autenticação
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                Log::info('API Authentication Error', [
                    'error' => $e->getMessage(),
                    'path' => $request->path(),
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'message' => 'Unauthenticated. Invalid or expired token.',
                    'error' => 'Unauthorized',
                    'status_code' => 401
                ], 401);
            }
        });

        // Personalizar a resposta para erros gerais em endpoints da API
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $status = 500;
                
                // Verificar se é uma exceção HTTP do Laravel
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    $status = $e->getStatusCode();
                } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                    $status = 422; // Unprocessable Entity para erros de validação
                } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $status = 404; // Not Found para modelos não encontrados
                }
                
                Log::error('API Error', [
                    'error' => $e->getMessage(),
                    'exception' => get_class($e),
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                    'status' => $status
                ]);
                
                $message = $status == 500 ? 'Internal server error.' : $e->getMessage();
                
                // Para exceções de validação, incluir os erros de validação
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'message' => 'Validation error',
                        'errors' => $e->errors(),
                        'status_code' => $status
                    ], $status);
                }
                
                return response()->json([
                    'message' => $message,
                    'error' => $e->getMessage(),
                    'status_code' => $status
                ], $status);
            }
        });
    }
}
