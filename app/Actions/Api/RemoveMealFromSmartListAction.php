<?php

namespace App\Actions\Api;

use App\Models\SmartList;
use App\Models\User;

class RemoveMealFromSmartListAction
{
    public function execute(User $user, string $id, string $mealId): SmartList
    {
        $smartList = SmartList::where('user_id', $user->id)->findOrFail($id);
        $smartList->meals()->detach($mealId);

        return $smartList->load('meals');
    }
}
