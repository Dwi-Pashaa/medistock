<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $table = 'purchase';
    protected $fillable = ['supplier_id', 'date', 'code', 'status'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function item()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id', 'id');
    }

    public static function generateCode()
    {
        $today = Carbon::now();
        $tanggal = $today->format('d');
        $bulan = $today->format('m');
        $tahun = $today->format('Y');

        $countToday = self::whereDate('created_at', $today->toDateString())->count() + 1;

        $urutan = str_pad($countToday, 3, '0', STR_PAD_LEFT);

        return "PO-{$tanggal}{$bulan}{$urutan}-{$tahun}";
    }
}
