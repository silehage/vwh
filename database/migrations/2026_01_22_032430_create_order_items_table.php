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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('product_line_id')->nullable();
            $table->text('itemOrderId');
            $table->text('itemName');
            $table->string('skuNumber')->nullable();
            $table->integer('discountAmount')->default(0);
            $table->integer('originalPrice')->default(0);
            $table->integer('price')->default(0);
            $table->integer('sellPrice')->default(0);
            $table->integer('onHandStock')->default(0);
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
