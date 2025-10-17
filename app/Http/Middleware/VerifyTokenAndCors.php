<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Project;

class VerifyTokenAndCors
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Token-Header');
        $origin = rtrim($request->headers->get('Origin'), '/');
        $host = $request->getSchemeAndHttpHost();

        // check the token if missed so given error
        if (empty($token)) {
            return response()->json([
                'error' => 'Missing X-Token-Header'
            ], 401);
        }

        // set the origin as post and get request 
        if (!$origin) $origin = $request->getSchemeAndHttpHost();
        else $origin = rtrim($origin, '/');

        $project = Project::where('token', $token)->first();
        if (!$project) {
            return response()->json([
                'error' => 'Something went wrong!'
            ], 403);
        }

        $allowedOrigins = Project::where('token', $token)
                            ->pluck('client_url')
                            ->filter()
                            ->map(fn($url) => rtrim($url, '/'))
                            ->toArray();

        if ($origin && !in_array($origin, $allowedOrigins)) {
            return response()->json([
                'message' => 'CORS not allowed for this frontend URL.',
                'origin' => $origin
            ], 403);
        }

        $request->attributes->set('openai', [
            'IMAGE_GENERATION' => $project->image_generation ?? 0,
            'OPENAI_API_KEY' => $project->openai_api_key ?? null,
            'OPENAI_PROMPT_ID' => $project->openai_prompt_id ?? null
        ]);

        $response = $next($request);

        // If origin is allowed, set CORS headers
        if ($origin && in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Token-Header, Authorization');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Vary', 'Origin');
        }

        // Handle preflight OPTIONS request (immediately respond)
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 204, $response->headers->all());
        }

        return $response;
    }
}