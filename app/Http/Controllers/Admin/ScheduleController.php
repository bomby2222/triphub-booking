<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivitySchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * หน้ารายการรอบเดินทางทั้งหมดของทริปนั้นๆ
     */
    public function index($activityId)
    {
        $activity = Activity::with(['schedules' => function ($q) {
            $q->orderBy('start_date', 'asc');
        }])->findOrFail($activityId);

        return view('admin.schedules.index', compact('activity'));
    }

    /**
     * สลับสถานะรอบเดินทาง (เปิดรับจอง <-> ปิดบริการ)
     */
    public function toggleStatus($id)
    {
        $schedule = ActivitySchedule::findOrFail($id);

        // สลับระหว่าง open กับ closed
        $schedule->status = ($schedule->status === 'closed') ? 'open' : 'closed';
        $schedule->save();

        $statusText = $schedule->status === 'closed' ? 'ปิดบริการรอบนี้แล้ว' : 'เปิดรับจองรอบนี้ตามปกติแล้ว';
        return redirect()->back()->with('success', "{$statusText} ({$schedule->start_date->format('d/m/Y')})");
    }

    /**
     * อัปเดตสถานะโดยตรง (เช่น ปรับเป็น 'full' หรือปรับจำนวนที่นั่งว่าง)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,full,closed',
            'available_seats' => 'required|integer|min:0',
        ]);

        $schedule = ActivitySchedule::findOrFail($id);
        $schedule->update([
            'status' => $request->status,
            'available_seats' => $request->available_seats,
        ]);

        return redirect()->back()->with('success', 'บันทึกสถานะรอบเดินทางเรียบร้อยแล้ว');
    }

    /**
     * แอดมินเพิ่มรอบเดินทางใหม่
     */
    public function store(Request $request, $activityId)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_seats' => 'required|integer|min:1',
            'price_override' => 'nullable|numeric|min:0',
        ]);

        ActivitySchedule::create([
            'activity_id' => $activityId,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_seats' => $request->total_seats,
            'available_seats' => $request->total_seats,
            'price_override' => $request->price_override ?: null,
            'status' => 'open',
        ]);

        return redirect()->back()->with('success', 'เพิ่มรอบเดินทางใหม่สำเร็จ');
    }
}