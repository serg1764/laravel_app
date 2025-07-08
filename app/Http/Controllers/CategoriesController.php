<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use JeroenNoten\LaravelAdminLte\AdminLte;

class CategoriesController extends Controller
{
    protected AdminLte $adminlte;
    protected CategoryService $categoryService;

    public function __construct(AdminLte $adminlte, CategoryService $categoryService)
    {
        $this->adminlte = $adminlte;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        // Получаем все категории
        $categories = $this->categoryService->getAll();

        return view('categories.index', compact('categories'));
    }

    public function getCategory($id)
    {
        $url = route('admin.getCategory', 1);

        // Получаем категорию по ID
        $categoryData = $this->categoryService->getCategory($id);

        if($categoryData['success']) {
            $postsCount = $usersCount = User::count(); // Пример получения количества пользователей
            // $postsCount = User::count(); убрали дулирующий запрос с помощью панели.

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
        $categoryData = $this->categoryService->saveCategory($Data);

        return $categoryData;
    }

}
