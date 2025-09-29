<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Project;

class GlobalCorsFromProjects
{
    public function handle(Request $request, Closure $next): Response
    {
        $origin = rtrim($request->headers->get('Origin'), '/');

        $host = $request->getSchemeAndHttpHost();

        // If local environment, allow everything
        // if (app()->environment('local')) {
        //     return $next($request);
        // }

        $allowedOrigins = Project::pluck('client_url')
            ->filter()
            ->map(fn($url) => rtrim($url, '/'))
            ->toArray();

        if ($origin && $origin !== $host && !in_array($origin, $allowedOrigins)) {
            return response()->json([
                'message' => 'CORS not allowed for this frontend URL.',
                'origin' => $origin
            ], 403);
        }

        if ($origin && in_array($origin, $allowedOrigins)) {
            $response = $next($request);
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Token-Header, Authorization');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Vary', 'Origin');

            if ($request->getMethod() === 'OPTIONS') {
                return response('', 204, $response->headers->all());
            }

            return $response;
        }

        return $next($request);
    }
}
