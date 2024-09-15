<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Products;
use Illuminate\Http\Request;
use JeroenNoten\LaravelAdminLte\AdminLte;

class ProductsController extends Controller
{
    protected $adminlte;

    public function __construct(AdminLte $adminlte)
    {
        $this->adminlte = $adminlte;
    }
    public function index($id)
    {
        $itemsData = Products::getListOfItems($id);

        Helper::logToDatabase('ProductController', $itemsData['data'], '$itemsData');

        return view('vendor.adminlte.page', [
            'adminlte' => $this->adminlte,
            'phoneData' => $itemsData['data'],
            'type' => 3
        ]);
    }
    public function getProduct($id)
    {
        $itemsData = Products::getProduct($id);
        Helper::logToDatabase('ProductController', $itemsData, '$itemsData');
        return view('vendor.adminlte.page', [
            'adminlte' => $this->adminlte,
            'phoneData' => $itemsData['data'],
            'type' => 4
        ]);
    }

    public function saveProduct(Request $request)
    {
        $Data = $request->all();
        // Сохраняем данные продукта
        $productData = Products::saveProduct($Data);

        return $productData;
    }
}
