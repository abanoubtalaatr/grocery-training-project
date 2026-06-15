<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Exception;

class UpdateCartItemAction
{
   
    public function execute(Cart $cart, string $itemId, int $quantity): Cart
    {
       
        $cartItem = $cart->items()->findOrFail($itemId);

        
        if ($cartItem->meal->stock_quantity < $quantity) {
            throw new Exception("Only {$cartItem->meal->stock_quantity} items available in stock");
        }

       
        return DB::transaction(function () use ($cart, $cartItem, $quantity) {
            $cartItem->update([
                'quantity' => $quantity
            ]);

        
            $cart->calculateTotals();
 
            return $cart->load(['items.meal.category', 'items.meal.subcategory']);
        });
    }
}