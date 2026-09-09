@extends('layouts.admin')

@section('title', 'เพิ่มทริปใหม่ & เปิดรอบเดินทาง | TripHub Admin')
@section('page_title', '➕ เพิ่มทริปเส้นทางใหม่ & กำหนดรอบเดินทาง')

@section('admin_content')
<div class="max-w-4xl mx-auto bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100" 
     x-data="{
        schedules: [
            { start_date: '', end_date: '', total_seats: 20, price: '' }
        ],
        addSchedule() {
            this.schedules.push({ start_date: '', end_date: '', total_seats: 20, price: '' });
        },
        removeSchedule(index) {
            if (this.schedules.length > 1) {
                this.schedules.splice(index, 1);
            }
        }
     }">

    <form action="{{ route('admin.activities.store') }}" method="POST" class="space-y-6 text-xs">
        @csrf

        <!-- 1. ข้อมูลทั่วไปของทริป -->
        <div class="border-b border-gray-100 pb-6">
            <h3 class="text-base font-bold text-nature-dark mb-4 flex items-center gap-2">
                <span>🌲</span> ข้อมูลทั่วไปของทริป
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block font-bold text-gray-700 uppercase mb-1">ชื่อทริป *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="เช่น เดินป่าพิชิตยอดเขาหลวงสุโขทัย ชมทะเลหมอก 360 องศา" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                    @error('name')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- 🏷️ ประเภททริป / หมวดหมู่ (เพิ่มใหม่) -->
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">🏷️ ประเภททริป / หมวดหมู่ *</label>
                    <select name="category" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                        <option value="hiking" {{ old('category') === 'hiking' ? 'selected' : '' }}>🥾 เดินป่าระยะไกล (Hiking / Trekking)</option>
                        <option value="climbing" {{ old('category') === 'climbing' ? 'selected' : '' }}>🧗 ปีนเขา / ผจญภัยยอดเขา (Mountaineering)</option>
                        <option value="camping" {{ old('category') === 'camping' ? 'selected' : '' }}>⛺ แคมป์ปิ้ง & ลานกางเต็นท์ (Camping)</option>
                        <option value="waterfall" {{ old('category') === 'waterfall' ? 'selected' : '' }}>🌊 ลุยน้ำตก / ล่องแก่ง (Rafting & Waterfall)</option>
                        <option value="nature_study" {{ old('category') === 'nature_study' ? 'selected' : '' }}>🌿 ศึกษาธรรมชาติและถ่ายภาพ (Nature & Photo Walk)</option>
                    </select>
                    @error('category')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">⛰️ ระดับความยาก *</label>
                    <select name="difficulty_level" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                        <option value="easy" {{ old('difficulty_level') === 'easy' ? 'selected' : '' }}>ระดับง่าย (Easy)</option>
                        <option value="medium" {{ old('difficulty_level', 'medium') === 'medium' ? 'selected' : '' }}>ระดับปานกลาง (Medium)</option>
                        <option value="hard" {{ old('difficulty_level') === 'hard' ? 'selected' : '' }}>ระดับยาก (Hard)</option>
                        <option value="extreme" {{ old('difficulty_level') === 'extreme' ? 'selected' : '' }}>ระดับท้าทายพิเศษ (Extreme)</option>
                    </select>
                    @error('difficulty_level')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">📍 อุทยาน / สถานที่จัด *</label>
                    <input type="text" name="location" required value="{{ old('location') }}" placeholder="เช่น อุทยานแห่งชาติรามคำแหง" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">📍 จังหวัด *</label>
                    <input type="text" name="province" required value="{{ old('province') }}" placeholder="เช่น สุโขทัย, สุราษฎร์ธานี, เชียงใหม่" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">⏳ ระยะเวลา *</label>
                    <input type="text" name="duration_text" required value="{{ old('duration_text') }}" placeholder="เช่น 2 วัน 1 คืน หรือ 3 วัน 2 คืน" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">💰 ราคาเริ่มต้น (บาท/ท่าน) *</label>
                    <input type="number" step="0.01" name="base_price" required value="{{ old('base_price') }}" placeholder="2190" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm font-mono font-bold focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-gray-700 uppercase mb-1">🖼️ ลิงก์รูปภาพหน้าปก (URL)</label>
                    <input type="url" name="cover_image" value="{{ old('cover_image') }}" placeholder="https://images.unsplash.com/photo-..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-gray-700 uppercase mb-1">📝 รายละเอียดกิจกรรม *</label>
                    <textarea name="description" rows="3" required placeholder="บรรยายเส้นทาง จุดเด่น สิ่งที่น่าสนใจในกิจกรรมนี้..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none leading-relaxed">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 2. กำหนดรอบวันเดินทาง (แสดงผลบนปฏิทินหน้าเว็บทันที) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-nature-dark flex items-center gap-2">
                        <span>🗓️</span> กำหนดรอบวันเดินทางที่เปิดให้จอง (เชื่อมปฏิทินหน้าเว็บ)
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">วันที่ระบุตรงนี้จะไปแสดงเป็นจุดสีเขียว (ว่าง) บนปฏิทินหน้าเว็บของลูกค้าทันที</p>
                </div>
                <button type="button" @click="addSchedule()" class="px-3.5 py-2 bg-nature-cream hover:bg-nature-golden/30 text-nature-deep font-bold rounded-xl border border-nature-golden/40 transition flex items-center gap-1.5">
                    <span>➕</span> เพิ่มอีกรอบเดินทาง
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(sched, index) in schedules" :key="index">
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-2xl relative space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-nature-forest text-xs" x-text="'รอบเดินทางที่ ' + (index + 1)"></span>
                            <button type="button" @click="removeSchedule(index)" x-show="schedules.length > 1" class="text-red-500 hover:text-red-700 font-bold text-xs">
                                ✕ ลบรอบนี้
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-gray-600 font-semibold mb-1">วันเริ่มเดินทาง *</label>
                                <input type="date" :name="'schedules[' + index + '][start_date]'" required x-model="sched.start_date" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-gray-600 font-semibold mb-1">วันสิ้นสุดเดินทาง *</label>
                                <input type="date" :name="'schedules[' + index + '][end_date]'" required x-model="sched.end_date" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-gray-600 font-semibold mb-1">จำนวนที่นั่งเปิดรับ *</label>
                                <input type="number" min="1" :name="'schedules[' + index + '][total_seats]'" required x-model="sched.total_seats" placeholder="20" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-mono font-bold focus:ring-2 focus:ring-nature-forest focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-gray-600 font-semibold mb-1">ราคาเฉพาะรอบนี้ (บาท)</label>
                                <input type="number" step="0.01" :name="'schedules[' + index + '][price]'" x-model="sched.price" placeholder="ใช้ราคาตั้งต้น" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-mono focus:ring-2 focus:ring-nature-forest focus:outline-none">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- ปุ่มกดบันทึก -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.activities.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-2xl transition">ยกเลิก</a>
            <button type="submit" class="px-8 py-3 bg-nature-deep hover:bg-nature-forest text-white font-bold rounded-2xl shadow transition flex items-center gap-2">
                <span>💾</span> บันทึกทริป & เปิดรอบเดินทางทันที
            </button>
        </div>
    </form>
</div>
@endsection