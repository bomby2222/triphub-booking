<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guide;
use Illuminate\Http\Request;

class GuideAssignmentController extends Controller
{
    /**
     * แอดมินบันทึกการส่งต่องานให้ไกด์
     */
    public function assign(Request $request, $bookingId)
    {
        $request->validate([
            'guide_id' => 'required|exists:guides,id',
        ]);

        $booking = Booking::with(['schedule.activity', 'members'])->findOrFail($bookingId);
        $guide = Guide::findOrFail($request->guide_id);

        $booking->update([
            'guide_id' => $guide->id,
            'assigned_to_guide_at' => now(),
        ]);

        return redirect()->back()->with('success', "ส่งต่อข้อมูลทริปให้ไกด์ '{$guide->name}' เรียบร้อยแล้ว");
    }

    /**
     * หน้า Portal สำหรับไกด์ดูงานและรายชื่อลูกทัวร์
     */
    public function guidePortal(Request $request)
    {
        // จำลองดูงานของไกด์คนแรก หรือไกด์ที่เลือก
        $guides = Guide::where('is_active', true)->get();
        $currentGuideId = $request->query('guide_id', $guides->first()->id ?? 1);
        $currentGuide = Guide::find($currentGuideId);

        $assignedBookings = Booking::with(['schedule.activity', 'members', 'user'])
            ->where('guide_id', $currentGuideId)
            ->latest()
            ->get();

        return view('guide.portal', compact('guides', 'currentGuide', 'assignedBookings'));
    }
}