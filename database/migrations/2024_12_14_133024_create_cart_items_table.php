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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade'); // FK for logged-in users
            $table->string('session_id', 100)->nullable(); // Session identifier for guests
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // FK to products
            $table->integer('quantity')->default(1); // Default to 1 item
            $table->decimal('price', 10, 2); // Unit price at the time of addition
            $table->boolean('selected')->default(true);
            $table->timestamps(); // Created at and Updated at

            // Ensure either customer_id or session_id is used
            $table->unique(['customer_id', 'product_id']);
            $table->unique(['session_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
