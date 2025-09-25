<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class EmbedScriptController extends Controller
{
    public function script(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return response('// Missing token', 400)
                ->header('Content-Type', 'application/javascript');
        }

        $project = Project::where('token', $token)
            ->with('theme')
            ->first();

        if (!$project || !$project->theme) {
            return response('// Invalid token or theme not found', 404)
                ->header('Content-Type', 'application/javascript');
        }

        // Render Blade without <script>
        $html = view('components.chat', [
            'theme' => $project->theme,
            'token' => $project->token
        ])->render();

        // Extract inline script manually
        preg_match('/<script>([\s\S]*)<\/script>/', $html, $matches);
        $inlineScript = $matches[1] ?? '';

        // Remove the <script> block from HTML
        $htmlWithoutScript = preg_replace('/<script>[\s\S]*<\/script>/', '', $html);

        // Escape HTML for JS string
        $escapedHtml = json_encode($htmlWithoutScript);

        $js = <<<JAVASCRIPT
            (function() {
                try {
                    // Inject HTML
                    var container = document.createElement('div');
                    container.innerHTML = $escapedHtml;
                    document.body.appendChild(container);

                    // Run inline script AFTER DOM has the widget
                    (function() {
                        $inlineScript
                    })();
                } catch (e) {
                    console.error("Embed error", e);
                }
            })();
        JAVASCRIPT;

        return response($js, 200)
            ->header('Content-Type', 'application/javascript');
    }

}