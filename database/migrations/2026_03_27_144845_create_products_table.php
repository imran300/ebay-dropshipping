<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('sku')->nullable();
            $table->string('category')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('source_url')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('target_price', 10, 2)->default(0);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('listing_status')->default('draft');
            $table->text('notes')->nullable();
            $table->timestamp('last_sold_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'listing_status']);
            $table->index(['user_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
