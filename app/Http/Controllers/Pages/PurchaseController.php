<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $sort = $request->sort ?? 10;

        $purchase = Purchase::with(['supplier', 'item'])
            ->when($search, function ($query, $search) {
                $query->where('code', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        return view("pages.purchase.index", compact("purchase"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = Product::all();
        $supplier = Supplier::all();
        return view("pages.purchase.create", compact("product", "supplier"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            "supplier_id" => "required",
            "date" => "required"
        ]);

        $purchase = new Purchase();
        $purchase->supplier_id = $request->supplier_id;
        $purchase->date = $request->date;
        $purchase->code = Purchase::generateCode();
        $purchase->save();

        $count = count($request->product_id);

        $totalPembelian = 0;
        $count = count($request->product_id);

        for ($i = 0; $i < $count; $i++) {
            $productId = $request->product_id[$i];
            $qty = (int) $request->qty[$i];

            $product = Product::find($productId);

            if (!$product) {
                continue;
            }

            $harga = $product->harga_jual;
            $total = $qty * $harga;
            $totalPembelian += $total;

            $item = new PurchaseItem();
            $item->purchase_id = $purchase->id;
            $item->product_id = $productId;
            $item->qty = $qty;
            $item->total = $total;
            $item->save();
        }

        $purchase->save();

        return redirect()->route('purchase')->with('success', 'Berhasil menyimpan data.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchase = Purchase::with('item')->where('id', $id)->first();

        $product = Product::all();
        $supplier = Supplier::all();

        return view("pages.purchase.edit", compact("product", "supplier", "purchase"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "supplier_id" => "required",
            "date" => "required"
        ]);

        $purchase = Purchase::find($id);
        $purchase->supplier_id = $request->supplier_id;
        $purchase->date = $request->date;
        $purchase->save();

        PurchaseItem::where('purchase_id', $purchase->id)->delete();

        $totalPembelian = 0;
        $count = count($request->product_id);

        for ($i = 0; $i < $count; $i++) {
            $productId = $request->product_id[$i];
            $qty = (int) $request->qty[$i];

            $product = Product::find($productId);

            if (!$product) {
                continue;
            }

            $harga = $product->harga_jual;
            $total = $qty * $harga;
            $totalPembelian += $total;

            $item = new PurchaseItem();
            $item->purchase_id = $purchase->id;
            $item->product_id = $productId;
            $item->qty = $qty;
            $item->total = $total;
            $item->save();
        }

        return redirect()->route('purchase')->with('success', 'Berhasil memperbarui data.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getProduct(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();

        return response()->json(['data' => $product]);
    }

    public function export($code)
    {
        $purchase = Purchase::with('item.product')->where('code', $code)->first();

        $pdf = Pdf::loadView('export.resi', compact('purchase'));
        $pdf->setBasePath(public_path());
        return $pdf->download('resi-pembelian-' . $code . '.pdf');
    }
}
