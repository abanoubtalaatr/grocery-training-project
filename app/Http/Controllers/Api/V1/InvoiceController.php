<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\InvoiceRequest;
use App\Jobs\SendInvoiceJob;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function send(InvoiceRequest $request)
    {

        SendInvoiceJob::dispatch($request->email, $request->name);
        return response()->json([
            'success' => true,
            'message' => 'Invoice job has been queued successfully.',
        ], 202);
    }
}
