<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivitySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::withCount('schedules')->latest()->paginate(15);
        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        return view('admin.activities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'difficulty_level' => 'required|in:easy,medium,hard,extreme',
            'duration_text' => 'required|string|max:100',
            'base_price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|url',
            'description' => 'required|string',
            'distance_km' => 'nullable|numeric|min:0',
            'altitude_meters' => 'nullable|integer|min:0',
            'suitable_season' => 'nullable|string|max:150',
            'schedules' => 'nullable|array',
            'schedules.*.start_date' => 'nullable|date',
            'schedules.*.end_date' => 'nullable|date|after_or_equal:schedules.*.start_date',
            'schedules.*.total_seats' => 'nullable|integer|min:1',
            'schedules.*.price' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            // 1. บันทึกข้อมูลทริปหลักพร้อมสเปกเส้นทาง
            $activity = Activity::create([
                'name' => $validated['name'],
                'category' => $validated['category'],
                'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
                'location' => $validated['location'],
                'province' => $validated['province'],
                'difficulty_level' => $validated['difficulty_level'],
                'duration_text' => $validated['duration_text'],
                'base_price' => $validated['base_price'],
                'cover_image' => $validated['cover_image'] ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=1920&q=85',
                'description' => $validated['description'],
                'distance_km' => $validated['distance_km'] ?? null,
                'altitude_meters' => $validated['altitude_meters'] ?? null,
                'suitable_season' => $validated['suitable_season'] ?? 'ตลอดทั้งปี',
                'is_active' => true,
            ]);

            // 2. บันทึกรอบเดินทางลงตาราง activity_schedules
            if ($request->has('schedules')) {
                foreach ($request->input('schedules') as $sched) {
                    if (!empty($sched['start_date']) && !empty($sched['end_date'])) {
                        $totalSeats = (int) ($sched['total_seats'] ?? 20);
                        ActivitySchedule::create([
                            'activity_id' => $activity->id,
                            'start_date' => $sched['start_date'],
                            'end_date' => $sched['end_date'],
                            'total_seats' => $totalSeats,
                            'available_seats' => $totalSeats,
                            'price_override' => !empty($sched['price']) ? (float) $sched['price'] : null,
                            'status' => 'open',
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.activities.index')->with('success', 'บันทึกทริปใหม่และเปิดรอบวันเดินทางเรียบร้อยแล้ว!');
    }

    /**
     * แสดงแบบฟอร์มแก้ไขข้อมูลทริป
     */
    public function edit($id)
    {
        $activity = Activity::with('schedules')->findOrFail($id);
        return view('admin.activities.edit', compact('activity'));
    }

    /**
     * บันทึกการอัปเดตข้อมูลทริปและรอบเดินทาง
     */
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'difficulty_level' => 'required|in:easy,medium,hard,extreme',
            'duration_text' => 'required|string|max:100',
            'base_price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|url',
            'description' => 'required|string',
            'distance_km' => 'nullable|numeric|min:0',
            'altitude_meters' => 'nullable|integer|min:0',
            'suitable_season' => 'nullable|string|max:150',
            'is_active' => 'nullable',
            'schedules' => 'nullable|array',
            'schedules.*.id' => 'nullable|integer',
            'schedules.*.start_date' => 'nullable|date',
            'schedules.*.end_date' => 'nullable|date|after_or_equal:schedules.*.start_date',
            'schedules.*.total_seats' => 'nullable|integer|min:1',
            'schedules.*.price' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($activity, $validated, $request) {
            // 1. อัปเดตข้อมูลทริปหลักและสเปกเส้นทาง
            $activity->update([
                'name' => $validated['name'],
                'category' => $validated['category'],
                'location' => $validated['location'],
                'province' => $validated['province'],
                'difficulty_level' => $validated['difficulty_level'],
                'duration_text' => $validated['duration_text'],
                'base_price' => $validated['base_price'],
                'cover_image' => $validated['cover_image'] ?? $activity->cover_image,
                'description' => $validated['description'],
                'distance_km' => $validated['distance_km'] ?? null,
                'altitude_meters' => $validated['altitude_meters'] ?? null,
                'suitable_season' => $validated['suitable_season'] ?? 'ตลอดทั้งปี',
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            // 2. ซิงค์รอบเดินทาง (สร้างใหม่ / แก้ไขรอบเดิม / ลบรอบที่ถูกนำออก)
            if ($request->has('schedules')) {
                $submittedIds = [];

                foreach ($request->input('schedules') as $sched) {
                    if (!empty($sched['start_date']) && !empty($sched['end_date'])) {
                        $totalSeats = (int) ($sched['total_seats'] ?? 20);
                        $price = !empty($sched['price']) ? (float) $sched['price'] : null;

                        if (!empty($sched['id'])) {
                            // อัปเดตรอบเดิม
                            $existingSchedule = ActivitySchedule::where('activity_id', $activity->id)
                                ->where('id', $sched['id'])
                                ->first();

                            if ($existingSchedule) {
                                $existingSchedule->update([
                                    'start_date' => $sched['start_date'],
                                    'end_date' => $sched['end_date'],
                                    'total_seats' => $totalSeats,
                                    'price_override' => $price,
                                ]);
                                $submittedIds[] = $existingSchedule->id;
                            }
                        } else {
                            // เพิ่มรอบใหม่
                            $newSchedule = ActivitySchedule::create([
                                'activity_id' => $activity->id,
                                'start_date' => $sched['start_date'],
                                'end_date' => $sched['end_date'],
                                'total_seats' => $totalSeats,
                                'available_seats' => $totalSeats,
                                'price_override' => $price,
                                'status' => 'open',
                            ]);
                            $submittedIds[] = $newSchedule->id;
                        }
                    }
                }

                // ลบรอบที่นำออก (ยกเว้นรอบที่มีคนจองแล้ว)
                $deleteQuery = $activity->schedules()->whereNotIn('id', $submittedIds);
                if (Schema::hasTable('bookings')) {
                    $deleteQuery->whereDoesntHave('bookings');
                }
                $deleteQuery->delete();
            }
        });

        return redirect()->route('admin.activities.index')->with('success', 'อัปเดตข้อมูลทริปและรอบเดินทางเรียบร้อยแล้ว!');
    }

    /**
     * ลบข้อมูลทริปและรอบเดินทาง
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        
        $scheduleIds = $activity->schedules()->pluck('id');

        $hasBookings = false;
        if (Schema::hasTable('bookings') && count($scheduleIds) > 0) {
            $hasBookings = DB::table('bookings')->whereIn('activity_schedule_id', $scheduleIds)->exists();
        }

        if ($hasBookings) {
            return redirect()->route('admin.activities.index')
                ->with('error', '❌ ไม่สามารถลบทริปนี้ได้ เนื่องจากมีประวัติการจองของลูกค้าอยู่ในระบบแล้ว');
        }

        DB::transaction(function () use ($activity) {
            $activity->schedules()->delete();
            $activity->delete();
        });

        return redirect()->route('admin.activities.index')
            ->with('success', 'ลบข้อมูลทริปและรอบเดินทางที่เกี่ยวข้องเรียบร้อยแล้ว');
    }
}