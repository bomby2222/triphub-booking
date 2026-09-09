@extends('layouts.admin')

@section('title', 'รายการจองทริปทั้งหมด | TripHub Admin')
@section('page_title', '📑 จัดการรายการจองทริป & โยนงานไกด์')

@section('admin_content')
@php
    $guideList = isset($guides) ? $guides : (class_exists(\App\Models\Guide::class) ? \App\Models\Guide::all() : collect());
@endphp

<div class="space-y-6" x-data="{
    detailModal: false,
    selectedBooking: null,
    reportModal: false,
    selectedReportBooking: null,

    openDetail(b) {
        this.selectedBooking = b;
        this.detailModal = true;
    },

    openReport(b) {
        this.selectedReportBooking = b;
        this.reportModal = true;
    },

    getImg(path) {
        if (!path) return null;
        if (path.startsWith('http://') || path.startsWith('https://')) return path;
        return '/storage/' + path.replace(/^\/+/, '');
    }
}">

    <!-- Alert Notifications -->
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold flex items-center gap-3 shadow-sm animate-fade-in">
            <span class="text-xl">🎉</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-xs font-bold flex items-center gap-3 shadow-sm animate-fade-in">
            <span class="text-xl">❌</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter Pills -->
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ empty($status) ? 'bg-nature-deep text-white shadow' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            ทั้งหมด
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'confirmed' ? 'bg-emerald-600 text-white shadow' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            ✅ ยืนยันแล้ว (Confirmed)
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'processing' ? 'bg-amber-600 text-white shadow' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            ⏳ รอตรวจสลิป (Processing)
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                        <th class="p-4">รหัสจอง</th>
                        <th class="p-4">ผู้ติดต่อหลัก</th>
                        <th class="p-4">ทริป</th>
                        <th class="p-4">รอบเดินทาง</th>
                        <th class="p-4">ที่นั่ง</th>
                        <th class="p-4">ยอดเงิน</th>
                        <th class="p-4">สถานะการเงิน</th>
                        <th class="p-4 text-center">ไกด์ผู้ดูแล & ตรวจงาน</th>
                        <th class="p-4 text-center">ดูลูกทัวร์ & สุขภาพ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 font-mono font-bold text-nature-forest">{{ $b->booking_code }}</td>
                            <td class="p-4">
                                <span class="font-bold text-nature-dark block">{{ $b->user->name ?? 'ลูกค้า' }}</span>
                                <span class="text-gray-400">{{ $b->user->phone ?? '-' }}</span>
                            </td>
                            <td class="p-4 font-bold text-gray-800">{{ $b->schedule->activity->name ?? '-' }}</td>
                            <td class="p-4">{{ $b->schedule ? $b->schedule->start_date->format('d/m/Y') : '-' }}</td>
                            <td class="p-4 font-bold">{{ $b->seats_count }} ท่าน</td>
                            <td class="p-4 font-mono font-bold text-nature-deep">฿{{ number_format($b->net_amount, 2) }}</td>
                            <td class="p-4">
                                @if($b->status === 'confirmed')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px]">Confirmed</span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full font-bold text-[10px]">{{ $b->status }}</span>
                                @endif
                            </td>

                            <!-- คอลัมน์สถานะงานไกด์ & ตรวจสอบการส่งงาน -->
                            <td class="p-4 text-center">
                                @if($b->guide)
                                    <div class="inline-flex flex-col items-center gap-1.5">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-gray-100 text-nature-dark font-bold text-xs border border-gray-200">
                                            <span>🧭</span> {{ $b->guide->name ?? $b->guide->guide_code }}
                                        </span>

                                        {{-- เช็กเงื่อนไขว่าส่งรายงานแล้วหรือไม่ --}}
                                        @if(in_array($b->guide_status, ['report_submitted', 'completed']) || !empty($b->report_submitted_at) || !empty($b->report_meet_photo))
                                            <!-- 1. ไกด์ส่งงานเรียบร้อยแล้ว -->
                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-black border border-emerald-300">
                                                📸 ส่งงานแล้ว (จบทริป)
                                            </span>

                                            <div class="flex items-center gap-1.5 mt-1">
                                                <!-- ปุ่มเปิดดูภาพรายงาน 3 สเต็ป -->
                                                <button type="button" @click="openReport({{ Js::from($b) }})" class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white font-bold text-[10px] rounded-lg shadow-sm transition flex items-center gap-1">
                                                    <span>🖼️</span> ตรวจ 3 รูป
                                                </button>

                                                <!-- ปุ่มบันทึกการจ่ายเงินค่าจ้างไกด์ -->
                                                @if($b->guide_payment_status === 'paid' || $b->guide_status === 'completed')
                                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                        ✓ จ่ายค่าจ้างแล้ว
                                                    </span>
                                                @else
                                                    <form action="{{ route('admin.guides.pay', $b->id) }}" method="POST" onsubmit="return confirm('ยืนยันว่าโอนค่าจ้างให้ไกด์เรียบร้อยแล้ว?');">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1 bg-nature-deep hover:bg-nature-forest text-white font-bold text-[10px] rounded-lg shadow-sm transition">
                                                            💵 จ่ายค่าจ้าง
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @else
                                            <!-- 2. ยังไม่ส่งงาน -->
                                            <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-bold border border-blue-200">
                                                ⏳ กำลังปฏิบัติงาน
                                            </span>
                                        @endif
                                    </div>
                                @elseif($b->status === 'confirmed')
                                    <!-- การจองสำเร็จแล้ว แต่ยังไม่ได้มอบหมายไกด์ -->
                                    <form action="{{ route('admin.bookings.assign_guide', $b->id) }}" method="POST" class="inline-flex items-center justify-center gap-1.5">
                                        @csrf
                                        <select name="guide_id" required class="bg-gray-50 border border-gray-200 rounded-xl px-2.5 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-1 focus:ring-nature-forest">
                                            <option value="">-- เลือกไกด์ --</option>
                                            @foreach($guideList as $g)
                                                <option value="{{ $g->id }}">
                                                    {{ $g->guide_code ? '[' . $g->guide_code . '] ' : '' }}{{ $g->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-3 py-1.5 bg-nature-deep hover:bg-nature-forest text-white rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap flex items-center gap-1">
                                            <span>🚀</span> โยนงาน
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-[11px] italic">รอยืนยันยอดเงิน</span>
                                @endif
                            </td>

                            <!-- ปุ่มดูลูกทัวร์ -->
                            <td class="p-4 text-center">
                                <button type="button" @click="openDetail({{ Js::from($b) }})" class="px-3 py-1.5 bg-nature-cream hover:bg-nature-golden/30 text-nature-deep rounded-xl font-bold border border-nature-golden/40 transition">
                                    🔍 ดูรายชื่อลูกทัวร์
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-gray-400">ไม่พบรายการจอง</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal 1: ดูรายชื่อลูกทัวร์และข้อมูลสุขภาพ -->
    <div x-show="detailModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div @click.outside="detailModal = false" class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b pb-4">
                <div>
                    <span class="text-xs font-mono font-bold text-nature-forest" x-text="selectedBooking ? selectedBooking.booking_code : ''"></span>
                    <h4 class="font-bold text-base text-nature-dark mt-0.5">รายชื่อสมาชิกร่วมทริป & ข้อมูลโรคประจำตัว</h4>
                </div>
                <button @click="detailModal = false" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>

            <div class="overflow-x-auto max-h-80">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500">
                            <th class="p-3">ลำดับ</th>
                            <th class="p-3">ชื่อ - นามสกุล</th>
                            <th class="p-3">เบอร์ติดต่อ</th>
                            <th class="p-3">โรคประจำตัว / แพ้ยา</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-if="selectedBooking && selectedBooking.members">
                            <template x-for="(m, idx) in selectedBooking.members" :key="m.id">
                                <tr>
                                    <td class="p-3 font-bold text-gray-400" x-text="idx + 1"></td>
                                    <td class="p-3 font-bold text-nature-dark" x-text="m.full_name"></td>
                                    <td class="p-3 font-mono" x-text="m.phone || '-'"></td>
                                    <td class="p-3">
                                        <template x-if="m.medical_conditions">
                                            <span class="px-2 py-0.5 bg-red-100 text-red-700 font-bold rounded" x-text="'⚠️ ' + m.medical_conditions"></span>
                                        </template>
                                        <template x-if="!m.medical_conditions">
                                            <span class="text-emerald-700 font-medium">สุขภาพปกติ</span>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-2 border-t border-gray-100">
                <button type="button" @click="detailModal = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl">
                    ปิดหน้าต่าง
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 2: ตรวจสอบรูปรายงาน 3 สเต็ปของไกด์ -->
    <div x-show="reportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div @click.outside="reportModal = false" class="bg-white rounded-3xl max-w-4xl w-full p-6 sm:p-8 shadow-2xl space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <div>
                    <span class="text-xs font-mono font-bold text-nature-forest" x-text="selectedReportBooking ? selectedReportBooking.booking_code : ''"></span>
                    <h4 class="font-bold text-lg text-nature-dark mt-0.5">📸 ภาพหลักฐานรายงานการปฏิบัติหน้าที่ของไกด์</h4>
                </div>
                <button @click="reportModal = false" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>

            <template x-if="selectedReportBooking">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- สเต็ปที่ 1: จุดนัดพบ -->
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center text-center">
                            <span class="text-[11px] font-bold text-nature-forest bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm mb-2">
                                📍 1. จุดนัดพบ รวมพล
                            </span>
                            <div class="w-full h-48 bg-gray-200 rounded-xl overflow-hidden flex items-center justify-center relative shadow-inner">
                                <template x-if="selectedReportBooking.report_meet_photo">
                                    <img :src="getImg(selectedReportBooking.report_meet_photo)" class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-300" @click="window.open(getImg(selectedReportBooking.report_meet_photo), '_blank')">
                                </template>
                                <template x-if="!selectedReportBooking.report_meet_photo">
                                    <span class="text-gray-400 text-xs">ยังไม่มีภาพ</span>
                                </template>
                            </div>
                        </div>

                        <!-- สเต็ปที่ 2: จุดเริ่มเดินเท้า -->
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center text-center">
                            <span class="text-[11px] font-bold text-nature-forest bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm mb-2">
                                🥾 2. ด่านเริ่มเดิน / สตาร์ต
                            </span>
                            <div class="w-full h-48 bg-gray-200 rounded-xl overflow-hidden flex items-center justify-center relative shadow-inner">
                                <template x-if="selectedReportBooking.report_start_photo">
                                    <img :src="getImg(selectedReportBooking.report_start_photo)" class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-300" @click="window.open(getImg(selectedReportBooking.report_start_photo), '_blank')">
                                </template>
                                <template x-if="!selectedReportBooking.report_start_photo">
                                    <span class="text-gray-400 text-xs">ยังไม่มีภาพ</span>
                                </template>
                            </div>
                        </div>

                        <!-- สเต็ปที่ 3: ส่งลูกทัวร์กลับ (จบทริป) -->
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center text-center">
                            <span class="text-[11px] font-bold text-nature-forest bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm mb-2">
                                🏁 3. จบทริป ส่งลูกทัวร์กลับ
                            </span>
                            <div class="w-full h-48 bg-gray-200 rounded-xl overflow-hidden flex items-center justify-center relative shadow-inner">
                                <template x-if="selectedReportBooking.report_end_photo">
                                    <img :src="getImg(selectedReportBooking.report_end_photo)" class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-300" @click="window.open(getImg(selectedReportBooking.report_end_photo), '_blank')">
                                </template>
                                <template x-if="!selectedReportBooking.report_end_photo">
                                    <span class="text-gray-400 text-xs">ยังไม่มีภาพ</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- หมายเหตุเพิ่มเติมจากไกด์ -->
                    <template x-if="selectedReportBooking.report_notes">
                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 text-xs text-amber-900">
                            <span class="font-bold block mb-1">📝 หมายเหตุเพิ่มเติมจากไกด์:</span>
                            <p x-text="selectedReportBooking.report_notes"></p>
                        </div>
                    </template>
                </div>
            </template>

            <div class="flex justify-end pt-3 border-t border-gray-100">
                <button type="button" @click="reportModal = false" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                    ปิดหน้าต่าง
                </button>
            </div>
        </div>
    </div>

</div>
@endsection