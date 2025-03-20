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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique()->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('birthday')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->unsignedBigInteger('default_address_id')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('password');
            $table->timestamps();
        });

        // Add the foreign keys after both tables are created
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->foreign('default_address_id')->references('id')->on('customer_addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
