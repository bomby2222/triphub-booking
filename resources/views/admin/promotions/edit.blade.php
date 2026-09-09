@extends('layouts.admin')

@section('title', 'แก้ไขคูปองส่วนลด | TripHub Panel')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">แก้ไขคูปอง: {{ $promotion->code }}</h1>
            <p class="text-xs text-gray-500 mt-1">อัปเดตสิทธิ์ หรือปรับเปลี่ยนระยะเวลาใช้งาน</p>
        </div>
        <a href="{{ route('admin.promotions.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800">← ย้อนกลับ</a>
    </div>

    <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">รหัสคูปอง *</label>
                <input type="text" name="code" value="{{ old('code', $promotion->code) }}" required class="w-full uppercase font-mono bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ชื่อโปรโมชั่น *</label>
                <input type="text" name="title" value="{{ old('title', $promotion->title) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">รายละเอียดเงื่อนไข</label>
            <textarea name="description" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">{{ old('description', $promotion->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ประเภทส่วนลด *</label>
                <select name="discount_type" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
                    <option value="fixed" {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>ลดเป็นจำนวนเงิน (฿)</option>
                    <option value="percent" {{ old('discount_type', $promotion->discount_type) == 'percent' ? 'selected' : '' }}>ลดเป็นเปอร์เซ็นต์ (%)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">มูลค่าส่วนลด *</label>
                <input type="number" step="0.01" name="discount_value" value="{{ old('discount_value', $promotion->discount_value) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ป้ายกำกับ (Badge)</label>
                <input type="text" name="badge_text" value="{{ old('badge_text', $promotion->badge_text) }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ยอดสั่งซื้อขั้นต่ำ (฿)</label>
                <input type="number" step="0.01" name="min_spend" value="{{ old('min_spend', $promotion->min_spend) }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ลดสูงสุดไม่เกิน (฿)</label>
                <input type="number" step="0.01" name="max_discount" value="{{ old('max_discount', $promotion->max_discount) }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">จำนวนสิทธิ์ทั้งหมด *</label>
                <input type="number" name="quota" value="{{ old('quota', $promotion->quota) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">วันเริ่มต้นใช้งาน</label>
                <input type="date" name="start_date" value="{{ old('start_date', $promotion->start_date ? $promotion->start_date->format('Y-m-d') : '') }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">วันหมดอายุ</label>
                <input type="date" name="end_date" value="{{ old('end_date', $promotion->end_date ? $promotion->end_date->format('Y-m-d') : '') }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-nature-forest focus:ring-nature-forest border-gray-300">
            <label for="is_active" class="text-xs font-bold text-gray-700">เปิดใช้งาน</label>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('admin.promotions.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-2xl text-xs font-bold transition">ยกเลิก</a>
            <button type="submit" class="px-6 py-2.5 bg-nature-deep hover:bg-nature-forest text-white rounded-2xl text-xs font-bold shadow-md transition">อัปเดตข้อมูล</button>
        </div>
    </form>
</div>
@endsection