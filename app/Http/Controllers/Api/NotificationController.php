<?php

namespace App\Http\Controllers\Api;

use App\Actions\Notification\BulkDeleteNotificationsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClearNotificationsRequest;
use App\Http\Requests\DestroyMultipleNotificationsRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Meal;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Get all notifications for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = max(1, min(100, (int) $request->get('per_page', 15)));

        $notifications = $this->buildNotificationsQuery($request)->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => [
                'notifications' => NotificationResource::collection($notifications->items()),
                'unread_count' => $user->unreadNotifications()->count(),
                'total_count' => $user->notifications()->count(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ],
            ],
        ]);
    }

    /**
     * Same as index but attaches related models (meal, order) when referenced in notification data.
     */
    public function indexWithResources(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = max(1, min(100, (int) $request->get('per_page', 15)));

        $notifications = $this->buildNotificationsQuery($request)->paginate($perPage);
        $pageItems = $notifications->getCollection();

        $mealIds = [];
        $orderIds = [];
        foreach ($pageItems as $n) {
            $d = $this->notificationDataAsArray($n->data);
            if (! empty($d['meal_id']) && is_numeric($d['meal_id'])) {
                $mealIds[] = (int) $d['meal_id'];
            }
            if (! empty($d['order_id']) && is_numeric($d['order_id'])) {
                $orderIds[] = (int) $d['order_id'];
            }
        }
        $mealIds = array_values(array_unique($mealIds));
        $orderIds = array_values(array_unique($orderIds));

        $meals = $mealIds === []
            ? collect()
            : Meal::query()->with('category')->whereIn('id', $mealIds)->get()->keyBy('id');
        $orders = $orderIds === []
            ? collect()
            : Order::query()->whereIn('id', $orderIds)->get()->keyBy('id');

        $transformed = $pageItems->map(function (DatabaseNotification $notification) use ($meals, $orders) {
            $d = $this->notificationDataAsArray($notification->data);
            $resources = [];

            if (! empty($d['meal_id']) && is_numeric($d['meal_id'])) {
                $meal = $meals->get((int) $d['meal_id']);
                if ($meal) {
                    $resources['meal'] = [
                        'id' => $meal->id,
                        'title' => $meal->title,
                        'slug' => $meal->slug,
                        'image_url' => $meal->image_url,
                        ...$meal->getApiPriceAttributes(),
                        'has_offer' => $meal->hasOffer(),
                        'category' => $meal->category ? [
                            'id' => $meal->category->id,
                            'name' => $meal->category->name,
                        ] : null,
                    ];
                }
            }

            if (! empty($d['order_id']) && is_numeric($d['order_id'])) {
                $order = $orders->get((int) $d['order_id']);
                if ($order) {
                    $resources['order'] = [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'status' => $order->status,
                        'total' => (string) $order->total,
                        'placed_at' => $order->placed_at?->toIso8601String(),
                        'created_at' => $order->created_at?->toIso8601String(),
                    ];
                }
            }

            $notification->resources = $resources;
            return $notification;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'notifications' => NotificationResource::collection($transformed),
                'unread_count' => $user->unreadNotifications()->count(),
                'total_count' => $user->notifications()->count(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ],
            ],
        ]);
    }

    /** Apply list filters to the authenticated user's notifications query. */
    private function buildNotificationsQuery(Request $request)
    {
        $user = $request->user();
        $query = $user->notifications();

        if ($request->has('read')) {
            $isRead = filter_var($request->read, FILTER_VALIDATE_BOOLEAN);
            $query = $isRead ? $query->read() : $query->unread();
        }

        if ($request->has('type')) {
            $query->where('data->type', $request->type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('data->title', 'like', "%{$search}%")
                    ->orWhere('data->body', 'like', "%{$search}%");
            });
        }

        $allowedOrderBy = ['created_at', 'read_at'];
        $orderBy = in_array((string) $request->get('order_by', 'created_at'), $allowedOrderBy, true)
            ? (string) $request->get('order_by', 'created_at')
            : 'created_at';
        $orderDirection = strtolower((string) $request->get('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $query->orderBy($orderBy, $orderDirection);

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    private function notificationDataAsArray(mixed $data): array
    {
        if (is_array($data)) {
            return $data;
        }

        if (is_string($data) && $data !== '') {
            $decoded = json_decode($data, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * Get notification statistics
     */
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();

        $allNotifications = $user->notifications();
        $unreadNotifications = $user->unreadNotifications();

        $total = $allNotifications->count();
        $unread = $unreadNotifications->count();

        // Count by type (in-memory; JSON path must not use dot form on the query builder)
        $typeCounts = $allNotifications->get()
            ->groupBy(function (DatabaseNotification $n) {
                $data = $this->notificationDataAsArray($n->data);

                return $data['type'] ?? 'unknown';
            })
            ->map(function ($notifications) {
                return [
                    'total' => $notifications->count(),
                    'unread' => $notifications->whereNull('read_at')->count(),
                ];
            });

        $recentTypes = $allNotifications->latest()
            ->take(5)
            ->get()
            ->map(function (DatabaseNotification $n) {
                $data = $this->notificationDataAsArray($n->data);

                return $data['type'] ?? null;
            })
            ->filter()
            ->unique()
            ->values();

        $last = $allNotifications->latest()->first();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'unread' => $unread,
                'read' => max(0, $total - $unread),
                'by_type' => $typeCounts,
                'recent_types' => $recentTypes,
                'last_notification_at' => $last?->created_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Get a single notification
     */
    public function show(Request $request, DatabaseNotification $notification): JsonResponse
    {
        $this->authorizeOwner($request, $notification);

        // Mark as read when viewing
        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        $notification->is_detailed = true;

        return response()->json([
            'success' => true,
            'data' => new NotificationResource($notification),
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, DatabaseNotification $notification): JsonResponse
    {
        $this->authorizeOwner($request, $notification);

        if (! $notification->read_at) {
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'data' => new NotificationResource($notification),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification is already read',
        ], 400);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(Request $request, DatabaseNotification $notification): JsonResponse
    {
        $this->authorizeOwner($request, $notification);

        if ($notification->read_at) {
            $notification->markAsUnread();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as unread',
                'data' => new NotificationResource($notification),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification is already unread',
        ], 400);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        $unreadCount = $user->unreadNotifications()->count();

        if ($unreadCount > 0) {
            $user->unreadNotifications()->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => "{$unreadCount} notifications marked as read",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No unread notifications',
        ], 400);
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, DatabaseNotification $notification): JsonResponse
    {
        $this->authorizeOwner($request, $notification);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully',
        ]);
    }

    /**
     * Delete multiple notifications
     */
    public function destroyMultiple(DestroyMultipleNotificationsRequest $request): JsonResponse
    {
        $deletedCount = BulkDeleteNotificationsAction::run($request->user(), $request->ids);

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} notifications deleted successfully",
        ]);
    }

    /**
     * Clear all notifications
     */
    public function clearAll(ClearNotificationsRequest $request): JsonResponse
    {
        $type = $request->get('type', 'all');
        $deletedCount = BulkDeleteNotificationsAction::run($request->user(), null, $type);

        $message = match ($type) {
            'read' => "{$deletedCount} read notifications cleared",
            'unread' => "{$deletedCount} unread notifications cleared",
            default => "All {$deletedCount} notifications cleared",
        };

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Get notifications by type
     */
    public function byType(Request $request, string $type): JsonResponse
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->where('data->type', $type)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'type' => $type,
                'notifications' => NotificationResource::collection($notifications->items()),
                'total' => $notifications->total(),
                'unread' => $notifications->whereNull('read_at')->count(),
            ],
        ]);
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();
        $count = $user->unreadNotifications()->count();

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $count,
                'has_unread' => $count > 0,
            ],
        ]);
    }

    /**
     * Get recent notifications (last 24 hours)
     */
    public function recent(Request $request): JsonResponse
    {
        $user = $request->user();

        $recentNotifications = $user->notifications()
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'notifications' => NotificationResource::collection($recentNotifications),
                'total_recent' => $recentNotifications->count(),
                'unread_recent' => $recentNotifications->whereNull('read_at')->count(),
            ],
        ]);
    }

    /**
     * Authorize that the user owns the notification.
     */
    private function authorizeOwner(Request $request, DatabaseNotification $notification): void
    {
        if ($notification->notifiable_id !== $request->user()->id || $notification->notifiable_type !== get_class($request->user())) {
            abort(404, 'Notification not found');
        }
    }
}

