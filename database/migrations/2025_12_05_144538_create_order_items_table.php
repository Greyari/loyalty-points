<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');

            $table->string('sku', 100);
            $table->string('product_name', 255);
            $table->integer('qty')->default(1);
            $table->integer('points_per_unit')->default(0);
            $table->integer('total_points')->default(0);

            // REMOVED: price_per_unit dan total_price

            $table->timestamps();

            // Indexes untuk performa
            $table->index('order_id');
            $table->index('product_id');
            $table->index(['product_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
