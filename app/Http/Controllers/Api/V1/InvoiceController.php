<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\InvoiceJob;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //
    public function send(Request $request)
    {
           $validate=$request->validate([
            'eamil'=>$request->email
           ]);

           InvoiceJob::dispatch($validate['email']);
           return response()->json(['message'=>"send pdf"]);
    }
}
