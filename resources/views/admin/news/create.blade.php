@extends('layouts.admin')

@section('title', 'สร้างประกาศใหม่ | TripHub Panel')

@section('content')
<div class="p-6 max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-black text-gray-800">สร้างประกาศข่าวสารใหม่</h1>
        <a href="{{ route('admin.news.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800">← ย้อนกลับ</a>
    </div>

    <form action="{{ route('admin.news.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ประเภทประกาศ *</label>
            <select name="category" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
                <option value="general">📢 ข่าวสารทั่วไป / เปิดฤดูกาล</option>
                <option value="weather">⚠️ สภาพอากาศ / เตือนภัย</option>
                <option value="closed">🚫 ปิดเส้นทางชั่วคราว</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">หัวข้อประกาศ *</label>
            <input type="text" name="title" required placeholder="เช่น เปิดฤดูกาลเดินป่าเขาหลวงสุโขทัย 2569" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ป้ายกำกับ (เว้นว่างเพื่อใช้ค่าเริ่มต้น)</label>
            <input type="text" name="badge_text" placeholder="เช่น 📢 ประกาศ หรือ ⚠️ สภาพอากาศ" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-gray-700 mb-1">เนื้อหารายละเอียด *</label>
            <textarea name="content" rows="4" required placeholder="ระบุรายละเอียดประกาศ..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none"></textarea>
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_published" id="is_published" value="1" checked class="w-4 h-4 rounded text-nature-forest focus:ring-nature-forest border-gray-300">
            <label for="is_published" class="text-xs font-bold text-gray-700">เผยแพร่ทันที</label>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-2xl text-xs font-bold transition">ยกเลิก</a>
            <button type="submit" class="px-6 py-2.5 bg-nature-deep hover:bg-nature-forest text-white rounded-2xl text-xs font-bold shadow-md transition">บันทึกประกาศ</button>
        </div>
    </form>
</div>
@endsection