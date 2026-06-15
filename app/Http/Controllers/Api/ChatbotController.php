<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chatbot\ChatHistoryRequest;
use App\Http\Requests\Chatbot\ChatRequest;
use App\Http\Resources\Chatbot\ChatbotMessageResource;
use App\Http\Resources\Chatbot\ChatbotResponseResource;
use App\Models\ChatbotMessage;
use App\Services\ChatbotService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ChatbotService $chatbotService
    ) {}

    public function chat(
        ChatRequest $request
    ): JsonResponse {

        $result = $this->chatbotService->chat(
            user: $request->user(),
            question: trim($request->question),
            conversationId:
                $request->conversation_id
                ?? $request->session_id,
            locale: $request->locale,
        );

        if ($request->filled('rating')) {

            ChatbotMessage::query()
                ->where('id', $result['id'])
                ->update([
                    'rating' => $request->rating,
                ]);

            $result['rating']
                = $request->rating;
        }

        return $this->successResponse(
            'Chat response generated successfully',
            new ChatbotResponseResource(
                $result
            )
        );
    }

    public function history(
        ChatHistoryRequest $request
    ): JsonResponse {

        $messages = $request->user()
            ->chatbotMessages()
            ->latest()
            ->paginate(
                $request->validated(
                    'per_page',
                    15
                )
            );

        return $this->successResponse(
            'Chat history retrieved successfully',
            [
                'items' =>
                    ChatbotMessageResource::collection(
                        $messages->items()
                    ),

                'pagination' => [
                    'current_page'
                        => $messages->currentPage(),

                    'last_page'
                        => $messages->lastPage(),

                    'per_page'
                        => $messages->perPage(),

                    'total'
                        => $messages->total(),

                    'from'
                        => $messages->firstItem(),

                    'to'
                        => $messages->lastItem(),
                ],
            ]
        );
    }

    public function suggestions(): JsonResponse
    {
        return $this->successResponse(
            'Suggestions retrieved successfully',
            [
                'suggestions' => [
                    [
                        'id' => 'faq',
                        'label' => 'FAQs',
                        'question' => 'What are the frequently asked questions?',
                    ],
                    [
                        'id' => 'orders',
                        'label' => 'Track order',
                        'question' => 'How do I track my order?',
                    ],
                ],
            ]
        );
    }
}