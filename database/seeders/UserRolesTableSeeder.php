<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_roles')->insert([
            ['user_id' => 1, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        for ($i = 2; $i <= 12; $i++) {
            DB::table('user_roles')->insert([
                'user_id' => $i,
                'role_id' => rand(2, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
