<?php

namespace App\Action\Address;

use App\Models\User;

use Illuminate\Support\Facades\DB;

class DeleteAddressAction
{
    public function execute(User $user , string $id  )
    {
  $address = $user->addresses()->findOrFail($id);

            DB::beginTransaction();

            $wasDefault = $address->is_default;
            $address->delete();

            // If deleted address was default, set another address as default
            if ($wasDefault) {
                $newDefault = $user->addresses()->first();
                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }

            DB::commit();
            }
}