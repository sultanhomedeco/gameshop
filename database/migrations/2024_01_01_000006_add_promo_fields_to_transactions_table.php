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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('original_amount', 10, 2)->nullable()->after('amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('original_amount');
            $table->string('promo_code')->nullable()->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['original_amount', 'discount_amount', 'promo_code']);
        });
    }
}; 