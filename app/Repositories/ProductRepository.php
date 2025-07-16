<?php

namespace App\Repositories;

use App\Models\Products;

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
}
