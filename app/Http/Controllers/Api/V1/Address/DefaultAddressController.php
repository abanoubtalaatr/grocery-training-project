<?php

namespace App\Http\Controllers\Api\V1\Address;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use AWS\CRT\HTTP\Request;
use Illuminate\Http\JsonResponse;

class DefaultAddressController extends Controller
{
    use ApiResponse;
    public function __invoke(Request $request ,string $id):JsonResponse
    {
         $user = $request('user');
            $address = $user->addresses()->findOrFail($id);  
             $address->update(['is_default' => true]);
        return $this->sendResponse('Default address updated successfully' ,$address,200);
    }
}