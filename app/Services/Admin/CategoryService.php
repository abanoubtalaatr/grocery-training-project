<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function paginate(int $perPage = 15, ?string $q = null): LengthAwarePaginator
    {
        $query = Category::ordered();
        if ($q) {
            $query->where('name', 'like', '%'.$q.'%');
        }
        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($this->prepareData($data));
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($this->prepareData($data));
        return $category->refresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    protected function prepareData(array $data): array
    {
        if (isset($data['is_active'])) {
            $data['is_active'] = (bool) $data['is_active'];
        }

        return $data;
    }
}
