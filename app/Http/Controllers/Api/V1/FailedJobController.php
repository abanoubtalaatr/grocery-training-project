<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FailedJobController extends Controller
{
    public function index()
    {
        
        $failedJobs = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->get();

        return view('failed_jobs.index', compact('failedJobs'));
    }
}
