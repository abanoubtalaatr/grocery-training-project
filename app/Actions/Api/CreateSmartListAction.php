<?php

namespace App\Actions\Api;

use App\Models\SmartList;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class CreateSmartListAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(User $user, array $data, ?UploadedFile $image = null): SmartList
    {
        $data['user_id'] = $user->id;
        $data['description'] = $data['description'] ?? '';
        $mealIds = $data['meal_ids'] ?? [];
        unset($data['meal_ids']);

        if ($image !== null) {
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/smart-lists'), $imageName);
            $data['image'] = $imageName;
        }

        $smartList = SmartList::create($data);

        if (! empty($mealIds)) {
            $smartList->meals()->attach($mealIds);
        }

        return $smartList->load('meals');
    }
}
