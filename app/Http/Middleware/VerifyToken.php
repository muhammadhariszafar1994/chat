<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Project;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Token-Header');

        if (empty($token)) {
            return response()->json([
                'error' => 'Missing X-Token-Header'
            ], 401);
        }

        $project = Project::where('token', $token)->first();

        if (!$project) {
            return response()->json([
                'error' => 'Invalid token'
            ], 403);
        }

        $openai = [
            'OPENAI_API_KEY' => $project->openai_api_key ?? null,
            'OPENAI_PROMPT_ID' => $project->openai_prompt_id ?? null
        ];

        $request->attributes->set('openai', $openai);

        return $next($request);
    }
}