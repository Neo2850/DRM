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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('reference_number')->unique(); // Unique order reference
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // FK to customers table
            $table->decimal('total_amount', 10, 2); // Total order amount
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending'); // Payment status
            $table->enum('payment_method', ['gcash'])->default('gcash'); // Payment method
            $table->string('gcash_transaction_id')->nullable(); // GCash transaction ID
            $table->enum('shipping_method', ['delivery', 'pickup'])->default('delivery'); // Shipping method
            $table->string('tracking_number')->nullable(); // Nullable for pickup orders
            $table->dateTime('pickup_date_time')->nullable(); // Nullable for delivery orders
            $table->dateTime('estimated_delivery')->nullable(); // Nullable for pickup orders
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'])->default('pending'); // Order status
            $table->enum('return_refund_status', ['none', 'requested', 'approved', 'refunded'])->default('none'); // Return or refund status
            $table->decimal('shipping_amount', 10, 2)->nullable(); // Nullable for pickup
            $table->text('notes')->nullable(); // Optional notes
            $table->string('shipping_address')->nullable(); // Nullable for pickup
            $table->string('e_invoice')->nullable(); // Nullable for pickup
            $table->timestamps(); // Created at & Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
