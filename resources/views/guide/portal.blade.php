@extends('layouts.app')

@section('title', 'Guide Portal | TripHub')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Guide Header Profile Card -->
    <div class="bg-gradient-to-r from-nature-deep to-nature-forest rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-full bg-nature-golden text-nature-deep text-2xl font-bold flex items-center justify-center shadow-md">
                🧭
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold">{{ $currentGuide->name }} ({{ $currentGuide->nickname ?? 'ไกด์' }})</h1>
                    <span class="px-3 py-1 bg-white/20 text-xs font-semibold rounded-full backdrop-blur-sm">ไกด์นำทาง</span>
                </div>
                <p class="text-xs text-white/80 mt-1">📞 เบอร์โทรศัพท์: {{ $currentGuide->phone }} | เลขที่ใบอนุญาต: {{ $currentGuide->license_number ?? 'GD-TH-001' }}</p>
            </div>
        </div>

        <!-- Switch Guide (สำหรับทดสอบระบบ) -->
        <div class="bg-white/10 p-3 rounded-2xl border border-white/20 text-xs">
            <span class="text-nature-golden block mb-1 font-bold">สลับมุมมองไกด์:</span>
            <form method="GET" action="{{ route('guide.portal') }}">
                <select name="guide_id" onchange="this.form.submit()" class="bg-white text-nature-dark rounded-xl px-3 py-1.5 text-xs font-medium focus:outline-none">
                    @foreach($guides as $g)
                        <option value="{{ $g->id }}" {{ $currentGuide->id == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Assigned Trips & Travelers -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-nature-dark">ทริปที่ได้รับมอบหมายจากแอดมิน ({{ $assignedBookings->count() }} รายการ)</h2>
        </div>

        @forelse($assignedBookings as $b)
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
                <!-- Trip Summary Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                    <div>
                        <span class="text-xs font-mono font-bold text-nature-forest bg-nature-cream px-2.5 py-1 rounded-md">{{ $b->booking_code }}</span>
                        <h3 class="text-lg font-bold text-nature-deep mt-1">{{ $b->schedule->activity->name }}</h3>
                        <p class="text-xs text-gray-500">📍 {{ $b->schedule->activity->location }} จ.{{ $b->schedule->activity->province }}</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-xs text-gray-400 block">วันเดินทาง</span>
                        <span class="text-sm font-bold text-nature-dark">{{ $b->schedule->start_date->format('d/m/Y') }} - {{ $b->schedule->end_date->format('d/m/Y') }}</span>
                        <span class="text-xs font-semibold text-emerald-600 block mt-0.5">ชำระเงินเรียบร้อยแล้ว</span>
                    </div>
                </div>

                <!-- Travelers List Table -->
                <div>
                    <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3">รายชื่อลูกทัวร์และข้อมูลสุขภาพ ({{ $b->members->count() }} ท่าน)</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 rounded-xl">
                                    <th class="p-3">ลำดับ</th>
                                    <th class="p-3">ชื่อ - นามสกุล</th>
                                    <th class="p-3">เบอร์ติดต่อ</th>
                                    <th class="p-3">เลขบัตร ปชช./Passport</th>
                                    <th class="p-3">โรคประจำตัว / ข้อควรระวัง</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($b->members as $idx => $m)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="p-3 font-bold text-gray-400">{{ $idx + 1 }}</td>
                                        <td class="p-3 font-bold text-nature-dark">{{ $m->full_name }}</td>
                                        <td class="p-3 font-mono">{{ $m->phone }}</td>
                                        <td class="p-3 font-mono text-gray-500">{{ $m->id_card_or_passport ?? '-' }}</td>
                                        <td class="p-3">
                                            @if($m->medical_conditions)
                                                <span class="px-2 py-0.5 bg-red-50 text-red-700 font-bold rounded border border-red-200">
                                                    ⚠️ {{ $m->medical_conditions }}
                                                </span>
                                            @else
                                                <span class="text-emerald-700">สุขภาพปกติ</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="pt-2 flex justify-between items-center text-xs text-gray-400">
                    <span>มอบหมายเมื่อ: {{ $b->assigned_to_guide_at ? \Carbon\Carbon::parse($b->assigned_to_guide_at)->format('d/m/Y H:i') . ' น.' : '-' }}</span>
                    <a href="tel:{{ $b->user->phone ?? '' }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center gap-1.5 transition">
                        📞 โทรหาผู้ติดต่อหลัก ({{ $b->user->name ?? 'ลูกค้า' }})
                    </a>
                </div>
            </div>
        @empty
            <div class="p-12 text-center bg-white rounded-3xl border border-gray-100 text-gray-400 text-sm">
                ยังไม่มีงานทริปที่มอบหมายมายังไกด์ท่านนี้
            </div>
        @endforelse
    </div>

</div>
@endsection