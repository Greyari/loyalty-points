<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel ini menyimpan detail item/produk dari setiap order
     * Satu order bisa punya banyak items (relasi 1:N)
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Relasi ke order (parent)
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade'); // Jika order dihapus, items ikut terhapus

            // Relasi ke product
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');

            // Snapshot data produk saat transaksi (untuk historis)
            $table->string('sku', 100);
            $table->string('product_name', 255)->comment('Nama produk saat transaksi');

            // Quantity & Points
            $table->integer('qty')->default(1)->comment('Jumlah barang dibeli');
            $table->integer('points_per_unit')->default(0)->comment('Poin per unit saat transaksi');
            $table->integer('total_points')->default(0)->comment('qty * points_per_unit');

            $table->timestamps();

            // Indexes untuk performa query
            $table->index('order_id');
            $table->index('product_id');
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
