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
            $table->foreignId('shipping_company_id')->nullable()->constrained('shipping_companies')->cascadeOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->cascadeOnDelete();
            $table->decimal('shipping_cost', 15, 2)->nullable();
            $table->decimal('discount', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
