<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class AdminMenu extends Model
{
    use HasFactory;

    public static function adminMenuPreparation($type): array
    {
        return Category::all()->map(function ($category) use ($type) {
            // Проверяем, существует ли маршрут 'admin.getCategory'
            if (Route::has('admin.getCategory') && $type === 'cat') {
                $url = route('admin.getCategory', $category->id);
            }
            elseif (Route::has('admin.getListOfItems') && $type === 'item') {
                $url = route('admin.getListOfItems', $category->id);
            } else {
                $url = '#'; // Или другой запасной вариант
            }

            //$url = route('test', ['id' => $category->id]);

            return [
                'text' => $category->name,
                'url' => $url,
                'parent_id' => $category->parent_id,
                'id' => $category->id,
            ];
        })->toArray();
    }


    public static function buildTree($categories, $parentId = null, $level = 0, $addNew = false): array
    {
        $branch = [];
        foreach ($categories as $category) {
            if ($category['parent_id'] === $parentId) {
                $children = self::buildTree($categories, $category['id'], $level + 1);

                // Создаем элемент ветки с учетом уровня вложенности
                $item = [
                    'text' => str_repeat('-', $level) . ' ' . $category['text'], // Добавляем префиксы в зависимости от уровня
                    'url' => $category['url']
                ];

                // Добавляем 'submenu' только если он не пустой
                if (count($children) > 0) {
                    $item['submenu'] = $children;
                }

                // Добавляем элемент в ветку
                $branch[] = $item;
            }
        }

        if($addNew){
            $branch[] = [
                'text' => 'New Product',
                'url' => route('admin.getProduct', 'new'),
                'parent_id',
                'id'
            ];
        }

        return $branch;
    }

    public static function buildWithoutTree(array $categories, $parentId = null, $prefix = '', $addNew = false):array
    {
        $result = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] === $parentId) {
                $category['text'] = $prefix . $category['text'];
                $result[] = $category;
                $result = array_merge($result, self::buildWithoutTree($categories, $category['id'], $prefix . '-'));
            }
        }

        if($addNew){
            $result[] = [
            'text' => 'New Category',
            'url' => route('admin.getCategory', 'new'),
            'parent_id',
            'id'
            ];
        }

        return $result;
    }
}
