<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\SendInvoiceMailJob;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function send()
{
    $email = 'maha@example.com';

    $invoiceData = [
        'id' => 7788,
        'total' => 1250
    ];

    SendInvoiceMailJob::dispatch($email, $invoiceData);

    return response()->json([
        'status' => true,
        'message' => 'Invoice queued successfully'
    ]);
}
}