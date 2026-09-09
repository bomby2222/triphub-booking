@extends('layouts.app')

@section('title', 'กรอกข้อมูลผู้เข้าร่วมทริป | DEEP FOREST')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{
    subtotal: {{ ($schedule->price_override ?? $schedule->activity->base_price) * $seatsCount }},
    discount: 0,
    promoCode: '',
    promoMessage: '',
    isApplied: false,
    applyPromo() {
        if(this.promoCode.toUpperCase() === 'HIKING100') {
            this.discount = 100;
            this.promoMessage = '✅ ใช้ส่วนลด HIKING100 ลด 100 บาทสำเร็จ!';
            this.isApplied = true;
        } else if(this.promoCode.toUpperCase() === 'FOREST20') {
            this.discount = (this.subtotal * 0.20);
            this.promoMessage = '✅ ใช้ส่วนลด FOREST20 ลด 20% สำเร็จ!';
            this.isApplied = true;
        } else {
            this.discount = 0;
            this.promoMessage = '❌ โค้ดส่วนลดไม่ถูกต้องหรือหมดอายุ';
            this.isApplied = false;
        }
    },
    get netTotal() {
        return Math.max(0, this.subtotal - this.discount);
    }
}">

    <!-- Breadcrumb Steps -->
    <div class="flex items-center justify-center gap-4 text-xs font-semibold mb-8 uppercase tracking-wider">
        <span class="text-nature-forest">1. เลือกทริป</span>
        <span>→</span>
        <span class="text-nature-deep bg-nature-golden/30 px-3 py-1 rounded-full border border-nature-golden">2. กรอกข้อมูลผู้เดินทาง</span>
        <span>→</span>
        <span class="text-gray-400">3. ชำระเงิน</span>
    </div>

    <form action="{{ route('bookings.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" name="seats" value="{{ $seatsCount }}">
        <input type="hidden" name="promo_code" :value="promoCode">

        <!-- Form: Member Details (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-gray-100">
                <h2 class="text-xl font-bold text-nature-deep mb-2 flex items-center gap-2">
                    <span>👥</span> ข้อมูลผู้เดินทางทั้งหมด ({{ $seatsCount }} ท่าน)
                </h2>
                <p class="text-xs text-gray-500 mb-6">กรุณากรอกข้อมูลตามจริงเพื่อใช้สำหรับทำประกันอุบัติเหตุและการลงทะเบียนเข้าอุทยาน</p>

                <div class="space-y-6">
                    @for ($i = 0; $i < $seatsCount; $i++)
                        <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200">
                            <h4 class="text-sm font-bold text-nature-forest mb-4">ผู้เดินทางท่านที่ {{ $i + 1 }} @if($i === 0) (ผู้ติดต่อหลัก) @endif</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">ชื่อ - นามสกุล *</label>
                                    <input type="text" name="members[{{ $i }}][full_name]" required class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none" placeholder="เช่น นายสมชาย ใจดี">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">เบอร์โทรศัพท์ติดต่อ *</label>
                                    <input type="tel" name="members[{{ $i }}][phone]" required class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none" placeholder="08x-xxx-xxxx">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">เลขบัตรประชาชน / Passport</label>
                                    <input type="text" name="members[{{ $i }}][id_card]" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none" placeholder="สำหรับทำประกันการเดินทาง">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">โรคประจำตัว / แพ้อาหาร (ถ้ามี)</label>
                                    <input type="text" name="members[{{ $i }}][medical]" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none" placeholder="เช่น หอบหืด, แพ้อาหารทะเล">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="mt-6">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">หมายเหตุเพิ่มเติมถึงทีมงาน</label>
                    <textarea name="user_notes" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-nature-forest focus:outline-none" placeholder="เช่น ต้องการอาหารมังสวิรัติ, มีจุดขึ้นรถระหว่างทาง"></textarea>
                </div>
            </div>
        </div>

        <!-- Summary Sidebar (1 Col) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-100 space-y-5">
                <h3 class="font-bold text-nature-dark text-base">สรุปรายการจอง</h3>

                <div class="p-3 bg-nature-cream/60 rounded-2xl border border-nature-golden/30 space-y-1">
                    <h4 class="font-bold text-nature-deep text-sm">{{ $schedule->activity->name }}</h4>
                    <p class="text-xs text-nature-forest">📅 {{ $schedule->start_date->format('d/m/Y') }} - {{ $schedule->end_date->format('d/m/Y') }}</p>
                    <p class="text-xs text-gray-600">👥 จำนวน: {{ $seatsCount }} ท่าน</p>
                </div>

                <!-- Promo Code Box -->
                <div>
                    <label class="block text-xs font-bold text-nature-deep uppercase mb-1.5">🎟️ โค้ดส่วนลด (Coupon)</label>
                    <div class="flex gap-2">
                        <input type="text" x-model="promoCode" placeholder="เช่น HIKING100" class="uppercase flex-1 bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nature-forest">
                        <button type="button" @click="applyPromo()" class="px-4 py-2 bg-nature-forest text-white rounded-xl text-xs font-bold hover:bg-nature-deep transition">ใช้โค้ด</button>
                    </div>
                    <p x-show="promoMessage" x-text="promoMessage" class="text-xs mt-1.5 font-medium" :class="isApplied ? 'text-emerald-600' : 'text-red-500'"></p>
                </div>

                <!-- Price Breakdown -->
                <div class="space-y-2 text-sm border-t pt-4 text-gray-600">
                    <div class="flex justify-between">
                        <span>ยอดรวม (฿{{ number_format($schedule->price_override ?? $schedule->activity->base_price) }} x {{ $seatsCount }})</span>
                        <span class="font-medium" x-text="'฿' + subtotal.toLocaleString()"></span>
                    </div>
                    <div class="flex justify-between text-emerald-600 font-medium">
                        <span>ส่วนลด</span>
                        <span x-text="'- ฿' + discount.toLocaleString()"></span>
                    </div>
                    <div class="border-t pt-2 flex justify-between text-lg font-bold text-nature-deep">
                        <span>ยอดชำระสุทธิ</span>
                        <span class="text-xl" x-text="'฿' + netTotal.toLocaleString()"></span>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-nature-deep to-nature-forest hover:from-[#143326] hover:to-[#357759] text-white font-bold text-sm transition shadow-xl hover:shadow-nature-forest/40">
                    ยืนยันการจองและไปหน้าชำระเงิน →
                </button>
            </div>
        </div>
    </form>
</div>
@endsection