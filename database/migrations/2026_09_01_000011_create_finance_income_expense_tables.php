<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ค่าไกด์, ค่าอาหาร, ค่าเดินทาง/น้ำมัน, ค่าอุปกรณ์, ค่าที่พัก, ค่าโฆษณา, ค่าใช้จ่ายอื่น
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('income', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->foreignId('activity_schedule_id')->nullable()->constrained('activity_schedules')->onDelete('set null');
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->date('entry_date');
            $table->foreignId('recorded_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained('expense_categories')->onDelete('restrict');
            $table->foreignId('activity_schedule_id')->nullable()->constrained('activity_schedules')->onDelete('set null');
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->date('entry_date');
            $table->string('receipt_file')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('income');
        Schema::dropIfExists('expense_categories');
    }
};
