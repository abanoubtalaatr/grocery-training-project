<?php

namespace App\Http\Controllers\Api;

use App\Actions\Chatbot\SendChatbotMessageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChatbotRequest;
use App\Models\ChatbotMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Send a message to the AI assistant.
     *
     * Supports an optional `session_id` (UUID) to maintain conversation history across requests.
     */
    public function chat(ChatbotRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $conversationId = $validated['conversation_id'] ?? $validated['session_id'] ?? null;

        $result = SendChatbotMessageAction::run(
            $user,
            $validated['question'],
            $conversationId,
            $validated['locale'] ?? null,
            $validated['rating'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Chat response generated successfully',
            'data' => [
                'id' => $result['id'],
                'conversation_id' => $result['conversation_id'],
                'session_id' => $result['conversation_id'],  // backwards compatibility
                'question' => $result['question'],
                'answer' => $result['answer'],
                'rating' => $result['rating'],
            ],
        ]);
    }

    /**
     * Get current user's chatbot conversation history (paginated).
     */
    public function history(Request $request): JsonResponse
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

        return response()->json([
            'success' => true,
            'message' => 'Chat history retrieved successfully',
            'data' => [
                'items' => $items,
                'pagination' => [
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                    'from' => $messages->firstItem(),
                    'to' => $messages->lastItem(),
                ],
            ],
        ]);
    }

    /**
     * Get suggested quick-reply questions (localised).
     */
    public function suggestions(Request $request): JsonResponse
    {
        $locale = $request->input('locale', 'en');
        $isAr = $locale === 'ar';

        $suggestions = $isAr
            ? [
                ['id' => 'faq',      'label' => 'أسئلة شائعة',        'question' => 'ما هي الأسئلة الشائعة؟'],
                ['id' => 'orders',   'label' => 'تتبع الطلب',          'question' => 'كيف أتتبع طلبي؟'],
                ['id' => 'payment',  'label' => 'طرق الدفع',           'question' => 'ما طرق الدفع المتاحة؟'],
                ['id' => 'products', 'label' => 'المنتجات والمفضلة',   'question' => 'ما المنتجات المتاحة والعروض؟'],
                ['id' => 'offers',   'label' => 'كوبونات وعروض',       'question' => 'ما العروض وكوبونات الخصم الحالية؟'],
            ]
            : [
                ['id' => 'faq',      'label' => 'FAQs',              'question' => 'What are the frequently asked questions?'],
                ['id' => 'orders',   'label' => 'Track order',        'question' => 'How do I track my order?'],
                ['id' => 'payment',  'label' => 'Payment methods',    'question' => 'What payment methods do you accept?'],
                ['id' => 'products', 'label' => 'Products & offers',  'question' => 'What products and offers do you have?'],
                ['id' => 'offers',   'label' => 'Coupons & offers',   'question' => 'What promo codes or offers are available?'],
            ];

        return response()->json([
            'success' => true,
            'message' => 'Suggestions retrieved successfully',
            'data' => ['suggestions' => $suggestions],
        ]);
    }
}

