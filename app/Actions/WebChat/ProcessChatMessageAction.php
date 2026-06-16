<?php

namespace App\Actions\WebChat;

use App\Repositories\UserRepository;
use App\Services\ChatbotService;

class ProcessChatMessageAction
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ChatbotService $chatbotService
    ) {}

    /**
     * Process a chat message from a web chat demo user.
     *
     * @param string $message
     * @param string|null $conversationId
     * @return array
     */
    public function __invoke(string $message, ?string $conversationId): array
    {
        $message = trim($message);
        $user = $this->userRepository->getOrCreateDemoUser();

        return $this->chatbotService->chat(
            user: $user,
            question: $message,
            conversationId: $conversationId,
            locale: null
        );
    }
}
