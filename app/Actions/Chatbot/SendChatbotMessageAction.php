<?php

namespace App\Actions\Chatbot;

use App\Models\ChatbotMessage;
use App\Models\User;
use App\Services\ChatbotService;
use Lorisleiva\Actions\Concerns\AsAction;

class SendChatbotMessageAction
{
    use AsAction;

    public function __construct(private readonly ChatbotService $chatbotService) {}

    /**
     * Send message to the chatbot service and optionally update message rating.
     */
    public function handle(User $user, string $question, ?string $conversationId, ?string $locale, ?int $rating = null): array
    {
        $result = $this->chatbotService->chat(
            user: $user,
            question: $question,
            conversationId: $conversationId,
            locale: $locale,
        );

        if ($rating !== null) {
            ChatbotMessage::where('id', $result['id'])->update(['rating' => $rating]);
            $result['rating'] = $rating;
        }

        return $result;
    }
}
