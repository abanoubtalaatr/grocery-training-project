<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\SmartListCreateRequest;
use App\Models\SmartList;
use Illuminate\Http\Request;

class SmartListsController extends Controller
{
    /**
     * Display smart lists page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $smartLists = SmartList::where('user_id', $user->id)
            ->with('meals')
            ->get();
            
        return view('dashboard.smart-lists', compact('user', 'smartLists'));
    }

    /**
     * Store new smart list.
     */
    public function store(SmartListCreateRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['description'] = $data['description'] ?? '';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/smart-lists'), $imageName);
            $data['image'] = $imageName;
        }

        SmartList::create($data);

        return redirect()->back()->with('success', 'Smart List created successfully.');
    }

    /**
     * Delete a smart list.
     */
    public function destroy(Request $request, $id)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $smartList = SmartList::where('user_id', $user->id)->findOrFail($id);
        $smartList->meals()->detach();
        $smartList->delete();

        return redirect()->back()->with('success', 'Smart List deleted successfully.');
    }
}
