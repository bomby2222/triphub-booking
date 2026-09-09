<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('guide_id')->nullable()->after('activity_schedule_id')->constrained('guides')->onDelete('set null');
            $table->timestamp('assigned_to_guide_at')->nullable()->after('guide_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['guide_id']);
            $table->dropColumn(['guide_id', 'assigned_to_guide_at']);
        });
    }
};