@extends('layouts.admin')

@section('title', 'ตรวจสอบการชำระเงิน | Finance')
@section('page_title', '💳 ตรวจสอบหลักฐานการโอนเงิน (Finance Verification)')

@section('admin_content')
<div class="space-y-6" x-data="{
    modalOpen: false,
    selectedImage: '',
    selectedBooking: '',
    selectedAmount: '',
    rejectModalOpen: false,
    rejectPaymentId: null,
    openSlip(img, code, amount) {
        this.selectedImage = img;
        this.selectedBooking = code;
        this.selectedAmount = amount;
        this.modalOpen = true;
    },
    openReject(id) {
        this.rejectPaymentId = id;
        this.rejectModalOpen = true;
    }
}">

    <!-- Filter Tabs -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.payments.index', ['status' => 'under_review']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'under_review' ? 'bg-amber-500 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            ⏳ รอตรวจสอบ (Under Review)
        </a>
        <a href="{{ route('admin.payments.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            ✅ อนุมัติแล้ว (Approved)
        </a>
        <a href="{{ route('admin.payments.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'rejected' ? 'bg-red-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            ❌ ปฏิเสธแล้ว (Rejected)
        </a>
        <a href="{{ route('admin.payments.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'all' ? 'bg-nature-deep text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            ทั้งหมด
        </a>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-xs uppercase text-gray-400">
                        <th class="pb-3">รหัสการจอง</th>
                        <th class="pb-3">ผู้ชำระเงิน</th>
                        <th class="pb-3">ทริป</th>
                        <th class="pb-3">ยอดที่ต้องโอน</th>
                        <th class="pb-3">เวลาที่แนบสลิป</th>
                        <th class="pb-3">หลักฐาน (Slip)</th>
                        <th class="pb-3">สถานะ</th>
                        <th class="pb-3 text-right">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payments as $p)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-4 font-mono font-bold text-nature-deep">{{ $p->booking->booking_code }}</td>
                            <td class="py-4 font-medium">{{ $p->booking->user->name ?? 'ผู้ใช้งาน' }}</td>
                            <td class="py-4 text-xs">{{ $p->booking->schedule->activity->name ?? '-' }}</td>
                            <td class="py-4 font-bold text-nature-forest text-base">฿{{ number_format($p->amount, 2) }}</td>
                            <td class="py-4 text-xs text-gray-500">{{ $p->created_at->format('d/m/Y H:i') }} น.</td>
                            <td class="py-4">
                                @if($p->slip_image)
                                    <button @click="openSlip('{{ asset('storage/' . $p->slip_image) }}', '{{ $p->booking->booking_code }}', '{{ number_format($p->amount, 2) }}')" class="px-3 py-1 bg-nature-cream hover:bg-nature-golden/30 text-nature-deep text-xs font-semibold rounded-lg border border-nature-golden/40 transition flex items-center gap-1.5">
                                        🖼️ ดูสลิป
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-4">
                                @if($p->status === 'under_review')
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-lg">รอตรวจ</span>
                                @elseif($p->status === 'approved')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg">อนุมัติแล้ว</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-lg">ปฏิเสธ</span>
                                @endif
                            </td>
                            <td class="py-4 text-right">
                                @if($p->status === 'under_review')
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('admin.payments.approve', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('ยืนยันว่ายอดเงินถูกต้องและอนุมัติการจองนี้?')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                                ✓ อนุมัติ
                                            </button>
                                        </form>

                                        <button type="button" @click="openReject({{ $p->id }})" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-xl transition">
                                            ✕ ปฏิเสธ
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">เสร็จสิ้น</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-xs text-gray-400">ไม่มีรายการชำระเงินตามเงื่อนไขที่เลือก</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Modal View Slip -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div @click.outside="modalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-base text-nature-dark" x-text="'สลิปการโอน: ' + selectedBooking"></h4>
                    <span class="text-xs text-nature-forest font-bold" x-text="'ยอดโอน: ฿' + selectedAmount"></span>
                </div>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>
            <div class="max-h-[500px] overflow-auto rounded-2xl border border-gray-100 flex items-center justify-center bg-gray-50">
                <img :src="selectedImage" alt="Slip Preview" class="w-full object-contain">
            </div>
            <button @click="modalOpen = false" class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>

    <!-- Modal Reject Reason -->
    <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div @click.outside="rejectModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h4 class="font-bold text-base text-red-600">ระบุเหตุผลที่ปฏิเสธสลิป</h4>
            <form :action="'/admin/payments/' + rejectPaymentId + '/reject'" method="POST" class="space-y-4">
                @csrf
                <textarea name="rejection_reason" required rows="3" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-xs focus:ring-2 focus:ring-red-500 focus:outline-none" placeholder="เช่น สลิปไม่ชัดเจน, ยอดเงินไม่ตรงกับยอดจอง, เวลาโอนไม่ตรง"></textarea>
                <div class="flex gap-2">
                    <button type="button" @click="rejectModalOpen = false" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">ยกเลิก</button>
                    <button type="submit" class="flex-1 py-2.5 bg-red-600 text-white font-bold text-xs rounded-xl shadow">ยืนยันปฏิเสธ</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection