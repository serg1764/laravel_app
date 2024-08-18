<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DisabledController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/admin', [AdminController::class, 'index'])->middleware('checkAdmin');
Route::get('/account', [AccountController::class, 'index'])->middleware('checkAccount:user,admin');
Route::get('/disabled', [DisabledController::class, 'index'])->name('disabled');
Route::get('/admin/category/{id}', [CategoriesController::class, 'getCategory'])->name('admin.getCategory');

