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
    protected $fillable = ['*'];

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
}
