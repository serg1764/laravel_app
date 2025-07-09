<?php

namespace App\Repositories;

use App\Models\Discount;

interface DiscountRepositoryInterface
{
    public function findByIdOrName(string|int $id_or_name): ?Discount;
}
