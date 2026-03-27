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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('listing_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->nullable();
            $table->string('buyer_name')->nullable();
            $table->decimal('sale_price', 10, 2)->default(0);
            $table->unsignedInteger('quantity')->default(1);
            $table->string('fulfillment_status')->default('pending');
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->string('tracking_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'fulfillment_status']);
            $table->index(['product_id', 'fulfillment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
