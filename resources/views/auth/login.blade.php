@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ | TripHub เดินกับเรา')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 sm:p-10 shadow-xl border border-gray-100">
        
        <!-- Header & Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/image.png') }}';" alt="TripHub Logo" class="h-16 w-auto mx-auto mb-3 object-contain">
            <h2 class="text-2xl font-black text-nature-deep">ยินดีต้อนรับสู่ TripHub</h2>
            <p class="text-xs text-gray-500 mt-1">เข้าสู่ระบบเพื่อจัดการทริปและสะสมแต้มส่วนลด</p>
        </div>

        @if(session('error'))
            <div class="mb-5 p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-nature-dark mb-1.5">อีเมล</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="user@example.com" class="w-full bg-gray-50 border @error('email') border-red-400 @else border-gray-200 @enderror rounded-2xl p-3.5 text-sm text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none transition">
                @error('email')
                    <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-bold uppercase text-nature-dark">รหัสผ่าน</label>
                    <a href="#" class="text-[11px] text-nature-forest hover:underline">ลืมรหัสผ่าน?</a>
                </div>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-sm text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none transition">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-nature-deep rounded border-gray-300 focus:ring-nature-forest">
                <label for="remember" class="ml-2 text-xs text-gray-600 cursor-pointer">จดจำการเข้าสู่ระบบ</label>
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-nature-deep to-nature-forest hover:from-[#143326] hover:to-[#357759] text-white font-bold text-sm rounded-2xl shadow-lg hover:shadow-nature-forest/40 transition">
                เข้าสู่ระบบ
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center text-xs text-gray-500">
            ยังไม่มีบัญชีสมาชิก? 
            <a href="{{ route('register') }}" class="font-bold text-nature-forest hover:underline">สมัครสมาชิกใหม่</a>
        </div>
    </div>
</div>
@endsection