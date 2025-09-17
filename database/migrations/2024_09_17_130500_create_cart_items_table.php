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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('machinery_id')->constrained('machinery')->onDelete('cascade');
            $table->enum('type', ['purchase', 'rental']);
            $table->integer('quantity')->default(1);
            $table->date('rental_start_date')->nullable(); // For rental items
            $table->date('rental_end_date')->nullable(); // For rental items
            $table->integer('rental_days')->nullable(); // For rental items
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};