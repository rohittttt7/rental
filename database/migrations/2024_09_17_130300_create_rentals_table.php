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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('rental_number')->unique();
            $table->foreignId('renter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('machinery_id')->constrained('machinery')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('rental_days');
            $table->decimal('daily_rate', 8, 2);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('security_deposit', 8, 2)->default(0);
            $table->enum('status', ['booked', 'ongoing', 'completed', 'cancelled', 'overdue'])->default('booked');
            $table->text('pickup_address')->nullable();
            $table->text('delivery_address')->nullable();
            $table->timestamp('pickup_scheduled_at')->nullable();
            $table->timestamp('delivery_scheduled_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->text('pickup_notes')->nullable();
            $table->text('return_notes')->nullable();
            $table->boolean('is_extended')->default(false);
            $table->text('extension_details')->nullable(); // JSON for extension history
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};