<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\SmartListCreateRequest;
use App\Services\SmartListService;
use Illuminate\Http\Request;

class SmartListsController extends Controller
{
    public function __construct(
        protected SmartListService $smartListService
    ) {}

    /**
     * Display smart lists page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $smartLists = $this->smartListService->getSmartLists($user);

        return view('dashboard.smart-lists', compact('user', 'smartLists'));
    }

    /**
     * Store new smart list.
     */
    public function store(SmartListCreateRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $this->smartListService->createSmartList(
            $user,
            $request->validated(),
            $request->file('image')
        );

        return redirect()->back()->with('success', 'Smart List created successfully.');
    }

    /**
     * Delete a smart list.
     */
    public function destroy(Request $request, $id)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $this->smartListService->deleteSmartList($user, (int) $id);

        return redirect()->back()->with('success', 'Smart List deleted successfully.');
    }
}
