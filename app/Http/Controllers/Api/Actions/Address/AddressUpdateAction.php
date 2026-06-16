<?php
namespace App\Http\Controllers\Api\Actions\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressUpdateAction
{
    public function handle(Address $address, array $data): Address
    {
        return DB::transaction(fn () => $address->update($data));
    }
}