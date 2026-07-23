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
        Schema::table('shifts', function (Blueprint $table) {
            $table->integer('actual_cups')->default(0)->after('target_foods');
            $table->integer('actual_foods')->default(0)->after('actual_cups');
            $table->text('close_notes')->nullable()->after('actual_foods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn(['actual_cups', 'actual_foods', 'close_notes']);
        });
    }
};
