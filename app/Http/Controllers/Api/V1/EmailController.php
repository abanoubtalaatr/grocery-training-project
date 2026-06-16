<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\PDFMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail()
    {
        Mail::to('your-email@gmail.com')->queue(new PDFMail());

        return response()->json(['message' => 'Email sent successfully']);
    }
}
