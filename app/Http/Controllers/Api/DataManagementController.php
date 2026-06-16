<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\UserAppSettingsService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataManagementController extends Controller
{
    use ApiResponse;
    
    public function __construct(
        private readonly UserAppSettingsService $settingsService,
        private readonly AuthService $authService,
    ) {}

    public function download(Request $request): StreamedResponse
    {
        $user = $request->user();
        $payload = $this->settingsService->buildDataExport($user);
        $filename = 'grocery-user-data-'.$user->id.'-'.now()->format('Y-m-d').'.json';

        return $this->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $this->authService->deleteUserAccount($request->user());

        return $this->successResponse(
            'Account deleted successfully',
            
        );
    }
}
