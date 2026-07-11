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
            $table->renameColumn('discount_amount', 'discount');
            $table->renameColumn('tax_amount', 'tax');
            $table->dropColumn('tax_percentage');

            $table->decimal('subtotal', 15, 2)->default(0)->after('shift_id');
            $table->decimal('service_charge', 15, 2)->default(0)->after('tax');
            $table->decimal('change_amount', 15, 2)->default(0)->after('amount_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('discount', 'discount_amount');
            $table->renameColumn('tax', 'tax_amount');
            $table->decimal('tax_percentage', 5, 2)->default(0)->after('total_amount');

            $table->dropColumn(['subtotal', 'service_charge', 'change_amount']);
        });
    }
};
