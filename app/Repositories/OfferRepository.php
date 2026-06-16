<?php

namespace App\Repositories;

use App\Models\Offer;

class OfferRepository
{
    public function getFiltered(array $filters, int $perPage, string $orderBy, string $orderDirection)
    {
        $query = Offer::active();
        
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        if (isset($filters['min_purchase'])) {
            $query->where('minimum_purchase', '<=', $filters['min_purchase'])
                  ->orWhereNull('minimum_purchase');
        }
        
        if (!empty($filters['featured'])) {
            $query->featured();
        }
        
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        $query->orderBy($orderBy, $orderDirection);
        return $query->paginate($perPage);
    }

    public function getFeatured(int $limit = 5)
    {
        return Offer::featured()->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    public function findByCode(string $code)
    {
        return Offer::where('code', $code)->firstOrFail();
    }

    public function findByCodeNullable(string $code)
    {
        return Offer::where('code', $code)->first();
    }
}
