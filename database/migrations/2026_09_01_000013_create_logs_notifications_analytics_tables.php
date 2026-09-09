<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->string('action'); // e.g. "แก้ไขกิจกรรม", "ยืนยันการชำระเงิน"
            $table->string('target_type')->nullable(); // e.g. "Activity", "Booking"
            $table->unsignedBigInteger('target_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['admin_id', 'created_at']);
        });

        Schema::create('website_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('device_type', 50)->nullable(); // Desktop, Mobile, Tablet
            $table->string('browser', 100)->nullable();
            $table->string('platform', 100)->nullable();
            $table->date('visited_date');
            $table->timestamp('last_active_at')->useCurrent();
            $table->timestamps();

            $table->index(['visited_date', 'ip_address']);
        });

        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->nullable()->constrained('website_visitors')->onDelete('cascade');
            $table->string('url_path');
            $table->string('page_title')->nullable();
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();

            $table->index(['url_path', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('website_visitors');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('notifications');
    }
};
