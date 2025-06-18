<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $sort = $request->sort ?? 10;

        $product = Product::with(['kategori', 'supplier'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('code', 'like', "%$search%")
                    ->orWhereHas('kategori', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        return view("pages.product.index", compact("product"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        $supplier = Supplier::all();
        return view("pages.product.create", compact("kategori", "supplier"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "code" => "required",
            "name" => "required",
            "harga_beli" => "required",
            "harga_jual" => "required",
            "kategori_id" => "required",
            "supplier_id" => "required"
        ]);

        $post = $request->all();

        Product::create($post);

        return redirect()->route('product')->with('success', 'Berhasil menyimpan data.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $count = $request->count ?? 10;
        $product = Product::find($id);
        return view("pages.product.show", compact("product", "count"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = Kategori::all();
        $supplier = Supplier::all();
        $product = Product::find($id);
        return view("pages.product.edit", compact("kategori", "supplier", "product"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        $request->validate([
            "code" => "required",
            "name" => "required",
            "harga_beli" => "required",
            "harga_jual" => "required",
            "kategori_id" => "required",
            "supplier_id" => "required"
        ]);

        $put = $request->all();

        $product->update($put);

        return redirect()->route('product')->with('success', 'Berhasil menyimpan data.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'Data Not Found.',
            ]);
        }

        $product->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
