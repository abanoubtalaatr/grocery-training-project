<?php

namespace App\Http\Controllers;

use App\Actions\Invoice\SendInvoiceAction;
use Illuminate\Http\JsonResponse;

class SendInvoiceController extends Controller
{
    public function sendInvoice(SendInvoiceAction $action): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'عفواً، يجب تسجيل الدخول أولاً للتعرف على الحساب! ❌'
            ], 401);
        }        
        
        $action($user);

        return response()->json(['message' => 'Invoice is being sent.']);
    }
}
