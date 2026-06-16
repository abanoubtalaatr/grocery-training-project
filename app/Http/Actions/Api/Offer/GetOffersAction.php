<?php

namespace App\Http\Actions\Api\Offer;

use App\Models\Offer;

class GetOffersAction
{
    public function execute(array $filters)
    {
        $query = Offer::active();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['min_purchase'])) {
            $query->where(function ($q) use ($filters) {
                $q->where(
                    'minimum_purchase',
                    '<=',
                    $filters['min_purchase']
                )->orWhereNull('minimum_purchase');
            });
        }

        if (!empty($filters['featured'])) {
            $query->featured();
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where(
                    'title',
                    'like',
                    '%' . $filters['search'] . '%'
                )
                ->orWhere(
                    'code',
                    'like',
                    '%' . $filters['search'] . '%'
                );
            });
        }

        $query->orderBy(
            $filters['order_by'] ?? 'created_at',
            $filters['order_direction'] ?? 'desc'
        );

        return $query->paginate(
            $filters['per_page'] ?? 15
        );
    }
}