<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SendEmailForInvoice;
use App\Models\User;
class SendInvoiceController extends Controller
{
    public function sendInvoice()
    {
$user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'عفواً، يجب تسجيل الدخول أولاً للتعرف على الحساب! ❌'
            ], 401);
        }        SendEmailForInvoice::dispatch($user);

        return response()->json(['message' => 'Invoice is being sent.']);
    }
}
