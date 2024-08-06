<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Показать административную страницу.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        return view('blog.admin.main.index'); // Убедитесь, что путь совпадает с местоположением представления
    }

    /**
     * Показать страницу настроек.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function settings()
    {
        return view('blog.admin.main.settings'); // Убедитесь, что путь совпадает с местоположением представления
    }
}
