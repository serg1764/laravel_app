<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use JeroenNoten\LaravelAdminLte\AdminLte;

class CategoriesController extends Controller
{
    protected $adminlte;

    public function __construct(AdminLte $adminlte)
    {
        $this->adminlte = $adminlte;
    }

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
        $categoryData = Category::getCategory($id);

        if($categoryData['success']) {
            $usersCount = User::count(); // Пример получения количества пользователей
            $postsCount = User::count();

            //return view('admin.index', compact('categoryData', 'usersCount', 'postsCount'));
            return view('vendor.adminlte.page', [
                'adminlte' => $this->adminlte,
                'usersCount' => $usersCount,
                'postsCount' => $postsCount,
                'categoryData' => $categoryData['data'],
                'type' => 1
            ]);
        }
        else{
            return view('vendor.adminlte.page', [
                'adminlte' => $this->adminlte,
                'type' => 2
            ]);

        }
    }

    public function saveCategory(Request $request)
    {
        $Data = $request->all();
        // Сохраняем данные категории
        $categoryData = Category::saveCategory($Data);

        return $categoryData;
    }

}
