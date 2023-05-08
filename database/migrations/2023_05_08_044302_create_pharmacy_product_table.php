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
        Schema::create('pharmacy_product', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('pharmacy_id');
            $table->integer('total_amount')->default(0);
            $table->integer('customer_net');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_product');
    }
};
