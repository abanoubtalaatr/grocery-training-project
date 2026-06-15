<?php

namespace App\Actions\Notification;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class BulkDeleteNotificationsAction
{
    use AsAction;

    /**
     * Delete multiple notifications for a user.
     * Can delete by a list of IDs, or by type (read, unread, all).
     */
    public function handle(User $user, ?array $ids = null, ?string $type = null): int
    {
        return DB::transaction(function () use ($user, $ids, $type) {
            if ($ids !== null) {
                return $user->notifications()
                    ->whereIn('id', $ids)
                    ->delete();
            }

            if ($type !== null) {
                return match ($type) {
                    'read' => $user->readNotifications()->delete(),
                    'unread' => $user->unreadNotifications()->delete(),
                    'all' => $user->notifications()->delete(),
                    default => 0,
                };
            }

            return 0;
        });
    }
}
