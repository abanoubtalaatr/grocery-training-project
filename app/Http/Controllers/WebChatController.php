<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WebChatController extends Controller
{
    public function __construct(private readonly ChatbotService $chatbotService) {}

    public function index()
    {
        return view('chat');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $user = $this->getOrCreateDemoUser();
        $conversationId = session('chat_conversation_id');

        $result = $this->chatbotService->chat(
            user: $user,
            question: $validated['message'],
            conversationId: $conversationId,
            locale: null,
        );

        session(['chat_conversation_id' => $result['conversation_id']]);

        return response()->json([
            'answer' => $result['answer'],
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->session()->forget('chat_conversation_id');

        return response()->json(['reset' => true]);
    }

    private function getOrCreateDemoUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'webchat@grocery.demo'],
            [
                'firstname' => 'Web',
                'lastname' => 'Chat Demo',
                'username' => 'webchat_demo',
                'password' => Hash::make(Str::random(32)),
            ]
        );
    }
}
