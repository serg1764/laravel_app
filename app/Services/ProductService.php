<?php

namespace App\Services;

use App\Models\Products;
use App\Repositories\DiscountRepositoryInterface;

class ProductService
{
    protected Products $products;
    protected DiscountRepositoryInterface $discountRepo;

    public function __construct(Products $products, DiscountRepositoryInterface $discountRepo)
    {
        $this->products = $products;
        $this->discountRepo = $discountRepo;
    }

    public function getProductWithDiscount(string|int $product_id, string|int|null $discount_id_or_name = null): array
    {
        $result = $this->products->getProduct($product_id);

        if (!$result['success'] || !$discount_id_or_name) {
            return $result;
        }

        $discount = $this->discountRepo->findByIdOrName($discount_id_or_name);

        if (!$discount) {
            $result['error'] = 'Discount not found';
            return $result;
        }

        $price = $result['data']['price'];

        switch ($discount->type) {
            case 'fixed':
                $price = max(0, $price - $discount->value);
                break;
            case 'percent':
                $price = max(0, $price * (1 - $discount->value / 100));
                break;
            case 'free_shipping':
                $result['data']['free_shipping'] = true;
                break;
        }

        $result['data']['price_with_discount'] = round($price, 2);
        $result['data']['applied_discount'] = $discount->only(['id', 'name', 'type', 'value']);

        return $result;
    }
}
