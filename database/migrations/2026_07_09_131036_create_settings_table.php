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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('My Store');
            $table->string('store_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('currency')->default('IDR');
            $table->boolean('tax_enabled')->default(true);
            $table->string('tax_type')->default('percentage'); // percentage or fixed
            $table->decimal('tax_value', 15, 2)->default(0);
            $table->boolean('service_enabled')->default(false);
            $table->decimal('service_value', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
