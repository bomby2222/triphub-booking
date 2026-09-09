<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->enum('payment_method', ['bank_transfer', 'qr_promptpay', 'credit_card'])->default('qr_promptpay');
            $table->decimal('amount', 10, 2);
            $table->string('slip_image')->nullable();
            $table->timestamp('transferred_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
