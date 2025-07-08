<?php

namespace App\Services;

use App\Models\Products;

class ProductService{

    protected Products $products;

    public function __construct(Products $products)
    {
        $this->products = $products;
    }

    public function getProduct($id) : array
    {
        return $this->products->getProduct($id);

    }
}
