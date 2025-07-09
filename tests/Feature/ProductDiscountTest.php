<?php

namespace Tests\Feature;

use App\Models\Discount;
use App\Models\Products;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDiscountTest extends TestCase
{
    //use RefreshDatabase; //Ничего не стираем


    /**
     * php artisan test tests/Feature/ProductDiscountTest.php
     *
     * Запуск одного метода в тесте.
     * php artisan test --filter=test_product_price_with_fixed_discount
     */
    public function test_product_price_with_fixed_discount()
    {
        // Используем существующий продукт
        $product = \App\Models\Products::where('id', 5)->firstOrFail();

        // Получаем скидку из базы
        $discount = \App\Models\Discount::where('name', 'summer2025')->firstOrFail();

        // Ожидаемая цена после применения фиксированной скидки
        $expectedPrice = max(0, floatval($product->price) - $discount->value);

        // Запрос
        $response = $this->getJson("/product/{$product->id}?discount=summer2025");

        // Проверка
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', 5)
            ->assertJsonPath('data.name', 'Redmi Note 6')
            ->assertJsonPath('data.price_with_discount', 4200)
            ->assertJsonPath('data.applied_discount.name', 'summer2025')
            ->assertJsonPath('data.applied_discount.type', 'percent')
            ->assertJsonPath('data.applied_discount.value', '30.00')
            ->assertJsonFragment([
                'id' => $product->id,
                "url" => "redmi-note-6",
                "description" => "Redmi Note 6",
                "content" => "Redmi Note 6",
                "price" => "6000.00",
                "quantity" => 20,
                "sku" => "1236",
                "category_id" => 5,
            ]);
    }
}
