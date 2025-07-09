<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        Discount::create([
            'name' => 'fixed10',
            'type' => 'fixed',
            'value' => 10
        ]);

        Discount::create([
            'name' => 'percent20',
            'type' => 'percent',
            'value' => 20
        ]);

        Discount::create([
            'name' => 'freeship',
            'type' => 'free_shipping',
            'value' => null
        ]);
    }
}
