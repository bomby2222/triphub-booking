@extends('layouts.admin')

@section('title', 'จัดการคูปองส่วนลด | TripHub Panel')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800">จัดการคูปองและโปรโมชั่น</h1>
            <p class="text-xs text-gray-500 mt-1">สร้าง รหัสส่วนลด ตรวจสอบการใช้งาน และควบคุมการเปิด/ปิดสิทธิ์</p>
        </div>
        <a href="{{ route('admin.promotions.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-nature-deep hover:bg-nature-forest text-white rounded-2xl text-xs font-bold shadow-md transition">
            + เพิ่มคูปองใหม่
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-500 uppercase font-bold border-b border-gray-100">
                    <tr>
                        <th class="p-4">โค้ดส่วนลด</th>
                        <th class="p-4">ชื่อโปรโมชั่น</th>
                        <th class="p-4">มูลค่าส่วนลด</th>
                        <th class="p-4">สิทธิ์คงเหลือ</th>
                        <th class="p-4">ระยะเวลาใช้งาน</th>
                        <th class="p-4 text-center">สถานะ</th>
                        <th class="p-4 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($promotions as $promo)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 font-mono font-bold text-nature-forest text-sm">
                                {{ $promo->code }}
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-gray-800 block">{{ $promo->title }}</span>
                                <span class="text-[11px] text-gray-400 line-clamp-1">{{ $promo->description }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $promo->discount_type === 'percent' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                    {{ $promo->discount_type === 'percent' ? 'ลด ' . (int)$promo->discount_value . '%' : 'ลด ฿' . number_format($promo->discount_value) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="font-bold {{ ($promo->quota - $promo->used_count) <= 5 ? 'text-red-500' : 'text-gray-700' }}">
                                    {{ max(0, $promo->quota - $promo->used_count) }}
                                </span>
                                <span class="text-gray-400">/ {{ $promo->quota }}</span>
                            </td>
                            <td class="p-4 text-gray-500">
                                {{ $promo->start_date ? $promo->start_date->format('d/m/y') : 'ทันที' }} - 
                                {{ $promo->end_date ? $promo->end_date->format('d/m/y') : 'ไม่มีกำหนด' }}
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.promotions.toggle', $promo->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1 rounded-full text-[11px] font-bold transition {{ $promo->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                        {{ $promo->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                    </button>
                                </form>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.promotions.edit', $promo->id) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-700 font-bold transition">
                                    แก้ไข
                                </a>
                                <form action="{{ route('admin.promotions.destroy', $promo->id) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันที่จะลบคูปองนี้?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold transition">
                                        ลบ
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400">ยังไม่มีคูปองในระบบ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($promotions->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $promotions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection