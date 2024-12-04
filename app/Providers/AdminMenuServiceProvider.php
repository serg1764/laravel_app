<?php

namespace App\Providers;

use App\Models\Helper;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\AdminMenu;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Cache;

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

            /** Настройка для работы с кешем Ларавель
             * Ключи для кэша
            $menuItemsItemKey = 'menu_items_item';
            $menuItemsCatKey = 'menu_items_cat';

            // Получаем данные из Кеша настроенного в ларавеле или выполняем запрос и кэшируем
            $menuItemsItem = Cache::remember($menuItemsItemKey, now()->addHours(1), function () {
                return AdminMenu::adminMenuPreparation('item');
            });*/

            /** Получаем все подкатегории мз Redis*/
            $menuItemsItem = json_decode(Redis::get('menuItemsItem'), true);
            $menuItemsGoods = json_decode(Redis::get('menuItemsGoods'), true);
            $menuItemsCategories = json_decode(Redis::get('menuItemsCategories'), true);

            if(!isset($menuItemsItem) && !isset($menuItemsGoods) && !isset($menuItemsCategories)) {
                $resRedis = [];
                /** Получаем все подкатегории для добавления в меню */
                $menuItemsItem = AdminMenu::adminMenuPreparation('item');
                $menuItemsCat = AdminMenu::adminMenuPreparation('cat');

                $menuItemsGoods = AdminMenu::buildTree(categories: $menuItemsItem, addNew: true);
                $menuItemsCategories = AdminMenu::buildWithoutTree(categories: $menuItemsCat, addNew: true);

                /** добавляем данные в редис */

                $resRedis['menuItemsItem'] = Redis::set('menuItemsItem', json_encode($menuItemsItem), 'EX', 3600) ? 'Добавили в Редис' : 'Ошибка Редис';
                $resRedis['menuItemsGoods'] = Redis::set('menuItemsGoods', json_encode($menuItemsGoods), 'EX', 3600) ? 'Добавили в Редис' : 'Ошибка Редис';
                $resRedis['menuItemsCategories'] = Redis::set('menuItemsCategories', json_encode($menuItemsCategories), 'EX', 3600) ? 'Добавили в Редис' : 'Ошибка Редис';
                Helper::logToDatabase('Redis',  $resRedis, '$resRedis');

            }

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
                'submenu' => $menuItemsCategories
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
