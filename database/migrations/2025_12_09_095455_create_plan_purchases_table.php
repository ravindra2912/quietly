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
        Schema::create('plan_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->integer('duration_in_month');
            $table->dateTime('start_at');
            $table->dateTime('expired_at');
            $table->json('plan_info'); // Store plan details at purchase time
            $table->decimal('price', 10, 2);
            $table->json('payment_details')->nullable(); // Store payment gateway response
            $table->enum('status', ['pending', 'active', 'in-active', 'expired', 'override'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_purchases');
    }
};
