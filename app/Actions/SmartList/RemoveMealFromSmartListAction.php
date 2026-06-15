<?php

namespace App\Actions\SmartList;

use App\Models\Meal;
use App\Models\SmartList;
use Lorisleiva\Actions\Concerns\AsAction;

class RemoveMealFromSmartListAction
{
    use AsAction;

    /**
     * Remove a meal from a smart list.
     */
    public function handle(SmartList $smartList, Meal $meal): SmartList
    {
        $smartList->meals()->detach($meal->id);
        return $smartList->load('meals');
    }
}
