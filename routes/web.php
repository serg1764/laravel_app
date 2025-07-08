<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DisabledController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

Route::get('/account', [AccountController::class, 'index'])
    ->middleware('checkAccount:user,admin');

Route::get('/disabled', [DisabledController::class, 'index'])
    ->name('disabled');

Route::middleware('checkAdmin')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);

    Route::get('/category/{id}', [CategoriesController::class, 'getCategory'])
        ->name('admin.getCategory');

    Route::post('/save-category', [CategoriesController::class, 'saveCategory'])
        ->name('admin.saveCategory');

    Route::get('/list-of-items/{id}', [ProductsController::class, 'index'])
        ->name('admin.getListOfItems');

    Route::get('/product/{id}', [ProductsController::class, 'getProduct'])
        ->name('admin.getProduct');

    Route::post('/save-product', [ProductsController::class, 'saveProduct'])
        ->name('admin.saveProduct');
});

