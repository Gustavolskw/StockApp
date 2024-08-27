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
        Schema::create('stock001', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id'); // Define the product_id column
            $table->foreign('product_id')->references('id_product')->on('products')->cascadeOnDelete();
            $table->bigInteger('amount');
            $table->primary('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock001s');
    }
};
