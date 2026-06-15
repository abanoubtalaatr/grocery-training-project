<?php

namespace App\Actions\Api;

use App\Models\SmartList;
use App\Models\User;

class DeleteSmartListAction
{
    public function execute(User $user, string $id): void
    {
        $smartList = SmartList::where('user_id', $user->id)->findOrFail($id);
        $smartList->meals()->detach();
        $smartList->delete();
    }
}
