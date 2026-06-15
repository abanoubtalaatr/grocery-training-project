<?php

namespace App\Http\Controllers\Api;

use App\Actions\Invoice\CreateInvoiceAction;
use App\Http\Controllers\Controller;
use App\Jobs\GenerateInvoicePdfAndSendEmail;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //
      public function store(Request $request, CreateInvoiceAction $action)
    {
        $invoice = $action->execute($request->all());

        GenerateInvoicePdfAndSendEmail::dispatch($invoice->id);

        return response()->json([
            'message' => 'Invoice created',
            'invoice_id' => $invoice->id,
            'status' => 'processing'
        ]);
    }
}
