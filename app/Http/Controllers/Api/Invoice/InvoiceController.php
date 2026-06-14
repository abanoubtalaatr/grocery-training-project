<?php

namespace App\Http\Controllers\Api\Invoice;

use App\Http\Controllers\Controller;
use App\Jobs\SendInvoiceJob;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function send(Request $request)
    {
        SendInvoiceJob::dispatch(
            $request->email,
            $request->customer_name,
            $request->amount
        );

        return response()->json([
            'message' => 'Invoice queued successfully'
        ]);
    }
}