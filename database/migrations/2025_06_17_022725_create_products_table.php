<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->references('id')->on('supplier')->onDelete('CASCADE');
            $table->foreignId('kategori_id')->references('id')->on('kategori')->onDelete('CASCADE');
            $table->string('code')->unique();
            $table->string('name');
            $table->double('harga_beli');
            $table->double('harga_jual');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
