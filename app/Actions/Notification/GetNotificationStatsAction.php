<?php

namespace App\Actions\Notification;

use App\Models\User;
use App\Traits\FormatsNotification;
use Illuminate\Notifications\DatabaseNotification;

class GetNotificationStatsAction
{
    use FormatsNotification;

    public function __invoke(User $user): array
    {
        $allNotifications = $user->notifications();
        $unreadNotifications = $user->unreadNotifications();

        $total = $allNotifications->count();
        $unread = $unreadNotifications->count();

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

        return [
            'total' => $total,
            'unread' => $unread,
            'read' => max(0, $total - $unread),
            'by_type' => $typeCounts,
            'recent_types' => $recentTypes,
            'last_notification_at' => $last?->created_at?->toIso8601String(),
        ];
    }
}
