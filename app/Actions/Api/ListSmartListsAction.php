<?php

namespace App\Actions\Api;

use App\Models\SmartList;
use App\Models\User;
use Illuminate\Support\Collection;

class ListSmartListsAction
{
    public function execute(User $user): Collection
    {
        return SmartList::where('user_id', $user->id)->with('meals')->get();
    }
}
