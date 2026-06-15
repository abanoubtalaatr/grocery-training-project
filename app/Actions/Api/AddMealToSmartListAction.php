<?php

namespace App\Actions\Api;

use App\Models\SmartList;
use App\Models\User;

class AddMealToSmartListAction
{
    public function execute(User $user, string $id, string|int $mealId): SmartList
    {
        $smartList = SmartList::where('user_id', $user->id)->findOrFail($id);
        $smartList->meals()->syncWithoutDetaching([$mealId]);

        return $smartList->load('meals');
    }
}
