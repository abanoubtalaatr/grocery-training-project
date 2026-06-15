<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        $notification = $this->resource;
        $data = $notification->data ?? [];

        return [
            'id' => $notification->id,
            'type' => $data['type'] ?? 'order',
            'title' => $data['title'] ?? 'Order update',
            'body' => $data['body'] ?? '',
            'is_read' => $notification->read_at !== null,
            'read_at' => $notification->read_at?->toIso8601String(),
            'created_at' => $notification->created_at?->toIso8601String(),
            'action_url' => $data['action_url'] ?? null,
        ];
    }
}
