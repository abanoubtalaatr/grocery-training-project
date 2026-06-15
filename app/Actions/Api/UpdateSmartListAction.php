<?php

namespace App\Actions\Api;

use App\Models\SmartList;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class UpdateSmartListAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(User $user, string $id, array $data, ?UploadedFile $image = null): SmartList
    {
        $smartList = SmartList::where('user_id', $user->id)->findOrFail($id);

        if (array_key_exists('description', $data) && $data['description'] === null) {
            $data['description'] = '';
        }

        $mealIds = $data['meal_ids'] ?? null;
        unset($data['meal_ids']);

        if ($image !== null) {
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
}
