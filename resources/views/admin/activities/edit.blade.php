@extends('layouts.admin')

@section('title', 'แก้ไขทริปเดินป่า | TripHub Admin')
@section('page_title', '✏️ แก้ไขข้อมูลทริป & จัดการรอบเดินทาง')

@section('admin_content')
<!-- Flatpickr Assets (Green Theme) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<div class="max-w-4xl mx-auto bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100" 
     x-data="{
        schedules: {{ json_encode($activity->schedules->map(function($s) {
            return [
                'id' => $s->id,
                'start_date' => \Carbon\Carbon::parse($s->start_date)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::parse($s->end_date)->format('Y-m-d'),
                'total_seats' => $s->total_seats,
                'price' => $s->price_override ?? '',
            ];
        })) }},
        init() {
            if (this.schedules.length === 0) {
                this.addSchedule();
            }
        },
        addSchedule() {
            this.schedules.push({ id: null, start_date: '', end_date: '', total_seats: 20, price: '' });
        },
        removeSchedule(index) {
            if (confirm('คุณต้องการลบรอบเดินทางนี้ใช่หรือไม่?')) {
                this.schedules.splice(index, 1);
            }
        }
     }">

    <!-- Header Actions -->
    <div class="flex items-center justify-between pb-6 border-b border-gray-100 mb-6">
        <div>
            <h3 class="text-base font-bold text-nature-dark flex items-center gap-2">
                <span>🌲</span> แก้ไขทริป: {{ $activity->name }}
            </h3>
            <p class="text-xs text-gray-400 mt-0.5">รหัสอ้างอิง: #{{ $activity->id }}</p>
        </div>
        <a href="{{ route('admin.activities.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl transition text-xs flex items-center gap-1">
            ← ย้อนกลับ
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl mb-6 text-xs">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST" class="space-y-6 text-xs">
        @csrf
        @method('PUT')

        <!-- 1. ข้อมูลทั่วไปของทริป -->
        <div class="border-b border-gray-100 pb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block font-bold text-gray-700 uppercase mb-1">ชื่อทริป *</label>
                    <input type="text" name="name" required value="{{ old('name', $activity->name) }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">🏷️ ประเภททริป / หมวดหมู่ *</label>
                    <select name="category" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                        <option value="hiking" {{ old('category', $activity->category) === 'hiking' ? 'selected' : '' }}>🥾 เดินป่าระยะไกล (Hiking / Trekking)</option>
                        <option value="climbing" {{ old('category', $activity->category) === 'climbing' ? 'selected' : '' }}>🧗 ปีนเขา / ผจญภัยยอดเขา (Mountaineering)</option>
                        <option value="camping" {{ old('category', $activity->category) === 'camping' ? 'selected' : '' }}>⛺ แคมป์ปิ้ง & ลานกางเต็นท์ (Camping)</option>
                        <option value="waterfall" {{ old('category', $activity->category) === 'waterfall' ? 'selected' : '' }}>🌊 ลุยน้ำตก / ล่องแก่ง (Rafting & Waterfall)</option>
                        <option value="nature_study" {{ old('category', $activity->category) === 'nature_study' ? 'selected' : '' }}>🌿 ศึกษาธรรมชาติและถ่ายภาพ (Nature & Photo Walk)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">⛰️ ระดับความยาก *</label>
                    <select name="difficulty_level" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                        <option value="easy" {{ old('difficulty_level', $activity->difficulty_level) === 'easy' ? 'selected' : '' }}>ระดับง่าย (Easy)</option>
                        <option value="medium" {{ old('difficulty_level', $activity->difficulty_level) === 'medium' ? 'selected' : '' }}>ระดับปานกลาง (Medium)</option>
                        <option value="hard" {{ old('difficulty_level', $activity->difficulty_level) === 'hard' ? 'selected' : '' }}>ระดับยาก (Hard)</option>
                        <option value="extreme" {{ old('difficulty_level', $activity->difficulty_level) === 'extreme' ? 'selected' : '' }}>ระดับท้าทายพิเศษ (Extreme)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">📍 สถานที่ / จุดหมาย *</label>
                    <input type="text" name="location" required value="{{ old('location', $activity->location) }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">📍 จังหวัด *</label>
                    <input type="text" name="province" required value="{{ old('province', $activity->province) }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">⏳ ระยะเวลา *</label>
                    <input type="text" name="duration_text" required value="{{ old('duration_text', $activity->duration_text) }}" placeholder="เช่น 2 วัน 1 คืน" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">💰 ราคาเริ่มต้น (บาท/ท่าน) *</label>
                    <input type="number" step="0.01" name="base_price" required value="{{ old('base_price', $activity->base_price) }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm font-mono font-bold focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-gray-700 uppercase mb-1">🖼️ ลิงก์รูปภาพหน้าปก (URL)</label>
                    <input type="url" name="cover_image" value="{{ old('cover_image', $activity->cover_image) }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none">
                    @if($activity->cover_image)
                        <div class="mt-2 flex items-center gap-3">
                            <img src="{{ $activity->cover_image }}" alt="Cover" class="w-24 h-14 rounded-xl object-cover border border-gray-200">
                            <span class="text-gray-400 text-xs">ตัวอย่างภาพหน้าปกปัจจุบัน</span>
                        </div>
                    @endif
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-gray-700 uppercase mb-1">📝 รายละเอียดกิจกรรม *</label>
                    <textarea name="description" rows="4" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none leading-relaxed">{{ old('description', $activity->description) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $activity->is_active) ? 'checked' : '' }} class="w-4 h-4 text-nature-forest rounded border-gray-300 focus:ring-nature-forest">
                        <span class="font-bold text-gray-700 text-sm">เปิดแสดงทริปนี้บนหน้าเว็บไซต์</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- 2. กำหนดรอบวันเดินทาง (ปฏิทิน Range Picker) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-nature-dark flex items-center gap-2">
                        <span>🗓️</span> จัดการรอบวันเดินทาง (เลือกผ่านปฏิทิน)
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">รอบวันเดินทางทั้งหมดที่เปิดให้จอง สามารถเลือกวันที่เริ่มต้นและสิ้นสุดผ่านปฏิทิน</p>
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
                            <button type="button" @click="removeSchedule(index)" class="text-red-500 hover:text-red-700 font-bold text-xs">
                                ✕ ลบรอบนี้
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <!-- ปฏิทินเลือกช่วงวันเดินทาง -->
                            <div class="sm:col-span-2" x-init="
                                $nextTick(() => {
                                    flatpickr($el.querySelector('.range-picker'), {
                                        mode: 'range',
                                        dateFormat: 'Y-m-d',
                                        altInput: true,
                                        altFormat: 'j M Y',
                                        minDate: 'today',
                                        locale: 'th',
                                        showMonths: 2,
                                        defaultDate: (sched.start_date && sched.end_date) ? [sched.start_date, sched.end_date] : null,
                                        onChange: function(selectedDates, dateStr, instance) {
                                            if (selectedDates.length === 2) {
                                                sched.start_date = instance.formatDate(selectedDates[0], 'Y-m-d');
                                                sched.end_date = instance.formatDate(selectedDates[1], 'Y-m-d');
                                            } else if (selectedDates.length === 1) {
                                                sched.start_date = instance.formatDate(selectedDates[0], 'Y-m-d');
                                                sched.end_date = instance.formatDate(selectedDates[0], 'Y-m-d');
                                            } else {
                                                sched.start_date = '';
                                                sched.end_date = '';
                                            }
                                        }
                                    });
                                });
                            ">
                                <label class="block text-gray-600 font-semibold mb-1">📅 เลือกช่วงวันเดินทาง (เริ่ม - สิ้นสุด) *</label>
                                <input type="text" class="range-picker w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none cursor-pointer" placeholder="คลิกเพื่อเลือกวันไป - วันกลับ..." readonly required>
                                
                                <input type="hidden" :name="'schedules[' + index + '][id]'" :value="sched.id">
                                <input type="hidden" :name="'schedules[' + index + '][start_date]'" :value="sched.start_date">
                                <input type="hidden" :name="'schedules[' + index + '][end_date]'" :value="sched.end_date">
                            </div>

                            <div>
                                <label class="block text-gray-600 font-semibold mb-1">จำนวนที่นั่งเปิดรับ *</label>
                                <input type="number" min="1" :name="'schedules[' + index + '][total_seats]'" required x-model="sched.total_seats" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-mono font-bold focus:ring-2 focus:ring-nature-forest focus:outline-none">
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

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.activities.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-2xl transition">ยกเลิก</a>
            <button type="submit" class="px-8 py-3 bg-nature-deep hover:bg-nature-forest text-white font-bold rounded-2xl shadow transition flex items-center gap-2">
                <span>💾</span> บันทึกการเปลี่ยนแปลงทั้งหมด
            </button>
        </div>
    </form>
</div>
@endsection