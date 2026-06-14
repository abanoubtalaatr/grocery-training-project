<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateInvoicePdfJob;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'name'  => ['required', 'string'],
        ]);

        GenerateInvoicePdfJob::dispatch(
            $validated['email'],
            $validated['name']
        );

        return response()->json([
            'success' => true,
            'message' => 'Invoice job queued successfully.'
        ], 202);
    }
}