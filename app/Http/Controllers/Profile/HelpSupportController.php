<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportReportRequest;
use App\Models\SupportReport;
use Illuminate\Http\Request;

class HelpSupportController extends Controller
{
    /**
     * Display help & support page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $reports = SupportReport::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.help-support', compact('user', 'reports'));
    }

    /**
     * Store new support report.
     */
    public function store(SupportReportRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $orderNumber = $request->filled('order_number')
            ? trim((string) $request->input('order_number'))
            : null;

        SupportReport::create([
            'user_id' => $user->id,
            'issue_type' => trim((string) $request->input('issue_type')),
            'order_number' => $orderNumber,
            'message' => trim((string) $request->input('message')),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Support request submitted successfully. We will get in touch shortly.');
    }
}
