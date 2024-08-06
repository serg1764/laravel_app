<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];

        $users[] = [
            'name' => 'admin', // Рандомное имя длиной 10 символов
            'email' => 'serg1764@gmail.com', // Рандомный email
            'password' => Hash::make('123'), // Пароль 1
            'created_at' => now(),
            'updated_at' => now(),
        ];

        for ($i = 2; $i <= 12; $i++) {
            $users[] = [
                'name' => Str::random(5), // Рандомное имя длиной 10 символов
                'email' => Str::random(5) . '@example.com', // Рандомный email
                'password' => Hash::make('1'), // Пароль 1
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
