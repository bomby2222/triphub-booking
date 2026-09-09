<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class GuideController extends Controller
{
    public function index()
    {
        $guides = Guide::withCount(['bookings as active_jobs' => function ($q) {
            $q->whereIn('guide_status', ['assigned', 'report_submitted']);
        }])->latest()->paginate(15);

        return view('admin.guides.index', compact('guides'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guide_code' => 'required|string|max:50|unique:guides,guide_code',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'location_area' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'bank_name' => 'required|string|max:100',
            'bank_account_no' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:150',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        Guide::create($validated);

        return redirect()->back()->with('success', 'เพิ่มข้อมูลไกด์และสร้างรหัสผ่านเข้าใช้งานเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $guide = Guide::findOrFail($id);
        $guide->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลไกด์เรียบร้อยแล้ว');
    }

    // แอดมินกดยืนยันการโอนเงินค่าจ้างให้ไกด์หลังจบทริป
    public function markAsPaid($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        $updateData = [
            'guide_status' => 'completed',
        ];

        if (Schema::hasColumn('bookings', 'guide_payment_status')) {
            $updateData['guide_payment_status'] = 'paid';
        }

        if (Schema::hasColumn('bookings', 'guide_paid_at')) {
            $updateData['guide_paid_at'] = now();
        }

        $booking->update($updateData);

        return redirect()->back()->with('success', "บันทึกการโอนเงินค่าจ้างของทริป {$booking->booking_code} ให้ไกด์เรียบร้อยแล้ว");
    }
}