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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('orderId')->unique();
            $table->string('orderSn')->nullable();
            $table->string('bookingSn')->nullable();
            $table->text('buyerNotes')->nullable();
            $table->string('cancelBy')->nullable();
            $table->boolean('codOrder')->default(0);
            $table->timestamp('createTime')->nullable();
            $table->string('customerEmail')->nullable();
            $table->string('customerName')->nullable();
            $table->string('customerPhone')->nullable();
            $table->integer('discount')->default(0);
            $table->boolean('hasPaid')->default(0);
            $table->boolean('includeTax')->default(0);
            $table->integer('insuranceCost')->default(0);
            $table->string('logisticStatus')->nullable();
            $table->string('orderStatusList')->nullable();
            $table->string('subOrderStatusList')->nullable();
            $table->string('platformOrderStatusList');
            $table->string('paymentMethod')->nullable();
            $table->string('platform')->nullable();
            $table->string('platformName')->nullable();
            $table->boolean('preOrder')->default(0);
            $table->string('storeId')->nullable();
            $table->string('storeName')->nullable();
            $table->integer('subTotal')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('totalPrice')->default(0);
            $table->integer('totalWeight')->default(0);
            $table->string('courier')->nullable();
            $table->timestamp('deliveryDeadline')->nullable();
            $table->text('shippingAddress')->nullable();
            $table->string('shippingArea')->nullable();
            $table->string('shippingCity')->nullable();
            $table->integer('shippingCost')->default(0);
            $table->string('shippingFullName')->nullable();
            $table->string('shippingPhone')->nullable();
            $table->string('shippingProvince')->nullable();
            $table->string('trackingNumber')->nullable();
            $table->string('shippingPostCode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
