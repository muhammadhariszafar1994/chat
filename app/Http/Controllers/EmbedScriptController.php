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

        // ✅ Load Blade from resources/views/components/chat.blade.php
        $html = view('components.chat', ['theme' => $project->theme])->render();

        // ✅ Safely escape into JS string
        $escapedHtml = json_encode($html);

        $js = <<<JAVASCRIPT
            (function() {
                try {
                    var container = document.createElement('div');
                    container.innerHTML = $escapedHtml;
                    document.body.appendChild(container);
                } catch (e) {
                    console.error("Embed error", e);
                }
            })();
        JAVASCRIPT;

        return response($js, 200)
            ->header('Content-Type', 'application/javascript');
    }

}
