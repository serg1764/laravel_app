<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            (object) [
                'id' => 1,
                'name' => 'Электроника',
                'parent_id' => null,
            ],
            (object) [
                'id' => 2,
                'name' => 'Мобильные телефоны',
                'parent_id' => 1,
            ],
            (object) [
                'id' => 3,
                'name' => 'Ноутбуки',
                'parent_id' => 1,
            ],
            (object) [
                'id' => 4,
                'name' => 'Одежда',
                'parent_id' => null,
            ],
            (object) [
                'id' => 5,
                'name' => 'Кнопочные',
                'parent_id' => 2,
            ],
            (object) [
                'id' => 6,
                'name' => 'Сенсорные',
                'parent_id' => 2,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'id' => $category->id,
                'parent_id' => $category->parent_id,
                'title' => $category->name,
                'name' => $category->name,
                'description' => 'Описание для ' . $category->name,
                'content' => 'Контент для ' . $category->name,
                'imgs' => 'image_' . $category->id . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
