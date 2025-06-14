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
        // 1. Create master_pembelians table with purchase_code from the start
        Schema::create('master_pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_code')->unique();
            $table->double('total');
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Create pembelians table with proper relationships
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id');
            $table->decimal('purchase_price', 10, 2);
            $table->integer('quantity');
            $table->date('purchase_date');
            $table->string('purchase_code'); // Non-unique to allow multiple entries with same code
            $table->unsignedBigInteger('master_pembelians_id');
            $table->timestamps();
            $table->softDeletes();

            // Add foreign keys
            $table->foreign('stock_id')
                  ->references('id')
                  ->on('stocks')
                  ->onDelete('cascade');

            $table->foreign('master_pembelians_id')
                  ->references('id')
                  ->on('master_pembelians')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to handle foreign key constraints
        Schema::dropIfExists('pembelians');
        Schema::dropIfExists('master_pembelians');
    }
};
