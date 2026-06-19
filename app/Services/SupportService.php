<?php

namespace App\Services;

use App\Models\SupportReport;
use App\Models\User;
use Illuminate\Support\Collection;

class SupportService
{
    /**
     * Get user's support reports.
     */
    public function getSupportReports(User $user): Collection
    {
        return SupportReport::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new support report ticket.
     */
    public function createSupportReport(User $user, array $data, string $ipAddress, ?string $userAgent): SupportReport
    {
        $orderNumber = !empty($data['order_number'])
            ? trim((string) $data['order_number'])
            : null;

        return SupportReport::create([
            'user_id' => $user->id,
            'issue_type' => trim((string) $data['issue_type']),
            'order_number' => $orderNumber,
            'message' => trim((string) $data['message']),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
