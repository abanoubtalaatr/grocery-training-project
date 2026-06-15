<?php

namespace App\Http\Resources\Chatbot;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatbotResponseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],

            'conversation_id'
                => $this['conversation_id'],

            'session_id'
                => $this['conversation_id'],

            'question'
                => $this['question'],

            'answer'
                => $this['answer'],

            'rating'
                => $this['rating'],
        ];
    }
}