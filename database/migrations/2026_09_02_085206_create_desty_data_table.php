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
        Schema::create('desty_data', function (Blueprint $table) {
            $table->id();
            $table->string('orderId')->index()->unique();
            $table->string('orderType');
            $table->dateTime('orderCreateTime')->nullable();
            $table->dateTime('orderUpdateTime')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->unsignedInteger('reserved_at')->nullable();
            $table->json('payload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desty_data');
    }
};
