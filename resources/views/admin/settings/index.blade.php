@extends('layouts.admin')

@section('title', 'ตั้งค่าระบบและ EasySlip API | TripHub Admin')
@section('page_title', '⚙️ ตั้งค่าระบบการเงิน & EasySlip API')

@section('admin_content')
<div class="max-w-4xl mx-auto space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2 shadow-sm">
            <span>✅</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- กล่องที่ 1: การตั้งค่า EasySlip API ตรวจสลิปอัตโนมัติ -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-5">
            <div class="flex items-center justify-between border-b pb-4">
                <div>
                    <h3 class="text-base font-bold text-nature-dark flex items-center gap-2">
                        <span>⚡</span> EasySlip Integration (ระบบตรวจสลิปอัตโนมัติ)
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">เชื่อมต่อ API กับ EasySlip เพื่ออ่าน QR Code บนสลิปและอนุมัติทันที</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="easyslip_enabled" value="1" {{ ($settings['easyslip_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-nature-forest"></div>
                    <span class="ml-2 text-xs font-bold text-gray-700">เปิดใช้งาน EasySlip</span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">🔑 EasySlip API Token (Secret Key)</label>
                <input type="password" name="easyslip_api_key" value="{{ $settings['easyslip_api_key'] }}" placeholder="es_live_xxxxxxxxxxxxxxxxxxxxxxxxxxxx" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-xs font-mono text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none">
                <span class="text-[11px] text-gray-400 mt-1 block">* ขอรับ API Token ได้ที่เว็บไซต์ทางการของ EasySlip (easyslip.com)</span>
            </div>
        </div>

        <!-- กล่องที่ 2: บัญชีธนาคารและพร้อมเพย์สำหรับรับโอนเงิน -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-5">
            <div class="border-b pb-4">
                <h3 class="text-base font-bold text-nature-dark flex items-center gap-2">
                    <span>🏦</span> ข้อมูลบัญชีรับเงิน (แสดงผลบนหน้าชำระเงินของลูกค้า)
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">ข้อมูลนี้จะถูกส่งไปแสดงเป็น QR PromptPay และรายละเอียดโอนเงินในหน้าแรก</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                <!-- เบอร์พร้อมเพย์ -->
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1.5">📱 เบอร์โทรศัพท์ หรือ เลขพร้อมเพย์ *</label>
                    <input type="text" name="promptpay_number" value="{{ $settings['promptpay_number'] }}" required placeholder="0812345678" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm font-mono font-bold text-nature-forest focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <!-- ธนาคาร -->
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1.5">🏛️ ธนาคาร *</label>
                    <input type="text" name="bank_name" value="{{ $settings['bank_name'] }}" required placeholder="ธนาคารกสิกรไทย (KBANK)" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm font-medium text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <!-- เลขที่บัญชี -->
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1.5">💳 เลขที่บัญชีธนาคาร *</label>
                    <input type="text" name="bank_account_number" value="{{ $settings['bank_account_number'] }}" required placeholder="123-4-56789-0" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm font-mono font-bold text-nature-forest focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <!-- ชื่อบัญชี -->
                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1.5">👤 ชื่อบัญชี / ชื่อบริษัท *</label>
                    <input type="text" name="bank_account_name" value="{{ $settings['bank_account_name'] }}" required placeholder="บจก. ทริปฮับ (ไทยแลนด์)" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm font-medium text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="px-8 py-3.5 bg-nature-deep hover:bg-nature-forest text-white font-bold text-xs rounded-2xl shadow-lg transition flex items-center gap-2">
                <span>💾</span> บันทึกการตั้งค่าทั้งหมด
            </button>
        </div>
    </form>
</div>
@endsection