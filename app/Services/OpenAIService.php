<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;
    protected $baseUrl;
    protected $assistantId;
    protected $adminKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->assistantId = env('OPENAI_PROMPT_ID');
        $this->baseUrl = 'https://api.openai.com/v1';
        $this->adminKey = env('OPENAI_ADMIN_KEY');
    }

    public function generateConversation($openai, $topic = 'demo')
    {
        $data = [
            'metadata' => ['assistant_id' => $openai['OPENAI_PROMPT_ID']]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $openai['OPENAI_API_KEY'],
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/conversations', $data);

        return $response->successful() ? $response->json() : null;
    }

    public function sendMessageToConversation($openai, $conversationId, $message, $topic = 'demo')
    {
        $data = [
            "prompt" => [
                "id" => $openai['OPENAI_PROMPT_ID'],
                "version" => "5"
            ],
            'conversation' => $conversationId,
            "input" => $message,
            'store' => true
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $openai['OPENAI_API_KEY'],
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/responses', $data);

        return $response->successful() ? $response->json() : null;
    }

    public function getConversationResponses($openai, $conversationId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $openai['OPENAI_API_KEY'],
            'Content-Type' => 'application/json',
        ])->get($this->baseUrl . '/conversations/' . $conversationId . '/items');

        return $response->successful() ? $response->json() : null;
    }

    public function generateImage($openai, $prompt)
    {
        $data = [
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'n' => 1,
            'size' => '1024x1024'
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $openai['OPENAI_API_KEY'],
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/images/generations', $data);

        return $response->successful() ? $response->json() : null;
    }

    public function sendMessageToConversationWithImage($openai, $conversationId, $message, $imagePrompt = null)
    {
        $messageData = [
            "prompt" => [
                "id" => $openai['OPENAI_PROMPT_ID'],
                "version" => "5"
            ],
            'conversation' => $conversationId,
            "input" => $message,
            'store' => true
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $openai['OPENAI_API_KEY'],
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/responses', $messageData);

        if ($response->successful()) {
            $generatedMessage = $response->json();
            $imageResponse = $this->generateImage($openai, $imagePrompt);

            if ($imageResponse) {
                $imageUrl = $imageResponse['data'][0]['url'];
                $imageMessage = [
                    "prompt" => [
                        "id" => $openai['OPENAI_PROMPT_ID'],
                        "version" => "5"
                    ],
                    'conversation' => $conversationId,
                    'input' => "Here is an image related to the conversation: $imageUrl",
                    'store' => true
                ];

                $this->sendMessageToConversation($openai, $conversationId, "Here is an image related to the conversation: $imageUrl");
            }

            return $generatedMessage;
        }

        return null;
    }

    /**
     * List all projects from OpenAI.
     */
    public function listProjects($after = null, $limit = 20, $includeArchived = false)
    {
        $params = [
            'limit' => $limit,
            'include_archived' => $includeArchived ? 'true' : 'false',
        ];

        if ($after) {
            $params['after'] = $after;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->adminKey,
            'Content-Type' => 'application/json',
        ])->get($this->baseUrl . '/organization/projects', $params);

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Create a new OpenAI project.
     */
    public function createProject($name)
    {
        $data = [
            'name' => $name,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->adminKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/organization/projects', $data);

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Archive a specific project by ID.
     */
    public function archiveProject($projectId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->adminKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . "/organization/projects/{$projectId}/archive");

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Get details of a specific project by ID.
     */
    public function getProjectDetails($projectId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->adminKey,
            'Content-Type' => 'application/json',
        ])->get($this->baseUrl . "/organization/projects/{$projectId}");

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Update the project.
     */
    public function updateProject($projectId, $name)
    {
        $data = ['name' => $name];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->adminKey,
            'Content-Type' => 'application/json',
        ])->put($this->baseUrl . "/organization/projects/{$projectId}", $data);

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Delete a project.
     */
    public function deleteProject($projectId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->adminKey,
            'Content-Type' => 'application/json',
        ])->delete($this->baseUrl . "/organization/projects/{$projectId}");

        return $response->successful();
    }

}
