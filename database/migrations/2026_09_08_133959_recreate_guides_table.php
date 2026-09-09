<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ปิดการตรวจ Foreign Key ชั่วคราวเพื่ออนุญาตให้แก้ไขตารางแม่ได้
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('guides');

        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->string('guide_code', 50)->unique();
            $table->string('name');
            $table->string('phone', 50);
            $table->string('location_area');
            $table->string('password');
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_no', 50)->nullable();
            $table->string('bank_account_name', 150)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'guide_status')) {
                $table->string('guide_status', 50)->default('unassigned');
            }
            if (!Schema::hasColumn('bookings', 'report_meet_photo')) {
                $table->string('report_meet_photo')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'report_start_photo')) {
                $table->string('report_start_photo')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'report_end_photo')) {
                $table->string('report_end_photo')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'report_notes')) {
                $table->text('report_notes')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'report_submitted_at')) {
                $table->timestamp('report_submitted_at')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'guide_paid_at')) {
                $table->timestamp('guide_paid_at')->nullable();
            }
        });

        // 2. เปิดการตรวจสอบ Foreign Key กลับคืนระบบ
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('guides');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};