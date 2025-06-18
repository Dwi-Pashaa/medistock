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
        Schema::table('purchase_item', function (Blueprint $table) {
            $table->foreignId('purchase_id')->references('id')->on('purchase')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_item', function (Blueprint $table) {
            $table->foreignId('purchase_id')->references('id')->on('purchase')->onDelete('CASCADE');
        });
    }
};
