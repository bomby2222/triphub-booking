@extends('layouts.app')

@section('title', 'ชำระเงินและแนบสลิป | TripHub')

@section('content')
@php
    $hasSetting = class_exists(\App\Models\Setting::class);

    $promptpayNo = $hasSetting 
        ? \App\Models\Setting::get('promptpay_number', $paymentSettings['promptpay_number'] ?? '0812345678')
        : ($paymentSettings['promptpay_number'] ?? '0812345678');

    $bankName = $hasSetting 
        ? \App\Models\Setting::get('bank_name', $paymentSettings['bank_name'] ?? 'ธนาคารกสิกรไทย (KBANK)')
        : ($paymentSettings['bank_name'] ?? 'ธนาคารกสิกรไทย (KBANK)');

    $bankAccName = $hasSetting 
        ? \App\Models\Setting::get('bank_account_name', $paymentSettings['bank_account_name'] ?? 'บจก. ทริปฮับ (ไทยแลนด์)')
        : ($paymentSettings['bank_account_name'] ?? 'บจก. ทริปฮับ (ไทยแลนด์)');

    $bankAccNo = $hasSetting 
        ? \App\Models\Setting::get('bank_account_number', $paymentSettings['bank_account_number'] ?? '123-4-56789-0')
        : ($paymentSettings['bank_account_number'] ?? '123-4-56789-0');

    $cleanPromptpay = preg_replace('/[^0-9]/', '', $promptpayNo);
