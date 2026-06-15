<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = $this->resource;

        $addresses = $user->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        $allOrders = \App\Models\Order::where('user_id', $user->id)->with(['items.meal.category', 'items.meal.subcategory', 'address'])->orderBy('created_at', 'desc')->get();

        $orderHistory = OrderSummaryResource::collection($allOrders);
        $inProgressWithTracking = OrderWithTrackingResource::collection($allOrders->whereNotIn('status', ['cancelled', 'delivered']));

        $orderNotifications = NotificationResource::collection($user->notifications()->where(function ($q) {
            $q->where('data->type', 'order_confirmation')->orWhere('data->type', 'order_shipped')->orWhere('data->type', 'delivery_updates');
        })->orderBy('created_at', 'desc')->take(20)->get());

        $sessions = SessionResource::collection($user->tokens()->get());
        $wishlist = WishlistItemResource::collection($user->favorites);

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
                'ordered_at' => $orderHistory->collection->map(fn ($o) => $o['placed_at'] ?? $o['created_at'])->values(),
            ],
            'in_progress_orders' => $inProgressWithTracking,
            'order_notifications' => $orderNotifications,
            'settings' => ['privacy_and_security' => ['active_sessions' => $sessions, 'change_password' => ['available' => true], 'change_username' => ['available' => true]]],
            'wishlist' => $wishlist,
        ];
    }
}
