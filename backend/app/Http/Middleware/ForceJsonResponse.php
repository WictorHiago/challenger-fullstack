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
        // Force Accept header to application/json
        $request->headers->set('Accept', 'application/json');
        
        // Get the response
        $response = $next($request);
        
        // If the response is not already a JSON response, convert it
        if (!$response->headers->has('Content-Type') || $response->headers->get('Content-Type') !== 'application/json') {
            $response->headers->set('Content-Type', 'application/json');
        }
        
        return $response;
    }
}
