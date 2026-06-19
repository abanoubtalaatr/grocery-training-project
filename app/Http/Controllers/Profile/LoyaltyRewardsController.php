<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyRewardsController extends Controller
{
    public function __construct(
        private readonly LoyaltyService $loyaltyService,
    ) {}

    /**
     * Display loyalty & rewards page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $loyaltyData = $this->loyaltyService->buildSummary($user);

        return view('dashboard.loyalty-rewards', compact('user', 'loyaltyData'));
    }
}
