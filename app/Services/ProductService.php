<?php

namespace App\Services;

use App\Models\Helper;
use App\Repositories\DiscountRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private DiscountRepositoryInterface $discountRepo
    ) {}

    public function getProductWithDiscount(string|int $product_id, string|int|null $discount_id_or_name = null): array
    {
        $result = $this->productRepository->getProduct($product_id);

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

    public function getListOfItems(int $id): array
    {
        $itemsData = $this->productRepository->getListOfItems($id);
        Helper::logToDatabase('ProductService', $itemsData['data'], 'getListOfItems');
        return $itemsData;
    }

    public function getSingleProduct(int $id): array
    {
        $itemData = $this->productRepository->getProduct($id);
        Helper::logToDatabase('ProductService', $itemData, 'getSingleProduct');
        return $itemData;
    }

    public function saveProduct(Request $request): array
    {
        $data = $request->all();
        return $this->productRepository->saveProduct($data);
    }
}
