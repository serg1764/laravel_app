<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for($y = 1; $y <= 12; $y++) {

            $data[] = [
                'id' => $y,
                'user_id' => $y,
                'created_at' => now(),
                'currency' => "USD",
                'sum' => rand(200,500),
            ];

        }

        DB::table('orders')->insert($data);
    }
}
