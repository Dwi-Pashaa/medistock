<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $fillable = ['name', 'contact', 'email', 'address'];

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'supplier_id', 'id');
    }
}
