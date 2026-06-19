<?php

namespace App\Services;

use App\Models\SmartList;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SmartListService
{
    /**
     * Get user's smart lists with meals.
     */
    public function getSmartLists(User $user): Collection
    {
        return SmartList::where('user_id', $user->id)
            ->with('meals')
            ->get();
    }

    /**
     * Create a new smart list for a user.
     */
    public function createSmartList(User $user, array $data, $imageFile = null): SmartList
    {
        $data['user_id'] = $user->id;
        $data['description'] = $data['description'] ?? '';

        if ($imageFile) {
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('images/smart-lists'), $imageName);
            $data['image'] = $imageName;
        }

        return SmartList::create($data);
    }

    /**
     * Delete a user's smart list.
     */
    public function deleteSmartList(User $user, int $id): void
    {
        DB::transaction(function () use ($user, $id) {
            $smartList = SmartList::where('user_id', $user->id)->findOrFail($id);
            $smartList->meals()->detach();
            $smartList->delete();
        });
    }
}
