<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Указание имени таблицы, если оно отличается от соглашения
    protected $table = 'categories';

    // Указание полей, которые можно массово заполнять
    protected $fillable = ['parent_id', 'title', 'name', 'description', 'url', 'content', 'imgs', 'inactive'];

    // Метод для получения фиксированных данных

    public static function fixedData(): \Illuminate\Support\Collection
    {
        return collect([
            (object) [
                'id' => 1,
                'name' => 'Электроника',
                'parent_id' => NULL,
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
                'parent_id' => NULL,
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
        ]);
    }

    /**
     * @description
     *
    */
    public static function getCategory($id): array
    {
        $Result = [
            'data' => [],
            'success' => false,
            'error' => ''
        ];

        try {

            if($id !== 'new') {
                $Result['data'] = self::findOrFail($id)->toArray();
            }
            else{
                $Result['data'] = [
                    'id' => 'new',
                    'url' => '',
                    'title' => '',
                    'name' => '',
                    'description' => '',
                    'parent_id' => '',
                    'content' => '',
                    'imgs' => '',
                    'inactive' => true,
                    'created_at' => '',
                    'updated_at' => '',

                ];
            }
            // Получение всех категорий
            $allCategories = Category::select('id', 'parent_id', 'name')->get()->toArray();

            // Преобразование массива всех категорий в ассоциативный массив по id
            $categoriesById = [];
            foreach ($allCategories as $cat) {
                $categoriesById[$cat['id']] = $cat;
            }

            // Добавление поля 'parent' в каждый элемент массива
            foreach ($allCategories as &$cat) {
                if ($cat['parent_id']) {
                    $parent = $categoriesById[$cat['parent_id']] ?? null;
                    if ($parent) {
                        $cat['parent'] = "prnt: {$parent['id']} - {$parent['name']}";
                    } else {
                        $cat['parent'] = "prnt: {$cat['parent_id']} - Unknown";
                    }
                } else {
                    $cat['parent'] = "prnt: none";
                }
            }

            $Result['data']['list_all_categories'] = $allCategories;
            $Result['success'] = true;

        } catch (\Exception $e) {
            $Result['error'] = 'Category not found - ' . $e->getMessage();
            Helper::logToDatabase('Category', $e->getMessage(), '$Result');
        }

        Helper::logToDatabase('Category', $Result, '$Result');

        return $Result;
    }

    public static function saveCategory($Data): array
    {
        $Result = [
            'data' => [],
            'success' => false,
            'error' => ''
        ];

        try {
            //dd(self::find($Data['id']));

            $Data['id'] = ($Data['id'] === 'new') ? null : $Data['id'];
            // Найти категорию по ID или создать новую, если не найдена
            $category = self::updateOrCreate(
                [
                   'id' => $Data['id']
                ], // Условие поиска по ID
                [
                    'parent_id' => $Data['parent_id'],
                    'title' => $Data['title'],
                    'name' => $Data['name'],
                    'description' => $Data['description'],
                    'url' => $Data['url'],
                    'content' => $Data['content'],
                    'imgs' => $Data['imgs'],
                    'inactive' => $Data['inactive']
                ] // Данные для обновления или создания
            );

            $Result['data'] = $category->toArray();
            $Result['incomingData'] = $Data;
            $Result['success'] = true;
        } catch (\Exception $e) {
            $Result['error'] = 'Failed to save category - ' . $e->getMessage();
            Helper::logToDatabase('Category', $e->getMessage(), '$Result');
        }

        return $Result;
    }
}
