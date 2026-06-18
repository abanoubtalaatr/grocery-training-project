<?php

namespace App\Services\Admin;

use App\Models\SmartList;

class SmartListService
{
    public function all()
    {
        return SmartList::with('meals')->get();
    }

    public function get(string $id): SmartList
    {
        return SmartList::with('meals')->findOrFail($id);
    }

   


public function create(array $data): SmartList
{
    $mealIds = $data['meal_ids'] ?? [];
    unset($data['meal_ids']);

    if (array_key_exists('description', $data) && $data['description'] === null) {
        $data['description'] = '';
    }

    if (isset($data['image']) && is_object($data['image'])) {
        $image = $data['image'];
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/smart-lists'), $imageName);
        $data['image'] = $imageName;
    }

 
    $data['user_id'] = auth()->id();

    $smartList = SmartList::create($data);

    if (!empty($mealIds)) {
        $smartList->meals()->attach($mealIds);
    }

    return $smartList->load('meals');
}







    public function update(SmartList $smartList, array $data): SmartList
    {
        $mealIds = $data['meal_ids'] ?? null;
        unset($data['meal_ids']);

        if (array_key_exists('description', $data) && $data['description'] === null) {
            $data['description'] = '';
        }

        if (isset($data['image']) && is_object($data['image'])) {
            $image = $data['image'];
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/smart-lists'), $imageName);
            $data['image'] = $imageName;
        }

        $smartList->update($data);
        if ($mealIds !== null) {
            $smartList->meals()->sync($mealIds);
        }

        return $smartList->load('meals');
    }

    public function delete(SmartList $smartList): void
    {
        $smartList->meals()->detach();
        $smartList->delete();
    }
}
