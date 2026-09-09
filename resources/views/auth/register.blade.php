@extends('layouts.app')

@section('title', 'สมัครสมาชิก | TripHub เดินกับเรา')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-lg w-full bg-white rounded-3xl p-8 sm:p-10 shadow-xl border border-gray-100">
        
        <!-- Header & Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/image.png') }}';" alt="TripHub Logo" class="h-16 w-auto mx-auto mb-3 object-contain">
            <h2 class="text-2xl font-black text-nature-deep">ร่วมเป็นส่วนหนึ่งกับ TripHub</h2>
            <p class="text-xs text-gray-500 mt-1">สมัครสมาชิกวันนี้ รับฟรีทันที 100 แต้มสะสมแลกส่วนลด</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-bold uppercase text-nature-dark mb-1.5">ชื่อ - นามสกุล *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="นายสมชาย ใจดี" class="w-full bg-gray-50 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-2xl p-3.5 text-sm text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none transition">
                @error('name')
                    <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-nature-dark mb-1.5">เบอร์โทรศัพท์ติดต่อ *</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="08x-xxx-xxxx" class="w-full bg-gray-50 border @error('phone') border-red-400 @else border-gray-200 @enderror rounded-2xl p-3.5 text-sm text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none transition">
                @error('phone')
                    <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-nature-dark mb-1.5">อีเมล *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="your.email@example.com" class="w-full bg-gray-50 border @error('email') border-red-400 @else border-gray-200 @enderror rounded-2xl p-3.5 text-sm text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none transition">
                @error('email')
                    <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-nature-dark mb-1.5">รหัสผ่าน *</label>
                    <input type="password" name="password" required placeholder="อย่างน้อย 8 ตัว" class="w-full bg-gray-50 border @error('password') border-red-400 @else border-gray-200 @enderror rounded-2xl p-3.5 text-sm text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-nature-dark mb-1.5">ยืนยันรหัสผ่าน *</label>
                    <input type="password" name="password_confirmation" required placeholder="พิมพ์รหัสอีกครั้ง" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none transition">
                </div>
            </div>
            @error('password')
                <span class="text-[11px] text-red-500 block">{{ $message }}</span>
            @enderror

            <div class="pt-2">
                <button type="submit" class="w-full py-4 bg-gradient-to-r from-nature-deep to-nature-forest hover:from-[#143326] hover:to-[#357759] text-white font-bold text-sm rounded-2xl shadow-lg hover:shadow-nature-forest/40 transition">
                    ยืนยันการสมัครสมาชิก
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center text-xs text-gray-500">
            มีบัญชีสมาชิกอยู่แล้ว? 
            <a href="{{ route('login') }}" class="font-bold text-nature-forest hover:underline">เข้าสู่ระบบ</a>
        </div>
    </div>
</div>
@endsection