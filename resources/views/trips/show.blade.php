@extends('layouts.app')

@section('title', $activity->name . ' | TripHub (เดินกับเรา)')

@section('content')
<!-- Hero Details Section -->
<section class="relative h-[480px] lg:h-[540px] bg-nature-deep overflow-hidden">
    <img src="{{ $activity->cover_image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=1920&q=85' }}" alt="{{ $activity->name }}" class="w-full h-full object-cover opacity-60">
    <div class="absolute inset-0 bg-gradient-to-t from-nature-dark via-nature-dark/50 to-transparent"></div>

    <div class="absolute bottom-0 inset-x-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 text-white">
        <div class="flex flex-wrap items-center gap-2 mb-3">
            @if($activity->badge && $activity->badge !== 'NONE')
                <span class="bg-red-500 text-white font-bold px-3 py-1 rounded-full text-xs shadow-md">🔥 {{ $activity->badge }}</span>
            @endif
            <span class="bg-nature-golden text-nature-deep font-bold px-3 py-1 rounded-full text-xs">{{ $activity->duration_text }}</span>
            <span class="bg-white/20 backdrop-blur-md text-white px-3 py-1 rounded-full text-xs">📍 {{ $activity->location }} จ.{{ $activity->province }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-4">
            {{ $activity->name }}
        </h1>
        <div class="flex flex-wrap items-center gap-6 text-sm text-white/80">
            <span class="flex items-center gap-1.5 text-nature-golden font-semibold">
                ★★★★★ <span class="text-white text-xs">(4.9 / 5 จากรีวิวสมาชิก)</span>
            </span>
            <span>• เข้าชมแล้ว {{ number_format($activity->view_count) }} ครั้ง</span>
        </div>
    </div>
</section>

<!-- Trip Specification Bar (สเปกเส้นทางเดินป่า) -->
<section class="bg-nature-dark text-white border-y border-white/10 py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div class="border-r border-white/10 last:border-none">
            <span class="text-xs text-nature-golden uppercase font-semibold block">ระดับความยาก</span>
            <span class="text-base font-bold capitalize text-orange-400">{{ $activity->difficulty_level }}</span>
        </div>
        <div class="border-r border-white/10 last:border-none">
            <span class="text-xs text-nature-golden uppercase font-semibold block">ระยะทางเดินเท้า</span>
            <span class="text-base font-bold">{{ $activity->distance_km ? $activity->distance_km . ' กิโลเมตร' : '-' }}</span>
        </div>
        <div class="border-r border-white/10 last:border-none">
            <span class="text-xs text-nature-golden uppercase font-semibold block">ระดับความสูง</span>
            <span class="text-base font-bold">{{ $activity->altitude_meters ? number_format($activity->altitude_meters) . ' ม. จากระดับน้ำทะเล' : '-' }}</span>
        </div>
        <div>
            <span class="text-xs text-nature-golden uppercase font-semibold block">ช่วงเวลาที่เหมาะสม</span>
            <span class="text-base font-bold">{{ $activity->suitable_season ?? 'ตลอดทั้งปี' }}</span>
        </div>
    </div>
</section>

<!-- Content Body & Sticky Booking Sidebar -->
@php
    $schedulesList = $activity->schedules->map(function($s) use ($activity) {
        return [
            'id' => $s->id,
            'start_date' => $s->start_date->format('Y-m-d'),
            'end_date' => $s->end_date->format('Y-m-d'),
            'start_day' => (int)$s->start_date->format('j'),
            'start_month' => (int)$s->start_date->format('n') - 1, // 0-indexed สำหรับ JavaScript
            'start_year' => (int)$s->start_date->format('Y'),
            'formatted_range' => $s->start_date->format('d/m/Y') . ' - ' . $s->end_date->format('d/m/Y'),
            'available_seats' => (int)$s->available_seats,
            'total_seats' => (int)$s->total_seats,
            'status' => $s->status,
            'price' => (float)($s->price_override ?? $activity->base_price),
        ];
    });
@endphp

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- Left Column: Details & Itinerary (2 Cols) -->
        <div class="lg:col-span-2 space-y-12">

            <!-- Description -->
            <div>
                <h2 class="text-2xl font-bold text-nature-deep mb-4">เกี่ยวกับกิจกรรมนี้</h2>
                <p class="text-gray-700 leading-relaxed text-base whitespace-pre-line">
                    {{ $activity->description }}
                </p>
            </div>

            <!-- Weather Preview Widget (Live Data จาก Open-Meteo API) -->
            <div class="glass-card rounded-2xl p-6 border border-nature-forest/20 shadow-md"
                 x-data="weatherWidget('{{ addslashes($activity->province) }}')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-nature-forest uppercase tracking-wider block">
                                🌤️ พยากรณ์อากาศสด (จ.{{ $activity->province }})
                            </span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        </div>

                        <template x-if="loading">
                            <div class="mt-2 space-y-1">
                                <div class="h-8 w-36 bg-gray-200 rounded-lg animate-pulse"></div>
                                <div class="h-4 w-52 bg-gray-100 rounded animate-pulse"></div>
                            </div>
                        </template>

                        <template x-if="!loading">
                            <div>
                                <div class="flex items-baseline gap-3 mt-1">
                                    <span class="text-3xl font-black text-nature-deep" x-text="currentTemp + '°C'"></span>
                                    <span class="text-sm font-semibold text-gray-500" x-text="'(สูงสุด ' + tempMax + '°C / ต่ำสุด ' + tempMin + '°C)'"></span>
                                </div>
                                <div class="text-xs text-gray-600 mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span class="font-medium text-nature-dark" x-text="weatherText"></span>
                                    <span>•</span>
                                    <span>โอกาสเกิดฝน <strong class="text-blue-600" x-text="rainProb + '%'"></strong></span>
                                    <span>•</span>
                                    <span>ลม <strong x-text="windSpeed + ' km/h'"></strong></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="sm:text-right">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold rounded-full shadow-sm"
                              :class="badgeClass"
                              x-text="badgeText">
                        </span>
                    </div>
                </div>
            </div>

            <!-- Itinerary Timeline -->
            @if($activity->itineraries->count() > 0)
            <div>
                <h2 class="text-2xl font-bold text-nature-deep mb-6">โปรแกรมการเดินทาง (Itinerary)</h2>
                <div class="space-y-6 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-nature-forest/30">
                    @foreach($activity->itineraries as $index => $item)
                    <div class="relative flex items-start gap-4">
                        <div class="w-7 h-7 rounded-full bg-nature-deep text-nature-golden flex items-center justify-center font-bold text-xs ring-4 ring-white shadow z-10">
                            {{ $index + 1 }}
                        </div>
                        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex-1">
                            <span class="text-xs font-bold text-nature-forest">
                                วันที่ {{ $item->day_number }} @if($item->time_slot) | {{ \Carbon\Carbon::parse($item->time_slot)->format('H:i') }} น. @endif
                            </span>
                            <h4 class="font-bold text-nature-dark text-base mt-0.5">{{ $item->title }}</h4>
                            @if($item->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Includes & Excludes -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Includes -->
                <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-6">
                    <h3 class="font-bold text-emerald-900 text-base mb-3 flex items-center gap-2">
                        <span>✅</span> สิ่งที่รวมในทริปนี้
                    </h3>
                    <ul class="space-y-2 text-sm text-emerald-800">
                        @forelse($activity->includes as $inc)
                            <li>• {{ $inc->item_name }}</li>
                        @empty
                            <li>• ไกด์นำทางและทีมงานดูแลความปลอดภัย</li>
                            <li>• ประกันอุบัติเหตุการเดินทาง</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Excludes -->
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                    <h3 class="font-bold text-gray-800 text-base mb-3 flex items-center gap-2">
                        <span>❌</span> สิ่งที่ไม่รวมในทริปนี้
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        @forelse($activity->excludes as $exc)
                            <li>• {{ $exc->item_name }}</li>
                        @empty
                            <li>• อุปกรณ์ส่วนตัวและของใช้ส่วนตัว</li>
                            <li>• ค่าใช้จ่ายส่วนตัวนอกเหนือรายการ</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Rules & Caution -->
            @if($activity->rules || $activity->cautions)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-amber-900">
                <h3 class="font-bold text-base mb-2">⚠️ ข้อควรระวังและกฎระเบียบ</h3>
                <p class="text-sm leading-relaxed whitespace-pre-line">{{ $activity->cautions ?? $activity->rules }}</p>
            </div>
            @endif
        </div>

        <!-- Right Column: Sticky Booking Widget with Interactive Calendar (1 Col) -->
        <div class="lg:col-span-1">
            <div class="sticky top-28 bg-white rounded-3xl p-6 sm:p-7 shadow-2xl border border-gray-100 space-y-6"
                 x-data="bookingCalendar({
                     basePrice: {{ (float)$activity->base_price }},
                     schedules: {{ Js::from($schedulesList) }},
                     isLoggedIn: {{ auth()->check() ? 'true' : 'false' }}
                 })">

                <!-- Header Price & Quota -->
                <div class="flex items-baseline justify-between">
                    <div>
                        <span class="text-xs text-gray-400 block font-medium">ราคาต่อท่าน</span>
                        <span class="text-3xl font-black text-nature-deep" x-text="'฿' + currentPrice.toLocaleString()">฿{{ number_format($activity->base_price) }}</span>
                    </div>
                    <span class="text-xs bg-nature-cream text-nature-forest px-3 py-1 rounded-full font-bold">
                        จองขั้นต่ำ 1 ท่าน
                    </span>
                </div>

                <hr class="border-gray-100">

                <!-- Interactive Calendar Picker -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between pb-1">
                        <label class="text-xs font-bold text-nature-deep uppercase tracking-wider">
                            📅 เลือกรอบวันเดินทาง
                        </label>
                        <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-xl">
                            <button type="button" @click="prevMonth()" class="w-7 h-7 rounded-lg hover:bg-white flex items-center justify-center text-xs font-bold text-gray-600 transition shadow-sm">
                                ‹
                            </button>
                            <span class="text-xs font-bold text-nature-dark px-1 min-w-[95px] text-center" x-text="monthNames[currentMonth] + ' ' + (currentYear + 543)"></span>
                            <button type="button" @click="nextMonth()" class="w-7 h-7 rounded-lg hover:bg-white flex items-center justify-center text-xs font-bold text-gray-600 transition shadow-sm">
                                ›
                            </button>
                        </div>
                    </div>

                    <!-- Days Header -->
                    <div class="grid grid-cols-7 gap-1 text-center text-[11px] font-bold text-gray-400 py-1 border-b border-gray-100">
                        <span>อา</span><span>จ</span><span>อ</span><span>พ</span><span>พฤ</span><span>ศ</span><span>ส</span>
                    </div>

                    <!-- Calendar Grid (เชื่อมแถบวันต่อเนื่อง) -->
                    <div class="grid grid-cols-7 gap-y-1 text-center">
                        <template x-for="blank in firstDayOfWeek" :key="'blank-'+blank">
                            <div class="h-11"></div>
                        </template>

                        <template x-for="day in daysInMonth" :key="'day-'+day">
                            <div class="h-11 relative">
                                <button type="button"
                                        @click="selectDate(day)"
                                        :disabled="!isDateSelectable(day)"
                                        class="w-full h-full flex flex-col items-center justify-center text-xs font-semibold transition relative"
                                        :class="{
                                            // 1. ไฮไลต์รอบเดินทางที่เลือก (Selected Range)
                                            'bg-nature-deep text-white shadow-md z-10': isSelected(day),
                                            'rounded-2xl ring-2 ring-nature-golden': isSelected(day) && isRangeStart(day) && isRangeEnd(day),
                                            'rounded-l-2xl ring-2 ring-nature-golden': isSelected(day) && isRangeStart(day) && !isRangeEnd(day),
                                            'rounded-r-2xl ring-2 ring-nature-golden': isSelected(day) && isRangeEnd(day) && !isRangeStart(day),
                                            'rounded-none': isSelected(day) && !isRangeStart(day) && !isRangeEnd(day),

                                            // 2. วันที่เปิดรับจองแต่ยังไม่ถูกเลือก (Available Range)
                                            'bg-emerald-50 text-nature-deep hover:bg-emerald-100 font-bold border-y border-emerald-300': isDateAvailable(day) && !isSelected(day),
                                            'rounded-2xl border': isDateAvailable(day) && !isSelected(day) && isScheduleStart(day) && isScheduleEnd(day),
                                            'rounded-l-2xl border-l': isDateAvailable(day) && !isSelected(day) && isScheduleStart(day) && !isScheduleEnd(day),
                                            'rounded-r-2xl border-r': isDateAvailable(day) && !isSelected(day) && isScheduleEnd(day) && !isScheduleStart(day),

                                            // 3. วันที่เต็ม / ปิด / ไม่มีรอบ
                                            'bg-amber-50 text-amber-800 opacity-60 cursor-not-allowed border border-amber-200 rounded-2xl': isDateFull(day),
                                            'bg-red-50 text-red-500 opacity-40 cursor-not-allowed line-through rounded-2xl': isDateClosed(day),
                                            'text-gray-300 cursor-not-allowed hover:bg-transparent rounded-2xl': !hasSchedule(day)
                                        }">
                                    <span x-text="day"></span>
                                    
                                    <template x-if="isScheduleStart(day) && !isSelected(day) && isDateAvailable(day)">
                                        <span class="text-[8px] text-emerald-700 leading-none font-bold" x-text="'ว่าง ' + getSchedule(day).available_seats"></span>
                                    </template>
                                    <template x-if="isScheduleStart(day) && isDateFull(day)">
                                        <span class="text-[8px] text-amber-700 leading-none font-bold">เต็ม</span>
                                    </template>
                                    <template x-if="isScheduleStart(day) && isDateClosed(day)">
                                        <span class="text-[8px] text-red-600 leading-none">ปิดรับ</span>
                                    </template>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Calendar Status Legend -->
                    <div class="flex flex-wrap items-center justify-center gap-3 pt-2 text-[10px] text-gray-500 border-t border-gray-100">
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> ว่าง</div>
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span> เต็ม</div>
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-400"></span> ปิดบริการ</div>
                    </div>
                </div>

                <!-- Selected Schedule Detail Banner -->
                <template x-if="selectedSchedule">
                    <div class="p-4 rounded-2xl bg-nature-cream/60 border border-nature-golden/40 text-xs space-y-1.5">
                        <div class="flex items-center justify-between font-bold text-nature-dark">
                            <span>🗓️ รอบเดินทาง:</span>
                            <span class="text-nature-forest text-sm font-bold" x-text="selectedSchedule.formatted_range"></span>
                        </div>
                        <div class="flex items-center justify-between text-gray-600">
                            <span>ที่นั่งว่างในรอบนี้:</span>
                            <span class="font-bold text-emerald-700" x-text="selectedSchedule.available_seats + ' / ' + selectedSchedule.total_seats + ' ท่าน'"></span>
                        </div>
                    </div>
                </template>

                <!-- Seat Quantity Selector -->
                <div>
                    <label class="block text-xs font-bold text-nature-deep uppercase tracking-wider mb-2">
                        👥 จำนวนผู้เดินทาง
                    </label>
                    <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-2xl p-2">
                        <button type="button" @click="decrementSeats()" :disabled="seatsCount <= 1" class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center font-bold text-lg text-gray-600 hover:bg-gray-100 disabled:opacity-30 transition">-</button>
                        <span class="text-sm font-bold text-nature-deep" x-text="seatsCount + ' ท่าน'">1 ท่าน</span>
                        <button type="button" @click="incrementSeats()" :disabled="!selectedSchedule || seatsCount >= selectedSchedule.available_seats" class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center font-bold text-lg text-gray-600 hover:bg-gray-100 disabled:opacity-30 transition">+</button>
                    </div>
                    <template x-if="selectedSchedule && seatsCount >= selectedSchedule.available_seats">
                        <span class="text-[11px] text-amber-600 block text-right mt-1 font-medium">* จองได้สูงสุดตามที่นั่งที่ว่างในรอบนี้</span>
                    </template>
                </div>

                <!-- Price Breakdown Preview -->
                <div class="bg-nature-cream/40 rounded-2xl p-4 space-y-2 text-sm border border-nature-golden/20">
                    <div class="flex justify-between text-gray-600 text-xs">
                        <span>ยอดรวม (<span x-text="seatsCount"></span> ท่าน)</span>
                        <span class="font-bold font-mono" x-text="'฿' + totalPrice.toLocaleString()">฿0</span>
                    </div>
                    <hr class="border-nature-golden/20 my-1">
                    <div class="flex justify-between text-base font-bold text-nature-deep">
                        <span>ยอดชำระสุทธิ</span>
                        <span class="text-xl font-mono" x-text="'฿' + totalPrice.toLocaleString()">฿0</span>
                    </div>
                </div>

                <!-- Booking CTA Form Button -->
                <form x-ref="bookingForm" action="{{ route('bookings.create') }}" method="GET">
                    <input type="hidden" name="schedule_id" :value="selectedSchedule ? selectedSchedule.id : ''">
                    <input type="hidden" name="seats" :value="seatsCount">

                    <button type="button" 
                            @click="submitBooking()"
                            :disabled="!selectedSchedule || selectedSchedule.available_seats === 0" 
                            class="w-full py-4 rounded-2xl bg-gradient-to-r from-nature-deep to-nature-forest hover:from-[#143326] hover:to-[#357759] text-white font-bold text-sm transition shadow-xl hover:shadow-nature-forest/40 disabled:opacity-40 disabled:cursor-not-allowed">
                        <span x-show="!selectedSchedule">👆 กรุณาเลือกวันเดินทางบนปฏิทิน</span>
                        <span x-show="selectedSchedule && selectedSchedule.available_seats > 0">ดำเนินการจองทริปนี้ →</span>
                        <span x-show="selectedSchedule && selectedSchedule.available_seats === 0">รอบเดินทางนี้เต็มแล้ว</span>
                    </button>
                </form>

                <!-- Pop-up Modal แจ้งเตือนเข้าสู่ระบบ -->
                <div x-show="loginModalOpen" 
                     x-cloak 
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 animate-fade-in"
                     @keydown.escape.window="loginModalOpen = false">
                    
                    <div @click.outside="loginModalOpen = false" 
                         class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-100 text-center space-y-5">
                        
                        <div class="w-16 h-16 bg-nature-deep/10 text-3xl rounded-2xl flex items-center justify-center mx-auto text-nature-deep">
                            🔐
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-gray-900">กรุณาเข้าสู่ระบบก่อนจองทริป</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                เข้าสู่ระบบหรือสร้างบัญชีใหม่เพื่อบันทึกประวัติการจอง ตรวจสอบสถานะการเงิน และรับข้อมูลการเตรียมตัวจากทีมงาน TripHub
                            </p>
                        </div>

                        <div class="space-y-2.5 pt-2">
                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" 
                               class="w-full py-3.5 bg-nature-deep hover:bg-nature-forest text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-1.5">
                                <span>🔑</span> เข้าสู่ระบบ (Login)
                            </a>

                            <a href="{{ route('register') }}" 
                               class="w-full py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5">
                                <span>✨</span> สมัครสมาชิกใหม่ (Register)
                            </a>

                            <button type="button" 
                                    @click="loginModalOpen = false" 
                                    class="w-full py-2.5 text-xs text-gray-400 hover:text-gray-600 font-medium transition">
                                กลับไปดูรายละเอียดทริปก่อน
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Alpine.js Controllers -->
<script>
// 1. ระบบพยากรณ์อากาศแบบเรียลไทม์ (Live Weather Widget)
function weatherWidget(provinceName) {
    return {
        loading: true,
        tempMin: 21,
        tempMax: 29,
        currentTemp: 26,
        rainProb: 15,
        windSpeed: 8,
        weatherText: 'สภาพอากาศสดชื่น',
        badgeText: 'เส้นทางเปิดปกติ',
        badgeClass: 'bg-emerald-100 text-emerald-800',
        province: provinceName ? provinceName.trim() : 'สุราษฎร์ธานี',

        async init() {
            try {
                // ค้นหาพิกัด ละติจูด/ลองจิจูด จากชื่อจังหวัด
                const geoRes = await fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(this.province)}&count=1&language=th&format=json`);
                const geoData = await geoRes.json();

                let lat = 9.14, lon = 99.33; // พิกัดสำรอง
                if (geoData && geoData.results && geoData.results.length > 0) {
                    lat = geoData.results[0].latitude;
                    lon = geoData.results[0].longitude;
                }

                // ดึงข้อมูลสภาพอากาศปัจจุบันและการพยากรณ์จาก Open-Meteo
                const weatherRes = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&daily=temperature_2m_max,temperature_2m_min,precipitation_probability_max&current=temperature_2m,wind_speed_10m,weather_code&timezone=Asia%2FBangkok`);
                const wData = await weatherRes.json();

                if (wData && wData.daily) {
                    this.tempMin = Math.round(wData.daily.temperature_2m_min[0]);
                    this.tempMax = Math.round(wData.daily.temperature_2m_max[0]);
                    this.rainProb = wData.daily.precipitation_probability_max[0] ?? 0;
                }

                if (wData && wData.current) {
                    this.currentTemp = Math.round(wData.current.temperature_2m);
                    this.windSpeed = Math.round(wData.current.wind_speed_10m ?? 8);

                    // ตรวจสอบสภาพอากาศตาม WMO Code
                    const code = wData.current.weather_code;
                    if (code <= 1) {
                        this.weatherText = 'ท้องฟ้าแจ่มใส ทัศนวิสัยดีเยี่ยม';
                    } else if (code <= 3) {
                        this.weatherText = 'มีเมฆบางส่วน ลมเย็นสบาย';
                    } else if (code >= 51 && code <= 67) {
                        this.weatherText = 'มีฝนตกปรอยๆ เล็กน้อย';
                    } else if (code >= 80) {
                        this.weatherText = 'มีโอกาสเกิดฝนตกหนัก';
                    } else {
                        this.weatherText = 'มีหมอกบาง ทัศนวิสัยปานกลาง';
                    }

                    // ปรับป้ายสถานะตามโอกาสเกิดฝน
                    if (this.rainProb >= 60) {
                        this.badgeText = '⚠️ ระวังทางลื่นจากฝน';
                        this.badgeClass = 'bg-amber-100 text-amber-800';
                    } else {
                        this.badgeText = '✅ สภาพอากาศเหมาะแก่การเดินป่า';
                        this.badgeClass = 'bg-emerald-100 text-emerald-800';
                    }
                }
            } catch (err) {
                console.warn('Weather fetch fallback triggered:', err);
            } finally {
                this.loading = false;
            }
        }
    };
}

