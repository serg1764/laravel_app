<?php

namespace App\Console\Commands;

use App\Models\AdminMenu;
use App\Models\Helper;
use App\Models\Products;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ClearRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-redis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command clear-redis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Укажите нужное подключение
        $redis = Redis::connection();

        // Список ключей для удаления
        $keys = [
            'category_items_4',
            'category_items_6',
        ];

        foreach ($keys as $key) {
            if ($redis->exists($key)) {
                $redis->del($key);
                $this->info("Key '{$key}' deleted.");
            } else {
                $this->info("Key '{$key}' does not exist.");
            }
        }


        // Укажите нужное подключение
        $redis = Redis::connection('cache');

        // Список ключей для удаления
        $keys = [
            'menuItemsItem',
            'menuItemsGoods',
            'menuItemsCategories',
        ];

        foreach ($keys as $key) {
            if ($redis->exists($key)) {
                $redis->del($key);
                $this->info("Key '{$key}' deleted.");
            } else {
                $this->info("Key '{$key}' does not exist.");
            }
        }

        $this->info('Specified Redis keys cleared successfully!');

        $resRedis = [];
        /** Получаем все подкатегории для добавления в меню */
        $menuItemsItem = AdminMenu::adminMenuPreparation('item');
        $menuItemsCat = AdminMenu::adminMenuPreparation('cat');

        $menuItemsGoods = AdminMenu::buildTree(categories: $menuItemsItem, addNew: true);
        $menuItemsCategories = AdminMenu::buildWithoutTree(categories: $menuItemsCat, addNew: true);

        /** добавляем данные в редис */

        $resRedis['menuItemsItem'] = Redis::connection('cache')->set('menuItemsItem', json_encode($menuItemsItem), 'EX', 3600) ? 'Добавили в Редис' : 'Ошибка Редис';
        $resRedis['menuItemsGoods'] = Redis::connection('cache')->set('menuItemsGoods', json_encode($menuItemsGoods), 'EX', 3600) ? 'Добавили в Редис' : 'Ошибка Редис';
        $resRedis['menuItemsCategories'] = Redis::connection('cache')->set('menuItemsCategories', json_encode($menuItemsCategories), 'EX', 3600) ? 'Добавили в Редис' : 'Ошибка Редис';
        Helper::logToDatabase('Redis',  $resRedis, '$resRedis');

        foreach ($resRedis as $key => $item) {
            $this->info("Key '{$key}' '{$item}'.");
        }

        $this->info('Specified Redis keys wrote successfully!');

        $res = [];
        // Получение всех товаров по category_id
        $products = Products::where('category_id', 6)
            ->get([
                'id',
                'brand',
                'name',
                'price',
                'quantity',
                'image',
                'title',
                'inactive',
                'category_id'
            ]);
        $res['cat6'] = Redis::set('category_items_' . 6, json_encode($products->toArray()), 'EX', 3600);

        $products = Products::where('category_id', 4)
            ->get([
                'id',
                'brand',
                'name',
                'price',
                'quantity',
                'image',
                'title',
                'inactive',
                'category_id'
            ]);
        $res['cat4'] = Redis::set('category_items_' . 4, json_encode($products->toArray()), 'EX', 3600);
        foreach ($res as $key => $item) {
            $this->info("Key '{$key}' '{$item}'.");
        }

        return CommandAlias::SUCCESS;
    }
}
