<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendInvoiceMailJob;

class MailController extends Controller
{
    public function send()
    {
        SendInvoiceMailJob::dispatch();

        return response()->json([
            'success' => true,
            'message' => 'Email queued successfully'
        ]);
    }
}