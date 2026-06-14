<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;

class SendInvocController extends Controller
{
    public function send(){
             $email = 'mariam@example.com';
    $invoiceData = [
        'id' => 7788,
        'total' => 1250
    ];
    SendEmailJob::dispatch($email, $invoiceData);

    return response()->json([
        'status' => 'Success',
        'message' => 'Job Sent To LOG'
    ]);
    }
}
