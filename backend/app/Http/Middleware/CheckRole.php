<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated',
                'error' => 'Unauthorized',
                'status_code' => 401
            ], 401);
        }

        // Se o usuário é admin, permitir acesso a tudo
        if ($request->user()->role === 'admin') {
            return $next($request);
        }

        // Verificar se o usuário tem a role necessária
        if ($request->user()->role !== $role) {
            Log::info('Unauthorized access attempt - insufficient role', [
                'user_id' => $request->user()->id,
                'user_role' => $request->user()->role,
                'required_role' => $role,
                'path' => $request->path(),
                'method' => $request->method()
            ]);

            return response()->json([
                'message' => 'You do not have permission to access this resource',
                'error' => 'Forbidden',
                'status_code' => 403
            ], 403);
        }

        return $next($request);
    }
}
