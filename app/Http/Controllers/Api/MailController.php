<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MailController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        SendMailJob::dispatch(
            email: $validated['email'],
            subject: $validated['subject'],
            body: $validated['body'],
        );

        return response()->json([
            'success' => true,
            'message' => 'Mail added to queue successfully',
        ]);
    }

    public function sendWithUpload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'invoice' => 'required|file|mimes:pdf|max:10240',
        ]);

        $invoice = $request->file('invoice');
        $path = $invoice->store('invoices', 'local');

        SendMailJob::dispatch(
            email: $validated['email'],
            subject: $validated['subject'],
            body: $validated['body'],
            invoicePath: Storage::disk('local')->path($path),
            invoiceName: $invoice->getClientOriginalName() ?: 'invoice.pdf',
        );

        return response()->json([
            'success' => true,
            'message' => 'Mail with invoice added to queue successfully',
            'data' => [
                'invoice_path' => $path,
            ],
        ]);
    }

    public function sendWithPath(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'invoice_path' => 'required|string',
            'invoice_name' => 'nullable|string|max:255',
        ]);

        $invoicePath = ltrim($validated['invoice_path'], '/\\');

        if (! Storage::disk('local')->exists($invoicePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice file not found',
            ], 404);
        }

        SendMailJob::dispatch(
            email: $validated['email'],
            subject: $validated['subject'],
            body: $validated['body'],
            invoicePath: Storage::disk('local')->path($invoicePath),
            invoiceName: $validated['invoice_name'] ?? basename($invoicePath),
        );

        return response()->json([
            'success' => true,
            'message' => 'Mail with invoice path added to queue successfully',
        ]);
    }
}
