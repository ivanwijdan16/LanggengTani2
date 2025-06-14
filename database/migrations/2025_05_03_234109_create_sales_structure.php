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
        // 1. Create transactions table with id_penjualan from the start
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->double('total_price');
            $table->double('total_paid');
            $table->double('change');
            $table->string('id_penjualan')->unique(); // Include this from the start
            $table->timestamps();
        });

        // 2. Create transaction_items table
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('product_id'); // Match with stocks.id
            $table->integer('quantity');
            $table->double('price');
            $table->double('subtotal');
            $table->timestamps();
        });

        // 3. Create carts table
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('product_id'); // Match with stocks.id
            $table->integer('quantity');
            $table->double('subtotal');
            $table->string('type')->default('normal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to handle foreign key constraints
        Schema::dropIfExists('carts');
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
    }
};
