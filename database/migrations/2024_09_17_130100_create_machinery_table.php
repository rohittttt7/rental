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
        Schema::create('machinery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 12, 2); // Selling price
            $table->decimal('daily_rate', 8, 2)->nullable(); // Daily rental rate
            $table->decimal('weekly_rate', 8, 2)->nullable(); // Weekly rental rate  
            $table->decimal('monthly_rate', 8, 2)->nullable(); // Monthly rental rate
            $table->enum('condition', ['new', 'used', 'refurbished'])->default('used');
            $table->enum('availability_type', ['sale', 'rent', 'both'])->default('both');
            $table->boolean('is_available')->default(true);
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->year('year')->nullable();
            $table->string('fuel_type')->nullable(); // diesel, electric, petrol, hybrid
            $table->text('specifications')->nullable(); // JSON field for specs
            $table->text('images')->nullable(); // JSON field for image paths
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('view_count')->default(0);
            $table->enum('status', ['active', 'inactive', 'pending', 'sold'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machinery');
    }
};