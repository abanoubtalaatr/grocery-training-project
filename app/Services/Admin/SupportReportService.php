<?php

namespace App\Services\Admin;

use App\Models\SupportReport;
use Illuminate\Http\Request;

class SupportReportService
{
    public function paginate(
        Request $request,
        int $perPage = 10
    )
    {
        return SupportReport::query()
            ->with('user')
            ->filter($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function markInProgress(
        SupportReport $report
    ): bool {
        return $report->update([
            'status' => 'in_progress',
        ]);
    }

    public function markResolved(
        SupportReport $report
    ): bool {
        return $report->update([
            'status' => 'resolved',
        ]);
    }

    public function delete(
        SupportReport $report
    ): bool {
        return $report->delete();
    }
}