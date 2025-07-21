<?php

namespace App\Repositories;

use App\Models\Products;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getProduct(int|string $id): array
    {
        return Products::getProduct($id);
    }

    public function saveProduct(array $data): array
    {
        return Products::saveProduct($data);
    }

    public function getListOfItems(int $id): array
    {
        return Products::getListOfItems($id);
    }

    public function getFilteredList(array $filters): LengthAwarePaginator
    {
        $query = Products::query();

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return $query->paginate(1);
    }
}
