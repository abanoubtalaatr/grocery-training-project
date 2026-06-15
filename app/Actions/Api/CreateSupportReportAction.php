<?php

namespace App\Actions\Api;

use App\Models\SupportReport;
use App\Models\User;

class CreateSupportReportAction
{
    /**
     * @param  array{issue_type: string, order_number: string|null, message: string}  $data
     */
    public function execute(User $user, array $data, ?string $ipAddress, ?string $userAgent): SupportReport
    {
        return SupportReport::create([
            'user_id' => $user->id,
            'issue_type' => $data['issue_type'],
            'order_number' => $data['order_number'],
            'message' => $data['message'],
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
