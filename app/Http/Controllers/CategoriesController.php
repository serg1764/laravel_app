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
        Helper::logToDatabase($url,'$url');
        Helper::logToDatabase(Route::has('admin.getCategory'),'$url');

        // Получаем категорию по ID
        $category = Category::findOrFail($id);

        $usersCount = User::count(); // Пример получения количества пользователей
        $postsCount = User::count();
        $categoryData = $category->toArray();

        Helper::logToDatabase($categoryData, '$category');

        return view('admin.index', compact('categoryData', 'usersCount', 'postsCount'));
    }
}
