<?php

namespace App\Http\Controllers;

use App\Models\ActivitySchedule;
use App\Models\Booking;
use App\Models\BookingMember;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Setting;
use App\Services\EasySlipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * 1. หน้ากรอกข้อมูลผู้ร่วมเดินทาง
     */
    public function create(Request $request)
    {
        $scheduleId = $request->query('schedule_id');
        $seatsCount = (int) $request->query('seats', 1);

        if (!$scheduleId) {
            return redirect()->route('home')->with('error', 'กรุณาเลือกรอบวันเดินทางที่ต้องการจอง');
        }

        $schedule = ActivitySchedule::with('activity')->findOrFail($scheduleId);

        if ($schedule->status === 'closed') {
            return redirect()->back()->with('error', 'ขออภัย รอบเดินทางนี้ปิดรับบริการแล้ว');
        }

        if ($schedule->available_seats < $seatsCount) {
            return redirect()->back()->with('error', 'ขออภัย จำนวนที่นั่งคงเหลือไม่เพียงพอสำหรับการจอง');
        }

        return view('bookings.create', compact('schedule', 'seatsCount'));
    }

    /**
     * 2. บันทึกข้อมูลการจอง พร้อม Concurrency Lock ป้องกันที่นั่งซ้อน
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:activity_schedules,id',
            'seats' => 'required|integer|min:1',
            'promo_code' => 'nullable|string',
            'members' => 'required|array|min:1',
            'members.*.full_name' => 'required|string|max:255',
            'members.*.phone' => 'required|string|max:20',
            'members.*.id_card' => 'nullable|string|max:50',
            'members.*.medical' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            // Lock แถวข้อมูล Schedule ป้องกัน Race Condition
            $schedule = ActivitySchedule::where('id', $request->schedule_id)
                ->lockForUpdate()
                ->firstOrFail();

            $seats = (int) $request->seats;

            if ($schedule->status === 'closed' || $schedule->available_seats < $seats) {
                return redirect()->back()->with('error', 'ขออภัย ที่นั่งในรอบนี้ถูกจองเต็มหรือปิดรับแล้ว');
            }

            // คำนวณราคา
            $unitPrice = $schedule->price_override ?? $schedule->activity->base_price;
            $subtotal = $unitPrice * $seats;
            $discount = 0;
            $promotionId = null;

            // ตรวจสอบโค้ดส่วนลด
            if ($request->filled('promo_code') && class_exists(Promotion::class)) {
                $promo = Promotion::where('code', strtoupper($request->promo_code))->first();
                if ($promo && method_exists($promo, 'isValidForAmount') && $promo->isValidForAmount($subtotal)) {
                    $discount = $promo->calculateDiscount($subtotal);
                    $promotionId = $promo->id;
                    $promo->increment('used_count');
                }
            }

            $netAmount = max(0, $subtotal - $discount);

            // ตัดสต็อกที่นั่งคงเหลือ
            $schedule->decrement('available_seats', $seats);
            if ($schedule->available_seats <= 0) {
                $schedule->update(['status' => 'full']);
            }

            // รองรับทั้งชื่อคอลัมน์ schedule_id และ activity_schedule_id
            $scheduleColumn = Schema::hasColumn('bookings', 'schedule_id') ? 'schedule_id' : 'activity_schedule_id';

            $bookingData = [
                'booking_code' => 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'user_id' => auth()->id() ?? 1,
                $scheduleColumn => $schedule->id,
                'promotion_id' => $promotionId,
                'seats_count' => $seats,
                'unit_price' => $unitPrice,
                'subtotal_amount' => $subtotal,
                'discount_amount' => $discount,
                'fee_amount' => 0,
                'net_amount' => $netAmount,
                'status' => 'pending',
                'expires_at' => now()->addMinutes(30),
                'user_notes' => $request->user_notes,
            ];

            if (Schema::hasColumn('bookings', 'schedule_id')) {
                $bookingData['schedule_id'] = $schedule->id;
            }
            if (Schema::hasColumn('bookings', 'activity_schedule_id')) {
                $bookingData['activity_schedule_id'] = $schedule->id;
            }

            $booking = Booking::create($bookingData);

            // บันทึกรายชื่อสมาชิกร่วมทริป
            foreach ($request->members as $member) {
                BookingMember::create([
                    'booking_id' => $booking->id,
                    'full_name' => $member['full_name'],
                    'phone' => $member['phone'],
                    'id_card_or_passport' => $member['id_card'] ?? null,
                    'medical_conditions' => $member['medical'] ?? null,
                ]);
            }

            return redirect()->route('bookings.payment', $booking->id);
        });
    }

    /**
     * 3. หน้าชำระเงินและแสดง QR Code ตามการตั้งค่าแอดมิน
     */
    public function payment($id)
    {
        $booking = Booking::with(['schedule.activity', 'members'])->findOrFail($id);

        $paymentSettings = [
            'promptpay_number' => Setting::get('promptpay_number', '0812345678'),
            'bank_name' => Setting::get('bank_name', 'ธนาคารกรุงเทพ (BBL)'),
            'bank_account_name' => Setting::get('bank_account_name', 'บจก. ไฮกิ้ง เนเจอร์ เจอร์นีย์'),
            'bank_account_number' => Setting::get('bank_account_number', '123-4-56789-0'),
        ];

        return view('bookings.payment', compact('booking', 'paymentSettings'));
    }

    /**
     * 4. ส่งสลิปตรวจกับ EasySlip ระบบจริง (ผ่าน = อนุมัติทันที / ไม่ผ่าน = แจ้งข้อผิดพลาดให้ส่งใหม่ ไม่ต้องรอแอดมิน)
     */
    public function submitPayment(Request $request, $id, EasySlipService $easySlipService)
    {
        $request->validate([
            'slip_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'slip_image.required' => 'กรุณาเลือกไฟล์ภาพสลิปการโอนเงิน',
            'slip_image.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
            'slip_image.max' => 'ขนาดไฟล์ภาพต้องไม่เกิน 5 MB',
        ]);

        $booking = Booking::findOrFail($id);

        // ดึงเบอร์พร้อมเพย์จากระบบเพื่อส่งไปตรวจบัญชีผู้รับเงิน
        $shopPromptPay = Setting::get('promptpay_number', '');

        // 1. ส่งตรวจกับระบบ EasySlip ตัวจริง
        $verification = $easySlipService->verifySlip(
            $request->file('slip_image'),
            (float) $booking->net_amount,
            $shopPromptPay
        );

        // 2. ถ้า EasySlip ตรวจสอบผ่าน
        if ($verification['success'] && $verification['status'] === 'approved') {
            $transRef = $verification['trans_ref'] ?? null;

            // ตรวจสอบสลิปซ้ำในระบบ (ป้องกันการนำสลิปเดิมมาใช้ใหม่)
            if ($transRef && Payment::where('notes', 'LIKE', "%{$transRef}%")->exists()) {
                return redirect()->route('bookings.payment', $booking->id)
                    ->with('error', 'สลิปใบนี้เคยถูกใช้งานในระบบแล้ว ไม่สามารถใช้ซ้ำได้');
            }

            // บันทึกไฟล์ภาพสลิปลง Storage
            $path = $request->file('slip_image')->store('slips', 'public');
            $imageColumn = Schema::hasColumn('payments', 'slip_image_url') ? 'slip_image_url' : 'slip_image';

            // บันทึกการชำระเงินและอนุมัติทันที
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'qr_promptpay',
                'amount' => $booking->net_amount,
                $imageColumn => $path,
                'status' => 'approved',
                'transferred_at' => now(),
                'verified_at' => now(),
                'notes' => 'อนุมัติอัตโนมัติ EasySlip Ref: ' . ($transRef ?? '-'),
            ]);

            // อัปเดตสถานะการจองเป็น confirmed ทันที (ออกตั๋วสีเขียวทันที)
            $booking->update([
                'status' => 'confirmed',
            ]);

            return redirect()->route('bookings.payment', $booking->id)
                ->with('success', 'สลิปถูกต้องครบถ้วน! ระบบยืนยันการจองทริปให้คุณเรียบร้อยแล้ว');
        }

        // 3. ถ้าตรวจสอบไม่ผ่าน (ยอดไม่ครบ, รูปไม่ชัด, ไม่มี mini-QR, หรือไม่ใช่สลิปโอนเงินจริง)
        // แสดงข้อความแจ้งเตือนข้อผิดพลาดทันที โดยไม่เปลี่ยนสถานะไปรอแอดมินตรวจ
        return redirect()->route('bookings.payment', $booking->id)
            ->with('error', $verification['message'] ?? 'รูปภาพสลิปไม่ถูกต้องหรือไม่พบ QR Code กรุณาตรวจสอบและแนบใหม่อีกครั้ง');
    }
}