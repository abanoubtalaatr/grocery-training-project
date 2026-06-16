<?php

namespace App\Actions\Notification;

use App\Models\User;
use App\Repositories\NotificationRepository;
use App\Traits\FormatsNotification;
use Illuminate\Http\Request;

class GetNotificationsAction
{
    use FormatsNotification;

    public function __construct(private readonly NotificationRepository $notificationRepository) {}

    public function __invoke(User $user, Request $request): array
    {
        $perPage = max(1, min(100, (int) $request->get('per_page', 15)));

        $query = $this->notificationRepository->getBaseQuery($user);

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

        $notifications = $query->paginate($perPage);
        
        $transformed = $notifications->getCollection()->map(fn ($n) => $this->transformNotification($n))->values();
        $notifications->setCollection($transformed);

        return [
            'notifications' => $notifications->items(),
            'unread_count' => $user->unreadNotifications()->count(),
            'total_count' => $user->notifications()->count(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
        ];
    }
}
