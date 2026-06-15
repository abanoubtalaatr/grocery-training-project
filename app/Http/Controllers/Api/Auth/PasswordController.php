<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    use ApiResponse;

    public function update(ChangePasswordRequest $request): JsonResponse
    {
        $request->user()->update([
            'password' => $request->input('password'),
        ]);

        return self::successResponse('Password changed successfully');
    }
}
