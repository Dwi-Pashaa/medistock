<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;
    protected $table = 'purchase_item';
    protected $fillable = ['purchase_id', 'product_id', 'qty', 'total'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
