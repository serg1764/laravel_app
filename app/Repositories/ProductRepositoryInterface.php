<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getProduct(int|string $id): array;
    public function saveProduct(array $data): array;
    public function getListOfItems(int $id): array;

    /**
     * Получение списка продуктов с фильтрацией.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getFilteredList(array $filters): LengthAwarePaginator;
}
