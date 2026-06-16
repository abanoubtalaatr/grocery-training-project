<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\{AuthService, UserAppSettingsService};
use App\Traits\ApiResponse;
use Illuminate\Http\{JsonResponse, Request};
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
        return response()->streamDownload(
            fn() => print json_encode($this->settingsService->buildDataExport($request->user()), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'grocery-user-data-' . $request->user()->id . '-' . now()->format('Y-m-d') . '.json',
            ['Content-Type' => 'application/json']
        );
    }

    public function delete(Request $request): JsonResponse
    {
        return $this->successResponse($this->authService->deleteAccount($request->user()), 'Account deleted successfully');
    }
}