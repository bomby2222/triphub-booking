<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('activity_schedule_id')->constrained('activity_schedules')->onDelete('restrict');
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->onDelete('set null');
            $table->integer('seats_count');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'confirmed', 'rejected', 'cancelled', 'completed'])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->text('user_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['activity_schedule_id', 'status']);
        });

        Schema::create('booking_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('full_name');
            $table->string('id_card_or_passport', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->text('medical_conditions')->nullable(); // ข้อมูลสุขภาพ/แพ้อาหาร
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_members');
        Schema::dropIfExists('bookings');
    }
};
