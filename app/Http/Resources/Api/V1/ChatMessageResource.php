<?php
namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $conversationId = $this->conversation_id ?? $this->session_id;

        return [
            'id' => $this->id,
            'conversation_id' => $conversationId,
            'session_id' => $conversationId, 
            'question' => $this->question,
            'answer' => $this->answer,
            'rating' => $this->rating,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}