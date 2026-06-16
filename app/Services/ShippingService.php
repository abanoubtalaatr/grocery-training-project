<?php

namespace App\Services;

use App\Models\Setting;

class ShippingService
{
    /**
     * Calculate shipping fee for an order/cart.
     * Business rules: pickup = 0; delivery = settings.shipping_fee unless subtotal >= free_shipping_min_order.
     *
     * @param  float  $subtotal  Cart/order subtotal (after item discounts)
     * @param  string  $deliveryType  'delivery' or 'pickup'
     */
    public function calculateShippingFee(float $subtotal, string $deliveryType): float
    {
        if ($deliveryType !== 'delivery') {
            return 0.0;
        }

        $settings = Setting::getSettings();
        $fee = (float) ($settings->shipping_fee ?? 0);
        $minForFree = $settings->free_shipping_min_order !== null
            ? (float) $settings->free_shipping_min_order
            : null;

        if ($minForFree !== null && $subtotal >= $minForFree) {
            return 0.0;
        }

        return $fee;
    }

    public function calculateOrderTotals($cart, float $shippingFee): array
    {
        $subtotal = (float) $cart->subtotal;
        $tax = (float) $cart->tax;
        $discount = (float) $cart->discount;

        $total = $subtotal + $tax + $shippingFee - $discount;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'shipping_fee' => $shippingFee,
            'total' => $total,
        ];
    }
}
