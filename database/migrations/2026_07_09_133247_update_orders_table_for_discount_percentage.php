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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('discount_type')->default('percentage')->after('subtotal');
            $table->decimal('discount_value', 15, 2)->default(0)->after('discount_type');
            $table->renameColumn('discount', 'discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('discount_amount', 'discount');
            $table->dropColumn(['discount_type', 'discount_value']);
        });
    }
};
