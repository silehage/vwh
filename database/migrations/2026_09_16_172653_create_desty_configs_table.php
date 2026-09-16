<?php

use App\Models\DestyConfig;
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
        Schema::create('desty_configs', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('username');
            $table->string('email')->nullable();
            $table->string('mobile');
            $table->string('apply_id')->nullable();
            $table->string('token_type')->nullable();
            $table->text('access_token')->nullable();
            $table->text('last_token')->nullable();
            $table->bigInteger('token_expired_time')->default(0);
        });

        DestyConfig::create([
            'apply_id' => '9169d2d6-ec40-414f-9215-765fe0fecda4',
            'token_type' => 'Bearer',
            'username' => '+6282112083030',
            'mobile' => '+6282112083030',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desty_configs');
    }
};
