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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // FK to orders table
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // FK to products table
            $table->integer('quantity'); // Quantity of the product
            $table->decimal('unit_amount', 10, 2); // Price per unit
            $table->decimal('total_amount', 10, 2); // Total amount for this item (quantity × unit_amount)
            $table->timestamps(); // Created at & Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
