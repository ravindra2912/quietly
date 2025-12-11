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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('duration_in_month');
            $table->boolean('is_ad_free')->default(false);
            $table->string('group_active_timing'); // 1 to 24, custom
            $table->boolean('is_active_multiple_group')->default(false);
            $table->enum('plan_purchase_limit_per_user', ['unlimit', 'limited']);
            $table->integer('plan_purchase_limit')->nullable();
            $table->enum('status', ['active', 'in-active'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