@endphp

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ uploading: false }">

    <!-- กล่องแจ้งเตือนความสำเร็จ (Success) -->
    @if (session('success'))
        <div class="mb-6 p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-medium flex items-center gap-4 shadow-sm animate-fade-in">
            <span class="text-3xl">🎉</span>
            <div>
                <p class="font-bold text-base">{{ session('success') }}</p>
                <p class="text-xs text-emerald-700 mt-0.5">การจองของคุณได้รับการยืนยันเรียบร้อยแล้ว เตรียมตัวออกเดินทางได้เลย!</p>
            </div>
        </div>
    @endif

    <!-- กล่องแจ้งเตือนข้อผิดพลาดจาก EasySlip (Error) -->
    @if (session('error'))
        <div class="mb-6 p-5 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-sm font-medium flex items-center gap-4 shadow-sm animate-fade-in">
            <span class="text-3xl">❌</span>
            <div>
                <p class="font-bold text-base">การตรวจสอบสลิปไม่ผ่าน</p>
                <p class="text-xs text-red-700 mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- กล่องแจ้งเตือน Validation ของฟอร์ม -->
    @if ($errors->any())
        <div class="mb-6 p-5 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-sm font-medium flex items-center gap-4 shadow-sm">
            <span class="text-3xl">⚠️</span>
            <div>
                <p class="font-bold text-base">กรุณาตรวจสอบข้อมูล</p>
                <ul class="text-xs text-red-700 mt-0.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- คอลัมน์ที่ 1: QR PromptPay และข้อมูลบัญชีธนาคาร -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-gray-100 flex flex-col items-center text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-nature-forest bg-nature-cream px-3.5 py-1.5 rounded-full mb-3 flex items-center gap-1.5 shadow-sm">
                <span>⚡</span> ชำระเงินผ่าน QR PromptPay
            </span>

            <h2 class="text-3xl font-black text-nature-deep mb-1">฿{{ number_format($booking->net_amount, 2) }}</h2>
            <p class="text-xs text-gray-500 mb-6">รหัสการจอง: <span class="font-mono font-bold text-nature-dark">{{ $booking->booking_code }}</span></p>

            <!-- PromptPay QR Code -->
            <div class="p-4 bg-white rounded-2xl border-2 border-dashed border-nature-forest shadow-inner mb-6 relative group">
                <img src="https://promptpay.io/{{ $cleanPromptpay }}/{{ $booking->net_amount }}.png" 
                     onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=PROMPTPAY-{{ $cleanPromptpay }}-{{ $booking->net_amount }}';"
                     alt="PromptPay QR Code" 
                     class="w-52 h-52 object-contain rounded-lg">
                <span class="text-[10px] text-gray-400 block mt-2">สแกนจ่ายผ่าน Mobile Banking ได้ทุกธนาคาร</span>
            </div>

            <!-- รายละเอียดบัญชีธนาคาร -->
            <div class="w-full text-left bg-gray-50 rounded-2xl p-4 text-xs space-y-2.5 text-gray-700 border border-gray-100">
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="text-gray-500">ธนาคาร:</span>
                    <span class="font-bold text-nature-dark">{{ $bankName }}</span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="text-gray-500">ชื่อบัญชี:</span>
                    <span class="font-bold text-nature-dark">{{ $bankAccName }}</span>
                </div>
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="text-gray-500">เลขที่บัญชี:</span>
                    <span class="font-mono font-bold text-nature-forest text-sm tracking-wider">{{ $bankAccNo }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">เบอร์พร้อมเพย์:</span>
                    <span class="font-mono font-bold text-nature-forest text-sm tracking-wider">{{ $promptpayNo }}</span>
                </div>
            </div>
        </div>

        <!-- คอลัมน์ที่ 2: ฟอร์มแนบสลิป & สถานะการจอง -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-gray-100">
                <h3 class="text-lg font-bold text-nature-dark mb-1">แนบสลิปโอนเงิน</h3>
                <p class="text-xs text-gray-500 mb-6">ระบบ AI ตรวจสอบผ่าน EasySlip อนุมัติและยืนยันที่นั่งให้ทันที</p>

                @if($booking->status === 'confirmed')
                    <!-- กล่องสีเขียว: จองสำเร็จทันที -->
                    <div class="p-6 rounded-2xl bg-gradient-to-br from-nature-deep to-nature-forest text-white text-center space-y-4 shadow-lg animate-fade-in">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center text-2xl mx-auto backdrop-blur-sm">
                            🎫
                        </div>
                        <div>
                            <h4 class="font-bold text-lg text-nature-golden">จองทริปสำเร็จเรียบร้อย!</h4>
                            <p class="text-xs text-white/80 mt-1">ได้รับยอดชำระเงิน ฿{{ number_format($booking->net_amount, 2) }} ครบถ้วน ที่นั่งของคุณได้รับการยืนยันแล้ว</p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ url('/trips') }}" class="w-full inline-block py-3 bg-nature-golden hover:bg-[#c9904d] text-nature-deep font-bold text-xs rounded-xl shadow transition">
                                🌲 ดูทริปอื่นเพิ่มเติม
                            </a>
                        </div>
                    </div>
                @else
                    <!-- ฟอร์มอัปโหลดสลิป -->
                    <form action="{{ route('bookings.submit_payment', $booking->id) }}" method="POST" enctype="multipart/form-data" @submit="uploading = true" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">เลือกรูปภาพสลิปการโอนเงิน (ที่มี mini-QR Code) *</label>
                            <input type="file" name="slip_image" required accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-nature-cream file:text-nature-deep hover:file:bg-nature-golden/30 cursor-pointer border border-gray-200 rounded-2xl p-2 bg-gray-50 focus:outline-none">
                        </div>

                        <button type="submit" :disabled="uploading" class="w-full py-4 bg-gradient-to-r from-nature-deep to-nature-forest text-white text-sm font-bold rounded-2xl shadow-lg hover:shadow-nature-forest/40 transition flex items-center justify-center gap-2 disabled:opacity-60">
                            <span x-show="!uploading">⚡ ตรวจสอบสลิป & ยืนยันที่นั่งทันที</span>
                            <span x-show="uploading" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                กำลังส่งตรวจสลิปกับ EasySlip...
                            </span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- Trip Info Summary Card -->
            <div class="bg-gray-50 rounded-2xl p-5 text-xs text-gray-600 border border-gray-200 space-y-2">
                <p class="font-bold text-nature-dark text-sm">
                    {{ $booking->schedule?->activity?->name ?? 'ทริปท่องเที่ยวธรรมชาติ' }}
                </p>
                <div class="flex justify-between">
                    <span>รอบเดินทาง:</span>
                    <span class="font-medium text-gray-800">
                        {{ $booking->schedule?->start_date ? $booking->schedule->start_date->format('d/m/Y') : '-' }} - 
                        {{ $booking->schedule?->end_date ? $booking->schedule->end_date->format('d/m/Y') : '-' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span>จำนวนผู้เดินทาง:</span>
                    <span class="font-medium text-gray-800">{{ $booking->seats_count }} ท่าน</span>
                </div>
                <div class="flex justify-between">
                    <span>รายชื่อลูกทัวร์:</span>
                    <span class="font-medium text-gray-800">{{ $booking->members?->pluck('full_name')->join(', ') ?: '-' }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection