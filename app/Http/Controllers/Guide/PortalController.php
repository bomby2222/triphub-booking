<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PortalController extends Controller
{
    // หน้าล็อกอินของไกด์
    public function showLogin()
    {
        if (session()->has('guide_id')) {
            return redirect()->route('guide.jobs');
        }
        return view('guide.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'guide_code' => 'required',
            'password' => 'required',
        ]);

        $guide = Guide::where('guide_code', $request->guide_code)->where('is_active', true)->first();

        if ($guide && Hash::check($request->password, $guide->password)) {
            session(['guide_id' => $guide->id, 'guide_name' => $guide->name, 'guide_code' => $guide->guide_code]);
            return redirect()->route('guide.jobs')->with('success', 'เข้าสู่ระบบเรียบร้อย');
        }

        return redirect()->back()->with('error', 'รหัสไกด์หรือรหัสผ่านไม่ถูกต้อง');
    }

    public function logout()
    {
        session()->forget(['guide_id', 'guide_name', 'guide_code']);
        return redirect()->route('guide.login');
    }

    // รายการงานที่ได้รับมอบหมาย
    public function jobs()
    {
        $guideId = session('guide_id');
        $guide = Guide::findOrFail($guideId);

        $jobs = Booking::with(['schedule.activity', 'members', 'user'])
            ->where('guide_id', $guideId)
            ->latest()
            ->get();

        return view('guide.jobs', compact('guide', 'jobs'));
    }

    // รายละเอียดงาน & ฟอร์มส่งรายงาน 3 รูป
    public function jobDetail($id)
    {
        $guideId = session('guide_id');
        $job = Booking::with(['schedule.activity', 'members', 'user'])
            ->where('guide_id', $guideId)
            ->findOrFail($id);

        return view('guide.job_detail', compact('job'));
    }

    // อัปโหลดรายงาน 3 รูป (เจอลูกค้า, เริ่มเดินทาง, จบทริป)
    public function submitReport(Request $request, $id)
    {
        $guideId = session('guide_id');
        $job = Booking::where('guide_id', $guideId)->findOrFail($id);

        $request->validate([
            'photo_meet' => 'required|image|max:8192',
            'photo_start' => 'required|image|max:8192',
            'photo_end' => 'required|image|max:8192',
            'notes' => 'nullable|string',
        ], [
            'photo_meet.required' => 'กรุณาแนบรูปที่ 1: จุดนัดพบ/เจอลูกค้า',
            'photo_start.required' => 'กรุณาแนบรูปที่ 2: เริ่มออกเดินทาง/จุดปล่อยตัว',
            'photo_end.required' => 'กรุณาแนบรูปที่ 3: จบทริปส่งลูกทัวร์ปลอดภัย',
        ]);

        $pathMeet = $request->file('photo_meet')->store('guide_reports', 'public');
        $pathStart = $request->file('photo_start')->store('guide_reports', 'public');
        $pathEnd = $request->file('photo_end')->store('guide_reports', 'public');

        $job->update([
            'report_meet_photo' => $pathMeet,
            'report_start_photo' => $pathStart,
            'report_end_photo' => $pathEnd,
            'report_notes' => $request->notes,
            'report_submitted_at' => now(),
            'guide_status' => 'report_submitted', // ปรับสถานะเป็น: รอแอดมินโอนเงิน
        ]);

        return redirect()->route('guide.job_detail', $job->id)->with('success', 'ส่งรายงานภาพครบ 3 ขั้นตอนเรียบร้อยแล้ว อยู่ระหว่างรอแอดมินโอนเงินค่าจ้าง');
    }
}