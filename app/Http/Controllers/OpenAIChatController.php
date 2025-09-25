<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenAIChatController extends Controller
{

    /**
     * @param $userInput
     * @param null $previousResponseId
     * @return array
     */
    private function generateResponse($openai, $userInput, $previousResponseId = null): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                // 'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),

                'Authorization' => 'Bearer ' . $openai['OPENAI_API_KEY'],
            ])->post('https://api.openai.com/v1/responses', [
                "prompt" => [
                    "id" => $openai['OPENAI_PROMPT_ID'],
                    "version" => "5"
                ],
                "input" => $userInput,
                // "instructions" => $previousResponseId,
                // "previous_response_id" => $previousResponseId,
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
