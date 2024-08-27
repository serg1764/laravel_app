<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CategoriesController extends Controller
{
    public function index()
    {
        // Получаем все категории
        $categories = Category::all();

        return view('categories.index', compact('categories'));
    }

    public function getCategory($id)
    {
        $url = route('admin.getCategory', 1);

        // Получаем категорию по ID
        $category = Category::findOrFail($id);

        $usersCount = User::count(); // Пример получения количества пользователей
        $postsCount = User::count();
        $categoryData = $category->toArray();

        return view('admin.index', compact('categoryData', 'usersCount', 'postsCount'));
    }
}
