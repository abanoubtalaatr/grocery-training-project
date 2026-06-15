<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray($request): array
    {
        $token = $this->resource;
        $currentTokenId = $request->user()?->currentAccessToken()?->id;

        return [
            'id' => $token->id,
            'name' => $token->name,
            'last_used_at' => $token->last_used_at?->toIso8601String(),
            'is_current' => (string) $token->id === (string) $currentTokenId,
            'created_at' => $token->created_at?->toIso8601String(),
        ];
    }
}
