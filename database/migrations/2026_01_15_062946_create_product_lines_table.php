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
        Schema::create('product_lines', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sku_number');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('buy_price')->default(0);
            $table->integer('sell_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_lines');
    }
};
