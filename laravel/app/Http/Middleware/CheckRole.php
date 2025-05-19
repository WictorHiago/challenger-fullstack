<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        try {
            if (!$request->user() || $request->user()->role !== $role) {
                return response()->json([
                    'error' => 'Acesso não autorizado',
                    'message' => 'Você não tem permissão para acessar este recurso'
                ], 403);
            }

            return $next($request);

        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao verificar permissão',
            ], 500);
        }
    }
}
