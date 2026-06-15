<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;

        $addresses = $user->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $allOrders = Order::where('user_id', $user->id)
            ->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();

        $orderHistory = $allOrders->map(fn (Order $o) => [
            'id' => $o->id,
            'order_number' => $o->order_number,
            'status' => $o->status,
            'status_description' => $o->status_description,
            'total' => (float) $o->total,
            'placed_at' => $o->placed_at?->toIso8601String(),
            'created_at' => $o->created_at?->toIso8601String(),
            'item_count' => $o->items->count(),
        ]);

        $inProgressWithTracking = $allOrders->whereNotIn('status', ['cancelled', 'delivered'])->map(function (Order $o) {
            $trackingStage = match ($o->status) {
                'shipping' => 'arriving',
                'out_for_delivery' => 'out_for_delivery',
                'delivered' => 'delivered',
                default => 'processing',
            };

            return [
                'id' => $o->id,
                'order_number' => $o->order_number,
                'status' => $o->status,
                'status_description' => $o->status_description,
                'tracking' => [
                    'stage' => $trackingStage,
                    'stage_label' => match ($trackingStage) {
                        'arriving' => 'Arriving',
                        'out_for_delivery' => 'Out for delivery',
                        'delivered' => 'Delivered',
                        default => 'Processing',
                    },
                    'positions' => [
                        ['stage' => 'arriving', 'label' => 'Arriving', 'completed' => in_array($o->status, ['shipping', 'out_for_delivery', 'delivered']), 'timestamp' => $o->shipping_at?->toIso8601String()],
                        ['stage' => 'out_for_delivery', 'label' => 'Out for delivery', 'completed' => in_array($o->status, ['out_for_delivery', 'delivered']), 'timestamp' => $o->out_for_delivery_at?->toIso8601String()],
                        ['stage' => 'delivered', 'label' => 'Delivered', 'completed' => $o->status === 'delivered', 'timestamp' => $o->delivered_at?->toIso8601String()],
                    ],
                ],
                'total' => (float) $o->total,
                'placed_at' => $o->placed_at?->toIso8601String(),
                'estimated_delivery_time' => $o->estimated_delivery_time?->toIso8601String(),
                'address' => $o->address ? [
                    'id' => $o->address->id,
                    'label' => $o->address->label,
                    'full_name' => $o->address->full_name,
                    'phone' => $o->address->phone,
                    'country_code' => $o->address->country_code,
                    'street_address' => $o->address->street_address,
                    'building_number' => $o->address->building_number,
                    'floor' => $o->address->floor,
                    'apartment' => $o->address->apartment,
                    'landmark' => $o->address->landmark,
                    'city' => $o->address->city,
                    'state' => $o->address->state,
                    'postal_code' => $o->address->postal_code,
                    'country' => $o->address->country,
                    'full_address' => $o->address->full_address ?? null,
                    'is_default' => $o->address->is_default,
                    'created_at' => $o->address->created_at,
                    'updated_at' => $o->address->updated_at,
                ] : null,
                'items' => $o->items->map(fn ($item) => [
                    'id' => $item->id,
                    'meal' => ['id' => $item->meal->id, 'title' => $item->meal->title, 'image_url' => $item->meal->image_url],
                    'quantity' => $item->quantity,
                    'subtotal' => (float) $item->subtotal,
                ])->values(),
            ];
        })->values();

        $orderNotifications = $user->notifications()
            ->where(function ($q) {
                $q->where('data->type', 'order_confirmation')
                    ->orWhere('data->type', 'order_shipped')
                    ->orWhere('data->type', 'delivery_updates');
            })
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function ($n) {
                $data = $n->data ?? [];
                return [
                    'id' => $n->id,
                    'type' => $data['type'] ?? 'order',
                    'title' => $data['title'] ?? 'Order update',
                    'body' => $data['body'] ?? '',
                    'is_read' => $n->read_at !== null,
                    'read_at' => $n->read_at?->toIso8601String(),
                    'created_at' => $n->created_at?->toIso8601String(),
                    'action_url' => $data['action_url'] ?? null,
                ];
            });

        $currentTokenId = $user->currentAccessToken()?->id;
        $sessions = $user->tokens()->get()->map(function ($token) use ($currentTokenId) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at?->toIso8601String(),
                'is_current' => (string) $token->id === (string) $currentTokenId,
            ];
        })->all();

        $wishlist = $user->favorites->map(function ($f) {
            $meal = $f->meal;
            return [
                'id' => $meal->id,
                'title' => $meal->title,
                'slug' => $meal->slug,
                'image_url' => $meal->image_url,
                ...($meal->getApiPriceAttributes() ?? []),
                'has_offer' => $meal->hasOffer(),
                'category' => $meal->category ? ['id' => $meal->category->id, 'name' => $meal->category->name] : null,
                'is_favorited' => true,
                'favorited_at' => $f->created_at?->toIso8601String(),
            ];
        })->values();

        return [
            'me' => [
                'id' => $user->id,
                'profile_picture' => $user->profile_image_url,
                'name' => $user->full_name,
                'username' => $user->username,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'gender' => $user->gender,
                'birthday' => $user->birthday?->format('Y-m-d'),
                'email' => $user->email,
                'phone' => $user->phone,
                'country_code' => $user->country_code,
                'email_verified' => $user->email_verified,
                'phone_verified' => $user->phone_verified,
                'preferred_languages' => $user->preferred_languages ?? [],
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'addresses' => AddressResource::collection($addresses),
            'order_history' => [
                'orders' => $orderHistory,
                'ordered_at' => $orderHistory->map(fn ($o) => $o['placed_at'] ?? $o['created_at'])->values(),
            ],
            'in_progress_orders' => $inProgressWithTracking,
            'order_notifications' => $orderNotifications,
            'settings' => [
                'privacy_and_security' => [
                    'active_sessions' => $sessions,
                    'change_password' => ['available' => true],
                    'change_username' => ['available' => true],
                ],
            ],
            'wishlist' => $wishlist,
        ];
    }
}
