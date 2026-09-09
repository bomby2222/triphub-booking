<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BookingManagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        // เตรียม Relation ที่จะโหลด
        $relations = ['user', 'schedule.activity', 'members', 'guide', 'payments'];
        
        // ถ้า Booking Model มี Relation ชื่อ report หรือ guideReport ให้โหลดมาด้วย
        if (method_exists(Booking::class, 'report')) {
            $relations[] = 'report';
        } elseif (method_exists(Booking::class, 'guideReport')) {
            $relations[] = 'guideReport';
        }

        $bookings = Booking::with($relations)
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(12);

        // ดึงรายชื่อไกด์ที่พร้อมรับงาน
        $guides = Guide::query();
        if (Schema::hasColumn('guides', 'is_active')) {
            $guides->where('is_active', true);
        } elseif (Schema::hasColumn('guides', 'status')) {
            $guides->where('status', 'active');
        }
        $guides = $guides->get();

        return view('admin.bookings.index', compact('bookings', 'status', 'guides'));
    }

    /**
     * บันทึกการโยนงาน/มอบหมายงานให้ไกด์
     */
    public function assignGuide(Request $request, $id)
    {
        $request->validate([
            'guide_id' => 'required|exists:guides,id',
        ], [
            'guide_id.required' => 'กรุณาเลือกไกด์ที่ต้องการมอบหมายงาน',
            'guide_id.exists' => 'ไม่พบข้อมูลไกด์ที่เลือกในระบบ',
        ]);

        $booking = Booking::findOrFail($id);
        $guide = Guide::findOrFail($request->guide_id);

        $updateData = [
            'guide_id' => $guide->id,
        ];

        if (Schema::hasColumn('bookings', 'guide_status')) {
            $updateData['guide_status'] = 'assigned';
        }

        $booking->update($updateData);

        return redirect()->back()
            ->with('success', "มอบหมายงานทริป {$booking->booking_code} ให้ไกด์ {$guide->name} สำเร็จเรียบร้อย!");
    }
}