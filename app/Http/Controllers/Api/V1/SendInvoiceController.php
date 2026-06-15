<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\SendInvoiceJob;
use Illuminate\Http\Request;

class SendInvoiceController extends Controller
{
    public function send(Request $request)
    {

        $request->validate([
            'email' => 'required | email'
        ]);

        SendInvoiceJob::dispatch($request->email);

        return response()->json(['message' => 'invoice sent successfully'], 200);
    }
}
