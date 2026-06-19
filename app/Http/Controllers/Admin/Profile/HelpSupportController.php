<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportReportRequest;
use App\Services\SupportService;
use Illuminate\Http\Request;

class HelpSupportController extends Controller
{
    public function __construct(
        protected SupportService $supportService
    ) {}

    /**
     * Display help & support page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $reports = $this->supportService->getSupportReports($user);

        return view('dashboard.help-support', compact('user', 'reports'));
    }

    /**
     * Store new support report.
     */
    public function store(SupportReportRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $this->supportService->createSupportReport(
            $user,
            $request->validated(),
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->back()->with('success', 'Support request submitted successfully. We will get in touch shortly.');
    }
}
