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
        Schema::create('transaction_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transcation_id')->references('id')->on('transaction')->onDelete('CASCADE');
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->integer('qty');
            $table->double('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_item');
    }
};
