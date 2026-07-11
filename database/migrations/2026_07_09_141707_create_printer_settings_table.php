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
        Schema::create('printer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('printer_name')->nullable();
            $table->string('printer_address')->nullable();
            $table->string('connection_type')->default('bluetooth');
            $table->integer('paper_size')->default(58);
            $table->boolean('auto_print')->default(true);
            $table->boolean('print_customer_copy')->default(true);
            $table->boolean('print_kitchen_copy')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printer_settings');
    }
};
