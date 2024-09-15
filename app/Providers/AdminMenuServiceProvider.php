<?php

namespace App\Providers;

use App\Models\Helper;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\AdminMenu;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AdminMenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Получаем все подкатегории для добавления в меню
            $menuItemsItem = AdminMenu::adminMenuPreparation('item');
            $menuItemsCat = AdminMenu::adminMenuPreparation('cat');

            $menuItemsGoods = AdminMenu::buildTree(categories: $menuItemsItem, addNew: true);

            $menuItemsСategories = AdminMenu::buildWithoutTree(categories: $menuItemsCat, addNew: true);

            $event->menu->addIn('products',$menuItemsItem);

            $event->menu->addAfter('pages', [
                'key' => 'products',
                'text' => 'products',
                'url' => 'admin/settings',
                'icon' => 'fas fa-list',
                'submenu' => $menuItemsGoods
            ]);

            $event->menu->addAfter('products', [
                'key' => 'categories',
                'text' => 'categories',
                'url' => 'admin/settings',
                'icon' => 'fas fa-list',
                'submenu' => $menuItemsСategories
            ]);

            $event->menu->add([
                'key' => 'account_settings_profile',
                'text' => 'Profile',
                'url' => 'account/edit/profile',
            ]);

            $event->menu->add([
                'key' => 'account_settings1',
                'header' => 'Account Settings1',
            ]);

            // Получаем текущее меню
            $menu = config('adminlte.menu');
        });
    }
}
