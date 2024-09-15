<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    // Указание имени таблицы, если оно отличается от соглашения
    protected $table = 'products';

    // Указание полей, которые можно массово заполнять
    protected $fillable = [
        'name', 'title', 'url', 'description', 'content', 'price', 'quantity', 'sku',
        'category_id', 'image', 'inactive', 'created_at', 'updated_at', 'brand'
    ];

    public static function getListOfItems($category_id): array
    {
        $Result = [
            'data' => [],
            'success' => false,
            'error' => ''
        ];

        try {
            // Получение всех товаров по category_id
            $products = self::where('category_id', $category_id)
                ->get([
                    'id',
                    'brand',
                    'name',
                    'price',
                    'quantity',
                    'image',
                    'title',
                    'inactive',
                    'category_id'
                ]); // Выбор нужных полей

            $Result['data'] = $products->toArray();
            $Result['success'] = true;
        } catch (\Exception $e) {
            // Логирование ошибки и возврат ошибки в ответе
            $Result['error'] = $e->getMessage();
        }

        return $Result;
    }

    public static function getProduct($id): array
    {
        $Result = [
            'data' => [],
            'success' => false,
            'error' => ''
        ];

        try {

            if ($id !== 'new') {
                $fillableFields = array_merge((new self())->getFillable(), ['id']);
                $product = self::where('id', $id)
                    ->first($fillableFields);

                $Result['data'] = $product->toArray();
            }
            else {
                $Result['data'] = [
                    'id' => 'new',
                    'name' => '',
                    'title' => '',
                    'url' => '',
                    'description' => '',
                    'content' => '',
                    'price' => '',
                    'quantity' => '',
                    'sku' => '',
                    'category_id' => '',
                    'image' => '',
                    'inactive' => true,
                    'brand' => '',
                    'created_at' => '',
                    'updated_at' => '',
                ];
            }

            $list_all_categories = Category::getAllCategories();
            if($list_all_categories['success']){
                $Result['data']['list_all_categories'] = $list_all_categories['data'];
            }
            else{
                // Выбрасываем исключение, если данные не были успешно получены
                throw new \Exception('Failed to retrieve categories');
            }

            $Result['success'] = true;
        } catch (\Exception $e) {
            // Логирование ошибки и возврат ошибки в ответе
            $Result['error'] = $e->getMessage();
        }

        return $Result;
    }

    public static function saveProduct($Data): array
    {
        $Result = [
            'data' => [],
            'success' => false,
            'error' => ''
        ];

        try {
            //dd(self::find($Data['id']));

            $Data['id'] = ($Data['id'] === 'new') ? null : $Data['id'];
            // Найти товар по ID или создать новую, если не найдена
            $category = self::updateOrCreate(
                [
                    'id' => $Data['id']
                ], // Условие поиска по ID
                [
                    'name' => $Data['name'],
                    'title' => $Data['title'],
                    'url' => $Data['url'],
                    'description' => $Data['description'],
                    'content' => $Data['content'],
                    'price' => $Data['price'],
                    'quantity' => $Data['quantity'],
                    'sku' => $Data['sku'],
                    'category_id' => $Data['category_id'],
                    'image' => $Data['image'],
                    'inactive' => $Data['inactive'],
                    'brand' => $Data['brand']
                ] // Данные для обновления или создания
            );

            $Result['data'] = $category->toArray();
            $Result['incomingData'] = $Data;
            $Result['success'] = true;

        } catch (\Exception $e) {
            $Result['error'] = 'Failed to save product - ' . $e->getMessage();
            Helper::logToDatabase('Product', $e->getMessage(), '$Result');
        }

        return $Result;
    }

}
