<?php

namespace App\Http\Controllers\Api\Jobs;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FailedJobController extends Controller
{
    public function index()
    {
        $failedJobs = DB::table('failed_jobs')
            ->latest('failed_at')
            ->paginate(10);

        return view('failed-jobs.index', compact('failedJobs'));
    }

    public function show(string $id)
    {
        $job = DB::table('failed_jobs')->find($id);

        abort_if(!$job, 404);

        return view('failed-jobs.show', compact('job'));
    }
}