<?php

namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    public function __construct(protected CategoryRepositoryInterface $repo) {}

    public function saveCategory(array $data): array
    {
        $response = $this->repo->save($data);

        $data['name'] = ucfirst($data['name']);
        $response['data']['my_value2'] = 'Yurochka';
        $response['data']['name'] = $data['name'];

        if ($response['success']) {
            Log::info('Категория успешно сохранена');
        }

        return $response;
    }

    public function getCategory(int|string $id): array
    {
        return $this->repo->get($id);
    }

    public function getAll(): Collection
    {
        return $this->repo->all();
    }
}

