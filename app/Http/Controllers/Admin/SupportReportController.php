<?php

namespace App\Http\Controllers\Admin;

use App\Models\SupportReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\SupportReportService;

class SupportReportController extends Controller
{
    public function __construct(
        private SupportReportService $supportReportService
    ) {}

    public function index(Request $request)
    {
        $reports = $this->supportReportService
            ->paginate($request);

        return view(
            'admin.support-reports.index',
            compact('reports')
        );
    }

    public function show(
        SupportReport $supportReport
    ) {
        $supportReport->load('user');

        return view(
            'admin.support-reports.show',
            compact('supportReport')
        );
    }

    public function markInProgress(
        SupportReport $supportReport
    ) {
        $this->supportReportService
            ->markInProgress($supportReport);

        return back();
    }

    public function markResolved(
        SupportReport $supportReport
    ) {
        $this->supportReportService
            ->markResolved($supportReport);

        return back();
    }

    public function destroy(
        SupportReport $supportReport
    ) {
        $this->supportReportService
            ->delete($supportReport);

        return back();
    }
}