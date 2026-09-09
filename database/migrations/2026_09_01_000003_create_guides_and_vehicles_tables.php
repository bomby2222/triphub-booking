<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->string('name');
            $table->string('nickname', 50)->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone', 20);
            $table->text('bio')->nullable();
            $table->integer('experience_years')->default(1);
            $table->string('license_number', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_type'); // e.g. Van, 4x4 Truck, Bus
            $table->string('license_plate', 50)->unique();
            $table->integer('capacity')->default(10);
            $table->string('driver_name', 100)->nullable();
            $table->string('driver_phone', 20)->nullable();
            $table->enum('status', ['available', 'maintenance', 'in_use'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('guides');
    }
};
