<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel ini menyimpan header/informasi utama dari setiap order/transaksi
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Order ID yang unik (untuk ditampilkan ke user)
            $table->string('order_id', 100)->unique();

            // Relasi ke customer
            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->onDelete('cascade');

            // Total agregat dari semua items
            $table->integer('total_points')->default(0)->comment('Total poin dari semua item');
            $table->integer('total_items')->default(0)->comment('Total jumlah barang (sum qty)');

            // Catatan tambahan (opsional)
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes untuk performa query
            $table->index('order_id');
            $table->index(['customer_id', 'created_at']);
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
