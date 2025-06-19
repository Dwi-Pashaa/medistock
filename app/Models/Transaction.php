<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transaction';
    protected $fillable = ['customer_id', 'invoice', 'date', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function item()
    {
        return $this->hasMany(TransactionItem::class, 'transcation_id', 'id');
    }

    public static function generateCode()
    {
        $today = Carbon::now();
        $tanggal = $today->format('d');
        $bulan = $today->format('m');
        $tahun = $today->format('Y');

        $countToday = self::whereDate('created_at', $today->toDateString())->count() + 1;

        $urutan = str_pad($countToday, 3, '0', STR_PAD_LEFT);

        return "SALE-{$tanggal}{$bulan}{$urutan}-{$tahun}";
    }
}
