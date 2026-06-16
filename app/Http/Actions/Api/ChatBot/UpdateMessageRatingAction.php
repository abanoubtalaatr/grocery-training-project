<?php 


namespace App\Http\Actions\Api\ChatBot;

use App\Models\ChatbotMessage;

class UpdateMessageRatingAction
{
    public function execute(
        int $messageId,
        int $rating
    ): void {

        ChatbotMessage::query()
            ->where('id', $messageId)
            ->update([
                'rating' => $rating
            ]);
    }
}