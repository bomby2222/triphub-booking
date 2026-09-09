<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentVerificationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'under_review');

        $query = Payment::with(['booking.user', 'booking.schedule.activity']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments', 'status'));
    }

    /**
     * อนุมัติการชำระเงินด้วยมือ (Manual Approve)
     */
    public function approve($id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        // ใช้สถานะ 'approved' สำหรับ Payment และ 'confirmed' สำหรับ Booking
        $payment->update([
            'status' => 'approved',
            'verified_at' => now(),
        ]);

        if ($payment->booking) {
            $payment->booking->update([
                'status' => 'confirmed',
            ]);
        }

        return redirect()->back()->with('success', 'อนุมัติยอดชำระเงินและยืนยันที่นั่งเรียบร้อยแล้ว');
    }

    /**
     * ปฏิเสธหลักฐานสลิป (Reject)
     */
    public function reject(Request $request, $id)
    {
        $payment = Payment::with('booking')->findOrFail($id);
        $reason = $request->input('reason', 'หลักฐานไม่ถูกต้อง หรือยอดเงินไม่ตรงกับระบบ');

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        if ($payment->booking) {
            $payment->booking->update([
                'status' => 'pending',
            ]);
        }

        return redirect()->back()->with('success', 'ปฏิเสธหลักฐานการโอนเงินเรียบร้อยแล้ว');
    }
}