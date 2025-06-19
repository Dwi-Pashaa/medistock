<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Retur;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
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

        return view("pages.report.purchase", compact("purchase"));
    }

    public function detailPurchase($code)
    {
        $purchase = Purchase::with(['supplier', 'item.product'])->where('code', $code)->first();
        return view("pages.report.purchase-detail", compact("purchase"));
    }

    public function exportPurchase($code)
    {
        $purchase = Purchase::with('item.product', 'supplier')->where('code', $code)->first();

        $pdf = Pdf::loadView('export.report-purchase', compact('purchase'));
        $pdf->setBasePath(public_path());
        return $pdf->download('report-purchase-' . $code . '.pdf');
    }

    public function retur(Request $request)
    {
        $code = $request->code ?? null;

        $purchase = Purchase::select(['code'])->get();

        $retur = Retur::with('purchase', 'product', 'user')
            ->when($code, function ($query, $code) {
                $query->whereHas('purchase', function ($q) use ($code) {
                    $q->where('code', 'like', "%{$code}%");
                });
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view("pages.report.retur", compact("retur", "purchase"));
    }

    public function exportRetur(Request $request)
    {
        $code = $request->code ?? null;
        $retur = Retur::with('purchase', 'product', 'user')
            ->when($code, function ($query, $code) {
                $query->whereHas('purchase', function ($q) use ($code) {
                    $q->where('code', 'like', "%{$code}%");
                });
            })
            ->orderBy('id', 'DESC')
            ->get();

        $pdf = Pdf::loadView('export.report-retur', compact("retur", "code"));
        return $pdf->download('Laporan pengembalian product #' . $code . '.pdf');
    }

    public function sale(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $transaction = Transaction::with(['customer', 'item.product']);

        if (!empty($start) && !empty($end)) {
            $start = Carbon::parse($start)->startOfDay();
            $end = Carbon::parse($end)->endOfDay();
            $transaction->whereBetween('date', [$start, $end]);
        }

        $transaction = $transaction->orderBy('date', 'DESC')->get();

        return view("pages.report.transaction", compact("transaction"));
    }

    public function exportSale(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $transaction = Transaction::with(['customer', 'item.product']);

        if (!empty($start) && !empty($end)) {
            $start = Carbon::parse($start)->startOfDay();
            $end = Carbon::parse($end)->endOfDay();
            $transaction->whereBetween('date', [$start, $end]);
        }

        $transaction = $transaction->orderBy('date', 'DESC')->get();

        $pdf = Pdf::loadView('export.transaction', compact("transaction"));
        return $pdf->stream('laporan penjualan.pdf');
    }
}
