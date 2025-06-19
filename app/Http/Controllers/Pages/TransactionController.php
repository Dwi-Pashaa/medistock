<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $sort = $request->sort ?? 10;

        $transaction = Transaction::with(['customer', 'item'])
            ->when($search, function ($query, $search) {
                $query->where('invoice', 'like', "%$search%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        return view("pages.transaction.index", compact("transaction"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customer = Customer::where('status', 'active')->get();
        $product = Product::all();
        return view("pages.transaction.create", compact("customer", "product"));
    }

    public function getProduct(Request $request)
    {
        $code = $request->code;

        $product = Product::where('code', $code)->first();

        return response()->json(['data' => $product]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "customer_id" => "required",
            "date" => "required"
        ]);

        if (empty($request->product_id)) {
            return redirect()->route('transaction.create')->with('warning', 'Pilih product yang akan di jual.');
        }

        $transaction = new Transaction();
        $transaction->customer_id = $request->customer_id;
        $transaction->invoice = Transaction::generateCode();
        $transaction->date = $request->date;
        $transaction->status = 'pending';
        $transaction->save();

        $totalPembelian = 0;
        $count = count($request->product_id);

        for ($i = 0; $i < $count; $i++) {
            $productId = $request->product_id[$i];
            $qty = (int) $request->qty[$i];

            $product = Product::find($productId);

            if (!$product) {
                continue;
            }

            if ($product->stock < $qty) {
                return redirect()->route('transaction.create')->with('warning', "Stok produk {$product->name} tidak mencukupi.");
            }

            $harga = $product->harga_jual;
            $total = $qty * $harga;
            $totalPembelian += $total;

            $item = new TransactionItem();
            $item->transcation_id = $transaction->id;
            $item->product_id = $productId;
            $item->qty = $qty;
            $item->total = $total;
            $item->save();

            $product->stock -= $qty;
            $product->save();
        }

        return redirect()->route('transaction')->with('success', 'Berhasil membuat transaksi');
    }

    /**
     * Display the specified resource.
     */
    public function updateStatus(Request $request, string $id)
    {
        $transaction = Transaction::with('item')->findOrFail($id);

        if ($request->status === 'unpaid' && $transaction->status !== 'unpaid') {
            foreach ($transaction->item as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock += $item->qty;
                    $product->save();
                }
            }
        }

        $transaction->update(['status' => $request->status]);

        return redirect()->route('transaction')->with('success', 'Berhasil mengubah status transaksi');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = Transaction::with('item')->find($id);
        $customer = Customer::where('status', 'active')->get();
        $product = Product::all();
        return view("pages.transaction.edit", compact("customer", "product", "transaction"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "customer_id" => "required",
            "date" => "required"
        ]);

        if (empty($request->product_id)) {
            return redirect()->route('transaction.edit', $id)->with('warning', 'Pilih product yang akan dijual.');
        }

        $transaction = Transaction::findOrFail($id);
        $transaction->customer_id = $request->customer_id;
        $transaction->date = $request->date;
        $transaction->save();

        foreach ($transaction->item as $oldItem) {
            $product = Product::find($oldItem->product_id);
            if ($product) {
                $product->stock += $oldItem->qty;
                $product->save();
            }
            $oldItem->delete();
        }

        $totalPembelian = 0;
        $count = count($request->product_id);

        for ($i = 0; $i < $count; $i++) {
            $productId = $request->product_id[$i];
            $qty = (int) $request->qty[$i];

            $product = Product::find($productId);

            if (!$product) {
                continue;
            }

            if ($product->stock < $qty) {
                return redirect()->route('transaction.edit', $id)->with('warning', "Stok produk {$product->name} tidak mencukupi.");
            }

            $harga = $product->harga_jual;
            $total = $qty * $harga;
            $totalPembelian += $total;

            $item = new TransactionItem();
            $item->transcation_id = $transaction->id;
            $item->product_id = $productId;
            $item->qty = $qty;
            $item->total = $total;
            $item->save();

            $product->stock -= $qty;
            $product->save();
        }

        return redirect()->route('transaction')->with('success', 'Berhasil mengupdate transaksi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::with('item')->findOrFail($id);

        if ($transaction->status !== 'unpaid') {
            foreach ($transaction->item as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock += $item->qty;
                    $product->save();
                }
            }
        }

        $transaction->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }

    public function export($invoice)
    {
        $transaction = Transaction::with('customer', 'item')->where('invoice', $invoice)->first();

        $pdf = Pdf::loadView('export.resi-transaction', compact('transaction'));

        return $pdf->download('#' . $invoice . '.pdf');
    }
}
