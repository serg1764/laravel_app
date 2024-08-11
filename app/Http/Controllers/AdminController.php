<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use JeroenNoten\LaravelAdminLte\AdminLte;

class AdminController extends Controller
{
    protected $adminlte;

    public function __construct(AdminLte $adminlte)
    {
        Helper::logToDatabase($this->adminlte);
        $this->adminlte = $adminlte;
    }
    public function index()
    {
        // Получаем данные, которые нужно передать в представление
        $usersCount = User::count(); // Пример получения количества пользователей
        $postsCount = User::count();
        //$postsCount = Post::count();  // Пример получения количества постов

        // Возвращаем представление для администрирования
        //return view('admin.index', compact('usersCount', 'postsCount'));
        // Здесь предполагается, что у вас есть файл resources/views/admin/index.blade.php

        Helper::logToDatabase($this->adminlte);

        return view('vendor.adminlte.page', [
            'adminlte' => $this->adminlte,
            'usersCount' => $usersCount,
            'postsCount' => $postsCount
        ]);}
}
