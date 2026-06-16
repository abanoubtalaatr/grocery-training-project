<?php

namespace App\Http\Controllers;

use App\Actions\WebChat\ProcessChatMessageAction;
use App\Http\Requests\WebChat\SendMessageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class WebChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(SendMessageRequest $request, ProcessChatMessageAction $action): JsonResponse
    {
        try {
            $validated = $request->validated();
            $conversationId = session('chat_conversation_id');

            $result = $action(
                message: $validated['message'],
                conversationId: $conversationId,
            );

            session(['chat_conversation_id' => $result['conversation_id']]);

            return response()->json([
                'answer' => $result['answer'],
            ]);

        } catch (Throwable $e) {
            Log::error('Web chat error', ['message' => $e->getMessage()]);

            return response()->json([
                'error' => config('app.debug') ? $e->getMessage() : 'Failed to get a response. Please try again.',
            ], 500);
        }
    }

    public function reset(Request $request): JsonResponse
    {
        $request->session()->forget('chat_conversation_id');

        return response()->json(['reset' => true]);
    }
}
