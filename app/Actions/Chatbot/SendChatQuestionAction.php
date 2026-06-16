<?php

namespace App\Actions\Chatbot;

use App\Models\ChatbotMessage;
use App\Models\User;
use App\Services\ChatbotService;

class SendChatQuestionAction
{
    public function __construct(private readonly ChatbotService $chatbotService) {}

   public function execute(\App\Models\User $user, array $data): ChatbotMessage
{
    $result = $this->chatbotService->chat(
        user: $user,
        question: trim($data['question']),
        conversationId: $data['conversation_id'],
        locale: $data['locale'] ?? null,
    );

    $message = ChatbotMessage::findOrFail($result['id']);

    if (!empty($data['rating'])) {
        $message->update(['rating' => $data['rating']]);
    }

    return $message;
}
}