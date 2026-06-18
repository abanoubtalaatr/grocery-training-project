<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SupportReportStatusRequest;
use App\Models\SupportReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportReportController extends Controller
{
    public function index(Request $request): View
    {
        $reports = SupportReport::query()
            ->with('user')
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('order_number', 'like', "%{$search}%")->orWhere('issue_type', 'like', "%{$search}%"))
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.support-reports.index', [
            'reports' => $reports,
            'statuses' => $this->statuses(),
        ]);
    }

    public function show(SupportReport $supportReport): View
    {
        $supportReport->load('user');

        if ($supportReport->status === 'new') {
            $supportReport->update(['status' => 'read']);
        }

        return view('admin.support-reports.show', [
            'report' => $supportReport,
            'statuses' => $this->statuses(),
        ]);
    }

    public function updateStatus(SupportReportStatusRequest $request, SupportReport $supportReport): RedirectResponse
    {
        $supportReport->update($request->validated());

        return back()->with('success', 'Report status updated.');
    }

    public function destroy(SupportReport $supportReport): RedirectResponse
    {
        $supportReport->delete();

        return redirect()->route('admin.support-reports.index')->with('success', 'Report deleted successfully.');
    }

    /**
     * @return array<string, string>
     */
    private function statuses(): array
    {
        return [
            'new' => 'New',
            'read' => 'Read',
            'resolved' => 'Resolved',
        ];
    }
}
