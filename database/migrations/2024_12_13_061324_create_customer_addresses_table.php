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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number');
            $table->string('province');
            $table->string('city');
            $table->string('zip_code');
            $table->text('complete_address');
            $table->boolean('default_address')->default(false);
            $table->timestamps();

            // Index for faster lookups
            $table->index('customer_id');
            $table->index('default_address');
        });


    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
