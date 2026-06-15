<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Jobs\SendInvoiceEmail;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        $invoice = Invoice::create([
            'invoice_no' => 'INV-1001',
            'customer' => 'Hamaad',
            'amount' => 1500,
            'email' => $request->email,
        ]);

        SendInvoiceEmail::dispatch($invoice->id);

        return response()->json([
            'message' => 'Invoice created and email queued',
            'invoice_id' => $invoice->id,
        ]);
    }
}
