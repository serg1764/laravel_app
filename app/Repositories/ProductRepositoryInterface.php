<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function getProduct(int|string $id): array;
    public function saveProduct(array $data): array;
    public function getListOfItems(int $id): array;
}
