<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_member_id')->constrained('booking_members')->onDelete('cascade');
            $table->foreignId('activity_schedule_id')->constrained('activity_schedules')->onDelete('cascade');
            $table->string('qr_token', 64)->unique();
            $table->enum('status', ['registered', 'checked_in', 'absent'])->default('registered');
            $table->foreignId('checked_in_by')->nullable()->constrained('guides')->onDelete('set null');
            $table->timestamp('checked_in_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};
