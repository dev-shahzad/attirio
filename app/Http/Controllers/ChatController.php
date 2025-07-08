<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatController extends Controller
{



    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with('latestMessage')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('chat', compact('conversations'));
    }

    public function list()
    {
        $conversations = Auth::user()->conversations()
            ->with('latestMessage')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($conversation) {
                return [
                    'id' => $conversation->id,
                    'title' => $conversation->title ?? 'New Chat',
                    'updated_at' => $conversation->updated_at->format('M j, H:i'),
                    'model' => $conversation->model,
                ];
            });

        return response()->json($conversations);
    }

    public function show($id)
    {
        $conversation = Auth::user()->conversations()
            ->with('messages')
            ->findOrFail($id);

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'model' => $conversation->model,
            ],
            'messages' => $conversation->messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'role' => $message->role,
                    'content' => $message->content,
                    'created_at' => $message->created_at->format('H:i'),
                ];
            }),
        ]);
    }

    public function createConversation(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
        ]);

        $conversation = Auth::user()->conversations()->create([
            'model' => $request->model,
            'title' => 'New Chat',
        ]);

        return response()->json([
            'id' => $conversation->id,
            'title' => $conversation->title,
            'model' => $conversation->model,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);

        $conversation = Auth::user()->conversations()->findOrFail($request->conversation_id);


        $userMessage = $conversation->messages()->create([
            'role' => 'user',
            'content' => $request->message,
        ]);


        if ($conversation->messages()->count() === 1) {
            $title = $this->generateTitle($request->message);
            $conversation->update(['title' => $title]);
        }


        $messages = $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) {
                return [
                    'role' => $message->role,
                    'content' => $message->content,
                ];
            })->toArray();


        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('app.open_ai_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $conversation->model,
                        'messages' => $messages,
                        'max_tokens' => 1000,
                        'temperature' => 0.7,
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                $assistantMessage = $data['choices'][0]['message']['content'];


                $aiMessage = $conversation->messages()->create([
                    'role' => 'assistant',
                    'content' => $assistantMessage,
                ]);


                $conversation->touch();

                return response()->json([
                    'success' => true,
                    'user_message' => [
                        'id' => $userMessage->id,
                        'role' => 'user',
                        'content' => $userMessage->content,
                        'created_at' => $userMessage->created_at->format('H:i'),
                    ],
                    'assistant_message' => [
                        'id' => $aiMessage->id,
                        'role' => 'assistant',
                        'content' => $aiMessage->content,
                        'created_at' => $aiMessage->created_at->format('H:i'),
                    ],
                    'conversation' => [
                        'title' => $conversation->title,
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to get response from OpenAI API',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing your request',
            ], 500);
        }
    }

    public function delete($id)
    {
        $conversation = Auth::user()->conversations()->findOrFail($id);
        $conversation->delete();

        return response()->json(['success' => true]);
    }

    private function generateTitle(string $message): string
    {

        $title = substr($message, 0, 50);
        if (strlen($message) > 50) {
            $title .= '...';
        }
        return $title;
    }

    public function getAvailableModels()
    {
        $models = [
            ['id' => 'gpt-4', 'name' => 'GPT-4'],
            ['id' => 'gpt-4-turbo', 'name' => 'GPT-4 Turbo'],
            ['id' => 'gpt-3.5-turbo', 'name' => 'GPT-3.5 Turbo'],
        ];

        return response()->json($models);
    }
}