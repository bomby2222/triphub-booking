<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_seats')->default(20);
            $table->integer('available_seats')->default(20);
            $table->decimal('price_override', 10, 2)->nullable();
            $table->enum('status', ['open', 'full', 'closed', 'cancelled'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['activity_id', 'start_date', 'status']);
        });

        Schema::create('schedule_guides', function (Blueprint $table) {
            $table->foreignId('activity_schedule_id')->constrained('activity_schedules')->onDelete('cascade');
            $table->foreignId('guide_id')->constrained('guides')->onDelete('cascade');
            $table->primary(['activity_schedule_id', 'guide_id']);
        });

        Schema::create('schedule_vehicles', function (Blueprint $table) {
            $table->foreignId('activity_schedule_id')->constrained('activity_schedules')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->primary(['activity_schedule_id', 'vehicle_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_vehicles');
        Schema::dropIfExists('schedule_guides');
        Schema::dropIfExists('activity_schedules');
    }
};
