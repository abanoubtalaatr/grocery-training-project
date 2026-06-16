<?php

namespace App\Filters;

use App\Filters\QueryFilter;

class OrderFilter extends QueryFilter
{
    /**
     * Filter by order status.
     */
    public function status($value)
    {
        if ($value) {
            $this->builder->where('status', $value);
        }
    }

    /**
     * Filter by delivery type.
     */
    public function delivery_type($value)
    {
        if ($value) {
            $this->builder->where('delivery_type', $value);
        }
    }

    /**
     * Filter by payment method.
     */
    public function payment_method($value)
    {
        if ($value) {
            $this->builder->where('payment_method', $value);
        }
    }

    /**
     * Filter by order number (exact match).
     */
    public function order_number($value)
    {
        if ($value) {
            $this->builder->where('order_number', $value);
        }
    }
}
