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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->decimal('discount_per_line', 14, 4)->nullable();
            $table->decimal('tax_amount', 14, 4)->nullable();
            $table->decimal('discount_amount', 14, 4)->nullable();
            $table->decimal('discount_percentage', 14, 4)->nullable();
            $table->decimal('deduction_amount', 14, 4)->nullable();
            $table->decimal('shipping_cost', 14, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
