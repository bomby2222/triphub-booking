<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('discount_type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('discount_value', 10, 2);
            $table->decimal('min_spend', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable(); // for percent discount cap
            $table->integer('quota')->default(100);
            $table->integer('used_count')->default(0);
            $table->integer('limit_per_user')->default(1);
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('activity_id')->nullable()->constrained('activities')->onDelete('set null'); // specific trip
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('promotion_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('booking_id');
            $table->decimal('discount_applied', 10, 2);
            $table->timestamp('used_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_usages');
        Schema::dropIfExists('promotions');
    }
};
