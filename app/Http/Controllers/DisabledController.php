<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DisabledController extends Controller
{
    public function index()
    {
        // Возвращаем представление для Disabled
        return view('disabled.index'); // Здесь предполагается, что у вас есть файл resources/views/disabled/index.blade.php
    }
}
