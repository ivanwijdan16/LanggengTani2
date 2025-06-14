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
        // 1. Create master_stocks table first
        Schema::create('master_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['Obat', 'Pupuk', 'Bibit']);
            $table->string('sub_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Create stocks table with proper relationships
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_stock_id');
            $table->text('size'); // Using text instead of enum to allow more flexibility
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->integer('quantity');
            $table->integer('retail_quantity')->nullable();
            $table->decimal('retail_price', 10, 2)->nullable();
            $table->date('expiration_date');
            $table->string('stock_id')->unique();
            $table->softDeletes(); // Include soft deletes from the beginning
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('master_stock_id')
                  ->references('id')
                  ->on('master_stocks')
                  ->onDelete('cascade');
        });

        // 3. Create stock_size_images table
        Schema::create('stock_size_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_stock_id');
            $table->string('size');
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('master_stock_id')
                  ->references('id')
                  ->on('master_stocks')
                  ->onDelete('cascade');

            // Each master_stock_id and size combination should be unique
            $table->unique(['master_stock_id', 'size']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to handle foreign key constraints
        Schema::dropIfExists('stock_size_images');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('master_stocks');
    }
};
