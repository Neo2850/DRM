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
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // FK to customers
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // FK to products
            $table->timestamp('added_at')->useCurrent(); // Default to current timestamp
            $table->timestamps(); // Created at and Updated at

            // Ensure no duplicate products in the wishlist for the same customer
            $table->unique(['customer_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
    }
};
