<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\CategoryService;
use App\Services\ProductService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        $this->app->singleton(CategoryService::class);
        $this->app->singleton(ProductService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // User::observe(UserObserver::class);
    }
}
