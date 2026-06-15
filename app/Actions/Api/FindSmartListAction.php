<?php

namespace App\Actions\Api;

use App\Models\SmartList;
use App\Models\User;

class FindSmartListAction
{
    public function execute(User $user, string $id): SmartList
    {
        return SmartList::where('user_id', $user->id)->with('meals')->findOrFail($id);
    }
}
