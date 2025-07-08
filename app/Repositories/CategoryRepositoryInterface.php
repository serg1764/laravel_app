<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function save(array $data): array;
    public function get(int|string $id): array;
    public function all(): Collection;
}
