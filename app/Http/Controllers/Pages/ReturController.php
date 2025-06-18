<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Retur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $sort = $request->sort ?? 10;

        $return = Retur::with('purchase', 'product', 'user')
            ->when($search, function ($query, $search) {
                $query->whereHas('purchase', function ($q) use ($search) {
                    $q->where('code', 'like', "%$search%");
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        return view("pages.retur.index", compact("return"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $purchase = Purchase::all();

        return view("pages.retur.create", compact("purchase"));
    }

    public function getItem(Request $request)
    {
        $item = PurchaseItem::with('product')->where('purchase_id', $request->purchase_id)->get();

        return response()->json(['data' => $item]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "purchase_id" => "required",
            "product_id" => "required",
            "qty" => "required|integer|min:1",
            "message" => "required"
        ]);

        $product = Product::find($request->product_id);

        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        if ($request->qty > $product->stock) {
            return back()->with('error', 'Jumlah retur melebihi stok yang tersedia.');
        }

        $product->stock -= $request->qty;
        $product->save();

        Retur::create([
            'purchase_id' => $request->purchase_id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'message' => $request->message,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('retur')->with('success', 'Retur berhasil disimpan dan stok dikurangi.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchase = Purchase::all();

        $data = Retur::find($id);

        $items = PurchaseItem::with('product')
            ->where('purchase_id', $data->purchase_id)
            ->get();

        return view("pages.retur.edit", compact("purchase", "data", "items"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "purchase_id" => "required",
            "product_id" => "required",
            "qty" => "required|integer|min:1",
            "message" => "required"
        ]);

        $retur = Retur::find($id);
        $product = Product::where('id', $request->product_id)->first();

        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        $product->stock += $retur->qty;

        if ($request->qty > $product->stock) {
            return back()->with('error', 'Jumlah retur melebihi stok yang tersedia.');
        }

        $product->stock -= $request->qty;
        $product->save();

        $retur->update([
            'purchase_id' => $request->purchase_id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'message' => $request->message,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('retur')->with('success', 'Data retur berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $retur = Retur::find($id);

        $product = Product::find($retur->product_id);

        if ($product) {
            $product->stock += $retur->qty;
            $product->save();
        }

        $retur->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data']);
    }
}
