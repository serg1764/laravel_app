<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StarWarsRebbit extends Model
{

    protected $table = 'api_star_wars_rebbit'; // Указываем имя таблицы

    protected $fillable = ['name']; // Разрешаем массовое заполнение поля 'name'
}
