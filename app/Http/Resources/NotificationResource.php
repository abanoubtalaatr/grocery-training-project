<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\DatabaseNotification;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        /** @var DatabaseNotification $notification */
        $notification = $this->resource;
        
        $data = $notification->data;
        if (is_string($data)) {
            $data = json_decode($data, true) ?? [];
        } elseif (!is_array($data)) {
            $data = [];
        }
        
        $type = $data['type'] ?? 'unknown';

        return [
            'id' => $notification->id,
            'type' => $type,
            'category' => $this->getCategory($type),
            'title' => $data['title'] ?? 'Notification',
            'body' => $data['body'] ?? '',
            'action_url' => $data['action_url'] ?? null,
            'action_label' => $data['action_label'] ?? 'View',
            'is_read' => !is_null($notification->read_at),
            'read_at' => $notification->read_at?->toISOString(),
            'created_at' => $notification->created_at?->toISOString(),
            'created_at_human' => $notification->created_at?->diffForHumans(),
            'icon' => $this->getIcon($type),
            'priority' => $data['priority'] ?? 'normal',
            
            // Detailed fields if loaded/requested
            $this->mergeWhen(isset($this->is_detailed) && $this->is_detailed, [
                'data' => $data,
                'channels' => $data['channels'] ?? ['database'],
                'metadata' => $data['metadata'] ?? [],
                'expires_at' => $data['expires_at'] ?? null,
            ]),

            // Dynamic resources
            'resources' => $this->when(isset($notification->resources), fn() => $notification->resources),
        ];
    }
    
    private function getCategory(string $type): string
    {
        $categories = [
            'order_confirmation' => 'Order & Delivery',
            'order_shipped' => 'Order & Delivery',
            'delivery_updates' => 'Order & Delivery',
            'out_of_stock_alerts' => 'Order & Delivery',
            'weekly_discounts' => 'Deals & Promotions',
            'exclusive_member_offers' => 'Deals & Promotions',
            'seasonal_campaigns' => 'Deals & Promotions',
            'cart_reminders' => 'Account & Reminders',
            'payment_billing' => 'Account & Reminders',
            'system' => 'System',
            'account' => 'System',
            'security' => 'System',
        ];
        
        return $categories[$type] ?? 'System';
    }
    
    private function getIcon(string $type): string
    {
        $icons = [
            'order_confirmation' => 'shopping-bag',
            'order_shipped' => 'truck',
            'delivery_updates' => 'package',
            'out_of_stock_alerts' => 'alert-triangle',
            'weekly_discounts' => 'percent',
            'exclusive_member_offers' => 'crown',
            'seasonal_campaigns' => 'gift',
            'cart_reminders' => 'shopping-cart',
            'payment_billing' => 'credit-card',
            'system' => 'bell',
            'account' => 'user',
            'security' => 'shield',
        ];
        
        return $icons[$type] ?? 'bell';
    }
}
