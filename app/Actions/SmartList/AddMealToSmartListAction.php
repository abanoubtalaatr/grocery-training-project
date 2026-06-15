<?php

namespace App\Actions\SmartList;

use App\Models\Meal;
use App\Models\SmartList;
use Lorisleiva\Actions\Concerns\AsAction;

class AddMealToSmartListAction
{
    use AsAction;

    /**
     * Add a meal to a smart list.
     */
    public function handle(SmartList $smartList, Meal $meal): SmartList
    {
        $smartList->meals()->syncWithoutDetaching([$meal->id]);
        return $smartList->load('meals');
    }
}
