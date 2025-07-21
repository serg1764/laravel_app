<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JeroenNoten\LaravelAdminLte\AdminLte;
use App\Services\ProductService;

class ProductsController extends Controller
{
    protected AdminLte $adminlte;
    protected ProductService $productService;

    public function __construct(AdminLte $adminlte, ProductService $productService)
    {
        $this->adminlte = $adminlte;
        $this->productService = $productService;
    }

    public function index($id)
    {
        $itemsData = $this->productService->getListOfItems($id);

        return view('vendor.adminlte.page', [
            'adminlte' => $this->adminlte,
            'phoneData' => $itemsData['data'],
            'type' => 3
        ]);
    }

    public function getProduct($id)
    {
        $itemsData = $this->productService->getSingleProduct($id);

        return view('vendor.adminlte.page', [
            'adminlte' => $this->adminlte,
            'phoneData' => $itemsData['data'],
            'type' => 4
        ]);
    }

    public function saveProduct(Request $request)
    {
        return $this->productService->saveProduct($request);
    }

    public function showToSite(int $id, Request $request)
    {
        $discount = $request->query('discount'); // id или name
        $product = $this->productService->getProductWithDiscount($id, $discount);

        return response()->json($product);
    }

    public function filteredList(Request $request): JsonResponse
    {
        $filters = $request->only(['category_id', 'min_price', 'max_price']);
        $products = $this->productService->getFilteredProducts($filters);

        return response()->json($products);
    }
}
