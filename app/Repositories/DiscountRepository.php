<?php

namespace App\Repositories;

use App\Models\Discount;

class DiscountRepository implements DiscountRepositoryInterface
{
    public function findByIdOrName(string|int $id_or_name): ?Discount
    {
        return is_numeric($id_or_name)
            ? Discount::find($id_or_name)
            : Discount::where('name', $id_or_name)->first();
    }
}
