<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $countPurchase = Purchase::count();
        $countSupplier = Supplier::count();
        $countProduct  = Product::count();
        $countSale     = Transaction::count();

        $products = Product::select('name', 'stock')->get();
        $labelsProduct = $products->pluck('name');
        $stocksProduct = $products->pluck('stock');

        return view("pages.dashboard", compact(
            "countPurchase",
            "countSupplier",
            "countProduct",
            "countSale",
            "products",
            "labelsProduct",
            "stocksProduct"
        ));
    }
}
