@extends('layouts.admin')

@section('title', 'เพิ่มคูปองส่วนลดใหม่ | TripHub Panel')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">เพิ่มคูปองส่วนลดใหม่</h1>
            <p class="text-xs text-gray-500 mt-1">กรอกรายละเอียดเงื่อนไขและมูลค่าส่วนลด</p>
        </div>
        <a href="{{ route('admin.promotions.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800">← ย้อนกลับ</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.promotions.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">รหัสคูปอง (Coupon Code) *</label>
                <input type="text" name="code" value="{{ old('code') }}" placeholder="เช่น SUMMER50, HIKING100" required class="w-full uppercase font-mono bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ชื่อโปรโมชั่น *</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="เช่น ส่วนลดรับหน้าฝน" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">รายละเอียดเงื่อนไข</label>
            <textarea name="description" rows="2" placeholder="เช่น ใช้เป็นส่วนลดสำหรับการจองทริปเดินป่าทุกทริป..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ประเภทส่วนลด *</label>
                <select name="discount_type" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>ลดเป็นจำนวนเงิน (฿)</option>
                    <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>ลดเป็นเปอร์เซ็นต์ (%)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">มูลค่าส่วนลด *</label>
                <input type="number" step="0.01" name="discount_value" value="{{ old('discount_value') }}" placeholder="เช่น 100 หรือ 15" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ป้ายกำกับ (Badge)</label>
                <input type="text" name="badge_text" value="{{ old('badge_text') }}" placeholder="เช่น ลด ฿100 หรือ ลด 15%" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ยอดสั่งซื้อขั้นต่ำ (฿)</label>
                <input type="number" step="0.01" name="min_spend" value="{{ old('min_spend', 0) }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ลดสูงสุดไม่เกิน (฿)</label>
                <input type="number" step="0.01" name="max_discount" value="{{ old('max_discount') }}" placeholder="กรณีลดเป็น %" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">จำนวนสิทธิ์ทั้งหมด *</label>
                <input type="number" name="quota" value="{{ old('quota', 100) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">วันเริ่มต้นใช้งาน</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">วันหมดอายุ</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded text-nature-forest focus:ring-nature-forest border-gray-300">
            <label for="is_active" class="text-xs font-bold text-gray-700">เปิดใช้งานทันที</label>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('admin.promotions.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-2xl text-xs font-bold transition">ยกเลิก</a>
            <button type="submit" class="px-6 py-2.5 bg-nature-deep hover:bg-nature-forest text-white rounded-2xl text-xs font-bold shadow-md transition">บันทึกคูปอง</button>
        </div>
    </form>
</div>
@endsection