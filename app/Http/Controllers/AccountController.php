<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        // Получаем данные, которые нужно передать в представление
        $usersCount = User::count(); // Пример получения количества пользователей
        $postsCount = User::count();
        //$postsCount = Post::count();  // Пример получения количества постов

        // Возвращаем представление для account
        return view('account.index', compact('usersCount', 'postsCount')); // Здесь предполагается, что у вас есть файл resources/views/account/index.blade.php
    }
}
