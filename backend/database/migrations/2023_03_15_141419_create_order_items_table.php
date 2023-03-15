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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('item_id')->constrained();
            $table->string('title');
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->string('currency_id');
            $table->decimal('full_unit_price', 8, 2);
            $table->integer('variation_id')->nullable();
            $table->string('variation_color')->nullable();
            $table->string('variation_size')->nullable();
            $table->timestamps();
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