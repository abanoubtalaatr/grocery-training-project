<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FailedJobController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('failed_jobs')
            ->orderByDesc('failed_at');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('uuid', 'like', "%{$search}%")
                    ->orWhere('queue', 'like', "%{$search}%")
                    ->orWhere('exception', 'like', "%{$search}%");
            });
        }

        $failedJobs = $query->paginate(15)->withQueryString();

        return view('failed-jobs.index', compact('failedJobs'));
    }

    public function show($id)
    {
        $failedJob = DB::table('failed_jobs')
            ->where('id', $id)
            ->first();

        if (! $failedJob) {
            abort(404);
        }

        return view('failed-jobs.show', compact('failedJob'));
    }
}