// 2. ระบบปฏิทินเลือกช่วงวันเดินทาง (Interactive Range Calendar)
function bookingCalendar(config) {
    return {
        basePrice: config.basePrice,
        schedules: config.schedules,
        isLoggedIn: config.isLoggedIn,
        loginModalOpen: false,
        currentYear: 2026,
        currentMonth: 8, // เริ่มต้นที่กันยายน (0-indexed: 8 = กันยายน)
        monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
        selectedSchedule: null,
        seatsCount: 1,

        init() {
            if (this.schedules.length > 0) {
                this.currentYear = this.schedules[0].start_year;
                this.currentMonth = this.schedules[0].start_month;

                const firstOpen = this.schedules.find(s => s.status === 'open' && s.available_seats > 0);
                if (firstOpen) {
                    this.selectedSchedule = firstOpen;
                }
            }
        },

        submitBooking() {
            if (!this.isLoggedIn) {
                this.loginModalOpen = true;
                return;
            }

            if (this.selectedSchedule && this.selectedSchedule.available_seats > 0) {
                this.$refs.bookingForm.submit();
            }
        },

        get currentPrice() {
            return this.selectedSchedule ? this.selectedSchedule.price : this.basePrice;
        },

        get totalPrice() {
            return this.currentPrice * this.seatsCount;
        },

        get daysInMonth() {
            return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        },

        get firstDayOfWeek() {
            return new Date(this.currentYear, this.currentMonth, 1).getDay();
        },

        getDateString(day) {
            const m = String(this.currentMonth + 1).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            return `${this.currentYear}-${m}-${d}`;
        },

        getSchedule(day) {
            const dateStr = this.getDateString(day);
            return this.schedules.find(s => dateStr >= s.start_date && dateStr <= s.end_date);
        },

        hasSchedule(day) {
            return !!this.getSchedule(day);
        },

        isDateAvailable(day) {
            const s = this.getSchedule(day);
            return s && s.status === 'open' && s.available_seats > 0;
        },

        isDateFull(day) {
            const s = this.getSchedule(day);
            return s && (s.status === 'full' || s.available_seats <= 0) && s.status !== 'closed';
        },

        isDateClosed(day) {
            const s = this.getSchedule(day);
            return s && s.status === 'closed';
        },

        isDateSelectable(day) {
            return this.isDateAvailable(day);
        },

        isSelected(day) {
            if (!this.selectedSchedule) return false;
            const dateStr = this.getDateString(day);
            return dateStr >= this.selectedSchedule.start_date && dateStr <= this.selectedSchedule.end_date;
        },

        isRangeStart(day) {
            if (!this.selectedSchedule) return false;
            return this.getDateString(day) === this.selectedSchedule.start_date;
        },

        isRangeEnd(day) {
            if (!this.selectedSchedule) return false;
            return this.getDateString(day) === this.selectedSchedule.end_date;
        },

        isScheduleStart(day) {
            const s = this.getSchedule(day);
            return s && this.getDateString(day) === s.start_date;
        },

        isScheduleEnd(day) {
            const s = this.getSchedule(day);
            return s && this.getDateString(day) === s.end_date;
        },

        selectDate(day) {
            const s = this.getSchedule(day);
            if (s && this.isDateAvailable(day)) {
                this.selectedSchedule = s;
                if (this.seatsCount > s.available_seats) {
                    this.seatsCount = 1;
                }
            }
        },

        incrementSeats() {
            if (this.selectedSchedule && this.seatsCount < this.selectedSchedule.available_seats) {
                this.seatsCount++;
            }
        },

        decrementSeats() {
            if (this.seatsCount > 1) {
                this.seatsCount--;
            }
        },

        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
        },

        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
        }
    };
}
</script>
@endsection