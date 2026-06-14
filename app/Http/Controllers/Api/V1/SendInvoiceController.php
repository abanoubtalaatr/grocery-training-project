<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\SendInvoiceJob;
use Illuminate\Http\Request;

class SendInvoiceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        SendInvoiceJob::dispatch($validated['email']);

        return response()->json([
            'message' => 'Invoice sent successfully',
        ], 200);
    }


}
