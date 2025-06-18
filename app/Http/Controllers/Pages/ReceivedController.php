<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;

class ReceivedController extends Controller
{
    public function index(Request $request)
    {
        $code = $request->code;

        $purchaseAll = Purchase::where('status', 'pending')->get();

        $purchase = null;
        $items = collect();

        if (!empty($code)) {
            $purchase = Purchase::where('code', $code)->first();

            if ($purchase) {
                $items = PurchaseItem::with('product')
                    ->where('purchase_id', $purchase->id)
                    ->get();
            }
        }

        return view("pages.received.index", compact("purchaseAll", "items"));
    }

    /**
     * Store the specified resource in storage.
     */
    public function store(Request $request)
    {
        foreach ($request->product_id as $key => $value) {
            $product = Product::where('id', $value)->first();

            if ($product) {
                $product->update([
                    'stock' => $product->stock + $request->stock[$key]
                ]);
            }
        }

        Purchase::where('code', $request->code)->update([
            'status' => 'accepted'
        ]);

        return redirect()->route('received')->with('success', 'Berhasil menerima barang');
    }
}
