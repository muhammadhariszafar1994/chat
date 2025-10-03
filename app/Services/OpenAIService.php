<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;
    protected $baseUrl;
    protected $assistantId;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->assistantId = env('OPENAI_PROMPT_ID');
        $this->baseUrl = 'https://api.openai.com/v1';
    }

    public function generateConversation($openai, $topic = 'demo')
    {
        $data = [
            'metadata' => [ 'assistant_id' => $openai['OPENAI_PROMPT_ID'] ]
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
}