@extends('layouts.admin')

@section('title', 'จัดการรอบเดินทางและวันบริการ | TripHub Admin')
@section('page_title', '📅 จัดการรอบเดินทาง: ' . $activity->name)

@section('admin_content')
<div class="max-w-6xl mx-auto space-y-8">

    <!-- Back & Header Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-nature-forest hover:underline flex items-center gap-1 font-bold mb-1">
                ← กลับสู่แดชบอร์ด
            </a>
            <h3 class="text-lg font-black text-nature-dark">
                {{ $activity->name }} (จ.{{ $activity->province }})
            </h3>
        </div>

        <!-- Toggle Add Modal Button -->
        <button type="button" onclick="document.getElementById('addScheduleModal').classList.remove('hidden')" class="px-5 py-2.5 bg-nature-deep hover:bg-nature-forest text-white font-bold text-xs rounded-2xl shadow transition flex items-center gap-2">
            <span>➕</span> เพิ่มรอบเดินทางใหม่
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Schedules Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h4 class="font-bold text-sm text-nature-dark">รอบเดินทางทั้งหมด ({{ $activity->schedules->count() }} รอบ)</h4>
                <p class="text-xs text-gray-400">สามารถกดสลับปุ่มเปิด/ปิดบริการ หรือปรับสถานะเป็น "ที่นั่งเต็ม" เพื่อไม่ให้ลูกค้าจองได้ทันที</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                        <th class="p-4">วันเดินทาง (ไป - กลับ)</th>
                        <th class="p-4">ที่นั่งคงเหลือ / ทั้งหมด</th>
                        <th class="p-4">ราคาต่อท่าน</th>
                        <th class="p-4 text-center">สถานะหน้าเว็บ</th>
                        <th class="p-4 text-center">เปิด / ปิดบริการ</th>
                        <th class="p-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activity->schedules as $s)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 font-bold text-nature-dark text-sm">
                                {{ $s->start_date->format('d/m/Y') }} - {{ $s->end_date->format('d/m/Y') }}
                            </td>
                            <td class="p-4">
                                <span class="font-bold font-mono text-sm {{ $s->available_seats > 0 ? 'text-emerald-700' : 'text-red-500' }}">
                                    {{ $s->available_seats }}
                                </span>
                                <span class="text-gray-400 font-mono"> / {{ $s->total_seats }} ท่าน</span>
                            </td>
                            <td class="p-4 font-mono font-bold text-nature-deep">
                                ฿{{ number_format($s->price_override ?? $activity->base_price) }}
                            </td>
                            <td class="p-4 text-center">
                                @if($s->status === 'open' && $s->available_seats > 0)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-full text-[11px]">
                                        ● เปิดรับจองปกติ
                                    </span>
                                @elseif($s->status === 'full' || $s->available_seats <= 0)
                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 font-bold rounded-full text-[11px]">
                                        ● ที่นั่งเต็ม
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 font-bold rounded-full text-[11px]">
                                        ✕ ปิดบริการ
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <!-- 1-Click Fast Toggle Button -->
                                <form action="{{ route('admin.schedules.toggle_status', $s->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3.5 py-1.5 rounded-xl font-bold text-xs transition {{ $s->status === 'closed' ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-red-500 hover:bg-red-600 text-white' }}">
                                        {{ $s->status === 'closed' ? '🔓 เปิดรับจอง' : '🔒 กดปิดบริการ' }}
                                    </button>
                                </form>
                            </td>
                            <td class="p-4 text-center">
                                <!-- Quick Update Available Seats -->
                                <form action="{{ route('admin.schedules.update_status', $s->id) }}" method="POST" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    <select name="status" class="bg-gray-50 border border-gray-200 rounded-lg p-1 text-xs">
                                        <option value="open" {{ $s->status === 'open' ? 'selected' : '' }}>เปิดรับ</option>
                                        <option value="full" {{ $s->status === 'full' ? 'selected' : '' }}>เต็ม</option>
                                        <option value="closed" {{ $s->status === 'closed' ? 'selected' : '' }}>ปิดบริการ</option>
                                    </select>
                                    <input type="number" name="available_seats" value="{{ $s->available_seats }}" min="0" max="{{ $s->total_seats }}" class="w-14 bg-gray-50 border border-gray-200 rounded-lg p-1 text-xs text-center">
                                    <button type="submit" class="p-1 bg-gray-100 hover:bg-gray-200 text-nature-deep rounded-lg" title="บันทึกจำนวน">💾</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">ยังไม่มีรอบเดินทางสำหรับทริปนี้</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: เพิ่มรอบเดินทางใหม่ -->
<div id="addScheduleModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h4 class="font-bold text-sm text-nature-dark">➕ เพิ่มรอบเดินทางใหม่</h4>
            <button onclick="document.getElementById('addScheduleModal').classList.add('hidden')" class="text-gray-400 text-xl font-bold">&times;</button>
        </div>

        <form action="{{ route('admin.activities.schedules.store', $activity->id) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-gray-700 mb-1">วันเริ่มเดินทาง (Start Date) *</label>
                <input type="date" name="start_date" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-nature-forest">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-1">วันจบทริป (End Date) *</label>
                <input type="date" name="end_date" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-nature-forest">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">จำนวนที่นั่งทั้งหมด *</label>
                    <input type="number" name="total_seats" value="20" required min="1" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-nature-forest">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">ราคาพิเศษ (ถ้ามี)</label>
                    <input type="number" name="price_override" placeholder="{{ $activity->base_price }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-nature-forest">
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" onclick="document.getElementById('addScheduleModal').classList.add('hidden')" class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl">ยกเลิก</button>
                <button type="submit" class="flex-1 py-3 bg-nature-deep text-white font-bold rounded-xl shadow">บันทึกรอบใหม่</button>
            </div>
        </form>
    </div>
</div>
@endsection