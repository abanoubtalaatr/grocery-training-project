<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\InvoiceJob;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //
    public function send1(Request $request)
    {
           $validate=$request->validate([
            'email'=>'required'
           ]);

           InvoiceJob::dispatch($validate['email']);
           return response()->json(['message'=>"send pdf"]);
    }
}
