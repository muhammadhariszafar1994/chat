<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\OpenAIService;
use App\Models\Visitor;

class OpenAIChatController extends Controller
{
    protected $openAIService;

    public function __construct(
        OpenAIService $openAIService
    )
    {
        $this->openAIService = $openAIService;
    }

    public function sendMessageToConversation(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ip = $request->ip();
        $openai = $request->attributes->get('openai');
        $message = $request->input('message');

        $conversationId = $this->findOrCreateConversation($ip, $openai);
        
        $response = $this->openAIService->sendMessageToConversation($openai, $conversationId, $message);

        if ($response) {
            return response()->json(['message' => 'Message sent successfully!', 'response' => $response]);
        } else {
            return response()->json(['message' => 'Failed to send message'], 500);
        }
    }

    public function getConversationResponses(Request $request)
    {
        $ip = $request->ip();
        $openai = $request->attributes->get('openai');

        $conversationId = $this->findOrCreateConversation($ip, $openai);
        $responses = $this->openAIService->getConversationResponses($openai, $conversationId);

        if ($responses) {
            return response()->json(['data' => $responses]);
        } else {
            return response()->json(['message' => 'Failed to retrieve conversation responses'], 500);
        }
    }

    private function findOrCreateConversation($ip = null, $openai = null) {
        $conversationId = null;
        $visitor = Visitor::query()->whereIpAddress($ip)->first();

        if(!$visitor) {
            $conversation = $this->openAIService->generateConversation($openai);
            if($conversation) $conversationId = $conversation['id'];
            
            $visitor = Visitor::query()->create([
                'ip_address' => $ip,
                'conversation_id' => $conversationId
            ]);

            return $conversationId;
        }

        $conversationId = $visitor['conversation_id'];

        return $conversationId;
    }



    /**
     * @param $userInput
     * @param null $previousResponseId
     * @return array
     */
    private function generateResponse($openai, $userInput, $previousResponseId = null): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $openai['OPENAI_API_KEY'],
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/responses', [
                "prompt" => [
                    "id" => $openai['OPENAI_PROMPT_ID'],
                    "version" => "5"
                ],
                "input" => $userInput,
            ]);

            $data = $response->json();

            $output = $data['output'][0] ?? $data;
            $response_id = $data['id'] ?? '';
            $msg = $output['content'][0]['text'] ?? "Sorry, I'm unable to respond right now. Please try again later.";

            return [
                'success' => true,
                'data' => $output,
                'msg' => [
                    'reply' => $msg,
                    'response_id' => $response_id,
                ],
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'data' => $exception->getMessage(),
                'msg' => $exception->getMessage(),
            ];
        }
    }

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $openai = $request->attributes->get('openai');

        $response = $this->generateResponse($openai, 'Hello');
        $msg = $response['msg'] ?? [];

        return response()->json([
            'reply' => $msg['reply'],
            'response_id' => $msg['response_id'],
            'debug' => $response,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        try {
            $openai = $request->attributes->get('openai');

            $userInput = $request->message;
            $previousResponseId = $request->previous_response_id;

            $response = $this->generateResponse($openai, $userInput, $previousResponseId);
            $msg = $response['msg'] ?? [];

            $response['user_input'] = $userInput;
            $response['previous_response_id'] = $previousResponseId;

            return response()->json([
                'reply' => $msg['reply'],
                'response_id' => $msg['response_id'],
                'debug' => $response,
            ]);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'data' => $exception->getMessage(),
                'msg' => $exception->getMessage(),
            ];
        }
    }
}
