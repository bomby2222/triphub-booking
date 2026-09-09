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
            'schedules' => 'nullable|array',
            'schedules.*.start_date' => 'nullable|date',
            'schedules.*.end_date' => 'nullable|date|after_or_equal:schedules.*.start_date',
            'schedules.*.total_seats' => 'nullable|integer|min:1',
            'schedules.*.price' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            // 1. บันทึกข้อมูลทริป (รวม category)
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
                'is_active' => true,
            ]);

            // 2. บันทึกรอบเดินทางลงตาราง activity_schedules ทันที
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
     * ลบข้อมูลทริปและรอบเดินทาง (ป้องกัน Foreign Key Constraint Error กรณีมีประวัติการจอง)
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        
        // ดึง ID ของรอบเดินทางทั้งหมดในทริปนี้
        $scheduleIds = $activity->schedules()->pluck('id');

        // ตรวจสอบว่ามีข้อมูลการจองของลูกค้าผูกอยู่กับรอบเดินทางเหล่านี้หรือไม่ (ใช้คอลัมน์ activity_schedule_id)
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