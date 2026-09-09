@extends('layouts.admin')

@section('title', 'จัดการทริปทั้งหมด | TripHub Admin')
@section('page_title', '🌲 จัดการข้อมูลทริปท่องเที่ยว')

@section('admin_content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-nature-dark">เส้นทางเดินป่าและกิจกรรมทั้งหมด</h3>
            <p class="text-xs text-gray-400">เพิ่ม แก้ไข ปรับราคา และกดเข้าจัดการรอบเดินทาง (ปฏิทิน)</p>
        </div>
        <a href="{{ route('admin.activities.create') }}" class="px-5 py-2.5 bg-nature-deep hover:bg-nature-forest text-white rounded-2xl text-xs font-bold shadow transition flex items-center gap-1.5">
            <span>➕</span> เพิ่มทริปใหม่
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Alert Error (ป้องกันลบทริปที่มีการจอง) -->
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-medium flex items-center gap-2 shadow-sm">
            <span>❌</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                        <th class="p-4">รูป & ชื่อทริป</th>
                        <th class="p-4">สถานที่ / จังหวัด</th>
                        <th class="p-4">ระดับความยาก</th>
                        <th class="p-4">ราคาต่อท่าน</th>
                        <th class="p-4 text-center">รอบเดินทาง</th>
                        <th class="p-4 text-center">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activities as $act)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 flex items-center gap-3">
                                <img src="{{ $act->cover_image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=200&q=80' }}" class="w-12 h-12 rounded-xl object-cover border border-gray-200 shrink-0">
                                <div>
                                    <span class="font-bold text-nature-dark block text-sm">{{ $act->name }}</span>
                                    <span class="text-gray-400 text-[11px]">{{ $act->duration_text }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-gray-700 block">{{ $act->location }}</span>
                                <span class="text-nature-forest font-semibold">จ.{{ $act->province }}</span>
                            </td>
                            <td class="p-4">
                                <span class="capitalize font-bold text-orange-600">{{ $act->difficulty_level }}</span>
                            </td>
                            <td class="p-4 font-mono font-bold text-nature-deep text-sm">
                                ฿{{ number_format($act->base_price) }}
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('admin.activities.schedules.index', $act->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-nature-cream hover:bg-nature-golden/30 text-nature-deep rounded-xl font-bold border border-nature-golden/40 transition">
                                    <span>📅</span> จัดการรอบ ({{ $act->schedules_count }})
                                </a>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <a href="{{ route('admin.activities.edit', $act->id) }}" class="p-2 text-nature-forest hover:bg-nature-cream rounded-lg transition font-bold">
                                    ✏️ แก้ไข
                                </a>
                                <form action="{{ route('admin.activities.destroy', $act->id) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบทริปนี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition font-bold" title="ลบทริป">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">ยังไม่มีข้อมูลทริป</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $activities->links() }}</div>
</div>
@endsection