<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force Accept header to application/json para todas as requisições da API
        $request->headers->set('Accept', 'application/json');
        
        // Tenta processar a requisição e captura quaisquer exceções
        try {
            $response = $next($request);
            
            // Se a resposta não for JSON, força o header Content-Type
            if (!$response->headers->has('Content-Type') || $response->headers->get('Content-Type') !== 'application/json') {
                $response->headers->set('Content-Type', 'application/json');
            }
            
            return $response;
        } catch (\Throwable $e) {
            // Se ocorrer uma exceção não tratada, retorna uma resposta JSON
            $status = 500;
            $message = 'Internal Server Error';
            
            // Determina o código de status e mensagem com base no tipo de exceção
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                $status = 401;
                $message = 'Unauthenticated. Invalid or expired token.';
            } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $message = $e->getMessage();
            }
            
            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'status_code' => $status
            ], $status);
        }
    }
}
