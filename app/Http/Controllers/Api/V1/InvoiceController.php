<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\SendInvoiceEmailJob;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        SendInvoiceEmailJob::dispatch($validated['email']);

        return response()->json([
            'success' => true,
            'message' => 'Invoice queued successfully',
        ], 202);
    }
}