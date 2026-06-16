<?php

namespace App\Services;

use App\Models\SmartList;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SmartListService
{
    public function getSmartLists(User $user): Collection
    {
        return SmartList::where('user_id', $user->id)->with('meals')->get();
    }

    public function createSmartList(User $user, array $data): SmartList
    {
        $data['user_id'] = $user->id;
        $data['description'] = $data['description'] ?? '';
        $mealIds = $data['meal_ids'] ?? [];
        unset($data['meal_ids']);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $imageName = time() . '.' . $data['image']->getClientOriginalExtension();
            $data['image']->move(public_path('images/smart-lists'), $imageName);
            $data['image'] = $imageName;
        }

        $smartList = SmartList::create($data);
        if (!empty($mealIds)) {
            $smartList->meals()->attach($mealIds);
        }

        return $smartList->load('meals');
    }

    public function updateSmartList(SmartList $smartList, array $data): SmartList
    {
        if (array_key_exists('description', $data) && $data['description'] === null) {
            $data['description'] = '';
        }
        $mealIds = $data['meal_ids'] ?? null;
        unset($data['meal_ids']);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $imageName = time() . '.' . $data['image']->getClientOriginalExtension();
            $data['image']->move(public_path('images/smart-lists'), $imageName);
            $data['image'] = $imageName;
        }

        $smartList->update($data);
        if ($mealIds !== null) {
            $smartList->meals()->sync($mealIds);
        }

        return $smartList->load('meals');
    }

    public function deleteSmartList(SmartList $smartList): bool
    {
        $smartList->meals()->detach();
        return $smartList->delete();
    }

    public function addMeal(SmartList $smartList, int $mealId): SmartList
    {
        $smartList->meals()->syncWithoutDetaching([$mealId]);
        return $smartList->load('meals');
    }

    public function removeMeal(SmartList $smartList, int $mealId): SmartList
    {
        $smartList->meals()->detach($mealId);
        return $smartList->load('meals');
    }
}