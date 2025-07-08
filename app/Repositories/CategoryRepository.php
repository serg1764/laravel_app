<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function save(array $data): array
    {
        return Category::saveCategory($data);
    }

    public function get(int|string $id): array
    {
        return Category::getCategory($id);
    }

    public function all(): Collection
    {
        return Category::all();
    }
}
