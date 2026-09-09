<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->longText('description')->nullable();
            $table->string('location');
            $table->string('province', 100);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('difficulty_level', ['easy', 'medium', 'hard', 'extreme'])->default('medium');
            $table->string('duration_text', 100)->nullable(); // e.g. "2 วัน 1 คืน"
            $table->decimal('distance_km', 6, 2)->nullable();
            $table->integer('altitude_meters')->nullable();
            $table->string('suitable_season', 100)->nullable(); // e.g. "พ.ย. - ก.พ."
            $table->string('meeting_point')->nullable();
            $table->time('departure_time')->nullable();
            $table->time('return_time')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->enum('badge', ['HOT', 'NEW', 'FULL', 'PROMOTION', 'NONE'])->default('NONE');
            $table->text('rules')->nullable();
            $table->text('cautions')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });

        Schema::create('activity_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('activity_includes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->string('item_name'); // e.g. ค่าไกด์, ค่าเข้าอุทยาน, อาหาร 3 มื้อ
            $table->timestamps();
        });

        Schema::create('activity_excludes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->string('item_name'); // e.g. ค่าเดินทางมายังจุดนัดพบ, อุปกรณ์ส่วนตัว
            $table->timestamps();
        });

        Schema::create('activity_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->integer('day_number')->default(1);
            $table->time('time_slot')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_itineraries');
        Schema::dropIfExists('activity_excludes');
        Schema::dropIfExists('activity_includes');
        Schema::dropIfExists('activity_images');
        Schema::dropIfExists('activities');
    }
};
