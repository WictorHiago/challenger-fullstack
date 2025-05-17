<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        // Verificamos se é uma requisição da API pelo URL ou pelo header Accept
        if ($request->expectsJson() || $request->is('api/*')) {
            Log::info('Unauthorized access attempt', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'method' => $request->method(),
                'headers' => $request->header()
            ]);
            
            // Retornamos diretamente uma resposta JSON em vez de usar abort()
            return response()->json([
                'message' => 'Unauthenticated. Invalid or expired token.',
                'error' => 'Unauthorized',
                'status_code' => 401
            ], 401);
        }
        
        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
