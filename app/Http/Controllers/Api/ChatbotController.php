<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotMessage;
use App\Services\ChatbotService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Send a message to the AI assistant.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question' => ['required_without:message', 'string', 'max:1000'],
            'message' => ['required_without:question', 'string', 'max:1000'],
            'conversation_id' => ['nullable', 'uuid'],
            'session_id' => ['nullable', 'uuid'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'locale' => ['nullable', 'string', 'in:ar,en'],
        ]);

        $question = $validated['question'] ?? $validated['message'];
        $conversationId = $validated['conversation_id'] ?? $validated['session_id'] ?? null;

        $result = $this->chatbotService->chat(
            user: $request->user(),
            question: $question,
            conversationId: $conversationId,
            locale: $validated['locale'] ?? null,
        );

        if (isset($validated['rating'])) {
            ChatbotMessage::where('id', $result['id'])->update(['rating' => $validated['rating']]);
            $result['rating'] = $validated['rating'];
        }

        return self::successResponse('Chat response generated successfully', [
            'id' => $result['id'],
            'conversation_id' => $result['conversation_id'],
            'session_id' => $result['conversation_id'],
            'question' => $result['question'],
            'answer' => $result['answer'],
            'rating' => $result['rating'],
        ]);
    }

    /**
     * Get user's chatbot conversation history.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->input('per_page', 15), 1), 50);
        $messages = $request->user()
            ->chatbotMessages()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $items = $messages->getCollection()->map(fn (ChatbotMessage $m) => [
            'id' => $m->id,
            'session_id' => $m->session_id,
            'question' => $m->question,
            'answer' => $m->answer,
            'rating' => $m->rating,
            'created_at' => $m->created_at,
        ]);

        return self::collectionResponse('Chat history retrieved successfully', $items);
    }
}
