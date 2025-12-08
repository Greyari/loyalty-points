<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 100)->unique();

            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->onDelete('cascade');

            $table->integer('total_points')->default(0);
            $table->integer('total_items')->default(0);
            $table->text('notes')->nullable();
            $table->decimal('total_price', 15, 2)->default(0);

            $table->timestamps();

            // PENTING: Indexes untuk performa
            $table->index('order_id');
            $table->index(['customer_id', 'created_at']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
