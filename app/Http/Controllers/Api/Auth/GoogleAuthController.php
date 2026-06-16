<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleLoginRequest;
use App\Actions\Auth\GoogleLoginAction;
use Illuminate\Http\JsonResponse;

class GoogleAuthController extends Controller
{
    public function login(GoogleLoginRequest $request, GoogleLoginAction $action): JsonResponse
    {
        $validated = $request->validated();
        
        $result = $action(
            $validated['id_token'],
            $validated['device_name'] ?? null
        );

        $response = [
            'success' => $result['success'],
            'message' => $result['message'],
        ];

        if (isset($result['data'])) {
            $response['data'] = $result['data'];
        }

        return response()->json($response, $result['status']);
    }
}
