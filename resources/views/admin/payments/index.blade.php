@extends('layouts.admin')

@section('title', 'ตรวจสอบการชำระเงิน & EasySlip | Finance')
@section('page_title', '💳 ตรวจสอบหลักฐานการโอนเงิน (EasySlip & Finance)')

@section('admin_content')
<div class="space-y-6" x-data="{
    modalOpen: false,
    selectedImage: '',
    selectedBooking: '',
    selectedAmount: '',
    selectedNotes: '',
    rejectModalOpen: false,
    rejectPaymentId: null,
    openSlip(img, code, amount, notes) {
        this.selectedImage = img;
        this.selectedBooking = code;
        this.selectedAmount = amount;
        this.selectedNotes = notes || 'ไม่มีข้อมูลเพิ่มเติม';
        this.modalOpen = true;
    },
    openReject(id) {
        this.rejectPaymentId = id;
        this.rejectModalOpen = true;
    }
}">

    <!-- Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.payments.index', ['status' => 'under_review']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'under_review' ? 'bg-amber-500 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            ⏳ รอตรวจสอบ ({{ \App\Models\Payment::where('status', 'under_review')->count() }})
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
                        <th class="pb-3">ทริป & วันเดินทาง</th>
                        <th class="pb-3">ยอดที่ต้องโอน</th>
                        <th class="pb-3">การตรวจสอบ (EasySlip / Note)</th>
                        <th class="pb-3">หลักฐาน (Slip)</th>
                        <th class="pb-3">สถานะ</th>
                        <th class="pb-3 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payments as $p)
                        @php
                            $slipPath = $p->slip_image ?? $p->slip_image_url ?? null;
                            $fullSlipUrl = $slipPath ? asset('storage/' . $slipPath) : '';
                            $isEasySlipAuto = str_contains($p->notes ?? '', 'EasySlip') || str_contains($p->notes ?? '', 'ตรวจผ่านอัตโนมัติ');
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition">
                            <!-- Booking Code -->
                            <td class="py-4 font-mono font-bold text-nature-deep">
                                {{ $p->booking->booking_code ?? '-' }}
                            </td>

                            <!-- Payer -->
                            <td class="py-4">
                                <span class="font-medium text-nature-dark block">{{ $p->booking->user->name ?? 'ผู้ใช้งานทั่วไป' }}</span>
                                <span class="text-xs text-gray-400 font-mono">{{ $p->booking->user->phone ?? '-' }}</span>
                            </td>

                            <!-- Trip Details -->
                            <td class="py-4">
                                <span class="font-medium text-gray-800 text-xs block truncate max-w-xs">{{ $p->booking->schedule->activity->name ?? '-' }}</span>
                                <span class="text-[11px] text-nature-forest font-semibold">
                                    {{ $p->booking->schedule ? $p->booking->schedule->start_date->format('d/m/Y') : '-' }}
                                </span>
                            </td>

                            <!-- Amount -->
                            <td class="py-4 font-bold text-nature-forest text-base font-mono">
                                ฿{{ number_format($p->amount, 2) }}
                            </td>

                            <!-- EasySlip Audit Notes -->
                            <td class="py-4 text-xs max-w-xs">
                                @if($isEasySlipAuto)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 text-[11px] mb-1">
                                        <span>⚡</span> EasySlip AI ตรวจผ่าน
                                    </span>
                                @endif
                                <p class="text-gray-500 truncate text-[11px]" title="{{ $p->notes }}">
                                    {{ $p->notes ?: '-' }}
                                </p>
                            </td>

                            <!-- Slip Thumbnail Button -->
                            <td class="py-4">
                                @if($slipPath)
                                    <button type="button" 
                                            @click="openSlip('{{ $fullSlipUrl }}', '{{ $p->booking->booking_code ?? '' }}', '{{ number_format($p->amount, 2) }}', '{{ addslashes($p->notes ?? '') }}')" 
                                            class="px-3 py-1.5 bg-nature-cream hover:bg-nature-golden/30 text-nature-deep text-xs font-semibold rounded-xl border border-nature-golden/40 transition flex items-center gap-1.5 shadow-sm">
                                        <span>🖼️</span> ดูสลิป
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 italic">ไม่มีรูปภาพ</span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4">
                                @if($p->status === 'under_review' || $p->status === 'pending')
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-lg">⏳ รอตรวจ</span>
                                @elseif($p->status === 'approved' || $p->status === 'confirmed')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg">✅ อนุมัติแล้ว</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-lg">❌ ปฏิเสธ</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 text-right">
                                @if($p->status === 'under_review' || $p->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Quick Manual Approve -->
                                        <form action="{{ route('admin.payments.approve', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('ยืนยันว่ายอดเงินถูกต้องและอนุมัติการจองนี้?')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                                ✓ อนุมัติ
                                            </button>
                                        </form>

                                        <!-- Quick Reject -->
                                        <button type="button" @click="openReject({{ $p->id }})" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-xl transition">
                                            ✕ ปฏิเสธ
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">เรียบร้อย</span>
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

    <!-- Modal View Slip & EasySlip Information -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div @click.outside="modalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <div>
                    <h4 class="font-bold text-base text-nature-dark" x-text="'สลิปโอนเงิน: ' + selectedBooking"></h4>
                    <span class="text-xs text-nature-forest font-bold font-mono" x-text="'ยอดชำระ: ฿' + selectedAmount"></span>
                </div>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>

            <!-- Image Preview Box -->
            <div class="max-h-[440px] overflow-auto rounded-2xl border border-gray-100 flex items-center justify-center bg-gray-50 p-2">
                <img :src="selectedImage" alt="Slip Preview" class="max-h-[420px] w-auto rounded-xl object-contain shadow-sm">
            </div>

            <!-- EasySlip Note / AI Info -->
            <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-xs">
                <span class="font-bold text-gray-700 block mb-0.5">ผลการอ่านสลิป / ข้อมูลอ้างอิง:</span>
                <p class="text-gray-600 break-words font-mono text-[11px]" x-text="selectedNotes"></p>
            </div>

            <button @click="modalOpen = false" class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>

    <!-- Modal Reject Reason -->
    <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div @click.outside="rejectModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <h4 class="font-bold text-base text-red-600">ระบุเหตุผลที่ปฏิเสธสลิป</h4>
                <button @click="rejectModalOpen = false" class="text-gray-400 text-xl font-bold">&times;</button>
            </div>

            <form :action="'/admin/payments/' + rejectPaymentId + '/reject'" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-gray-700 mb-1">เหตุผลในการปฏิเสธ *</label>
                    <textarea name="reason" required rows="3" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-xs focus:ring-2 focus:ring-red-500 focus:outline-none" placeholder="เช่น ยอดเงินไม่ตรง, สลิปซ้ำ, ภาพไม่ชัดเจน หรือตรวจไม่พบ QR Code"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="button" @click="rejectModalOpen = false" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">ยกเลิก</button>
                    <button type="submit" class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow">ยืนยันปฏิเสธ</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection