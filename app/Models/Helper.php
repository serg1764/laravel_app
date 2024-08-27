<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class Helper
{
    public static function logToDatabase($checkPoint, $message, $level = 'info'): void
    {
        $user = Auth::check() ? Auth::user()->name : null; // Получаем имя пользователя, если он залогинен

        // Получаем информацию о файле и строке вызова
        $backtrace = debug_backtrace();
        $file = $backtrace[0]['file'] ?? null; // Имя файла
        $line = $backtrace[0]['line'] ?? null; // Номер строки

        DB::table('logs')->insert([
            'message' => print_r($message, true),
            'level' => $level,
            'user' => $user,
            'file_name' => $file,  // Добавляем имя файла с путем
            'line_number' => $line,  // Добавляем номер строки
            'check_point' => $checkPoint,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }


    public static function showUrl(): array
    {
        // Получаем путь текущего запроса
        $path = Request::path();

        // Получаем полный URL текущего запроса
        $fullUrl = Request::fullUrl();

        return [$path, $fullUrl];
    }

}
