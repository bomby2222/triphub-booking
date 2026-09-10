<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TripHub - จองทริปเดินป่า ปีนเขา กางเต็นท์ ชมน้ำตกทั่วไทย')</title>

    <!-- Google Fonts: Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Prompt', 'sans-serif'],
                    },
                    colors: {
                        nature: {
                            deep: '#1B4332',
                            forest: '#40916C',
                            golden: '#DDA15E',
                            cream: '#F5F3EA',
                            dark: '#1D2924',
                            light: '#F8FAF8',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .glass-nav {
            background: rgba(27, 67, 50, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .glass-dark {
            background: rgba(29, 41, 36, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-nature-light text-nature-dark font-sans antialiased selection:bg-nature-forest selection:text-white flex flex-col min-h-screen">

    <!-- Navigation Header -->
    <header x-data="{ mobileMenu: false, userDropdown: false }" class="fixed top-0 left-0 right-0 z-50 glass-nav transition-all duration-300 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/image.png') }}';" alt="TripHub Logo" class="h-11 w-auto object-contain rounded-xl bg-white/10 p-1 border border-white/20 shadow-md group-hover:scale-105 transition-transform">
                    <div>
                        <span class="text-xl font-bold tracking-tight text-white block">TripHub</span>
                        <span class="text-[10px] uppercase tracking-widest text-nature-golden font-medium block">เดินกับเรา • จองทริปเดินป่า</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-white/90">
                    <a href="{{ url('/') }}" class="hover:text-nature-golden transition">หน้าแรก</a>
                    <a href="{{ url('/trips') }}" class="hover:text-nature-golden transition">ทริปเดินป่าทั้งหมด</a>
                    <a href="{{ url('/promotions') }}" class="hover:text-nature-golden transition">โปรโมชั่น</a>
                    <a href="{{ url('/news') }}" class="hover:text-nature-golden transition">ข่าวสารและประกาศ</a>
                    <a href="{{ url('/reviews') }}" class="hover:text-nature-golden transition">รีวิวจากสมาชิก</a>
                </nav>

                <!-- Auth Action Buttons -->
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <div class="relative" @click.outside="userDropdown = false">
                            <button @click="userDropdown = !userDropdown" class="flex items-center gap-3 py-1.5 px-3 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white transition">
                                <div class="w-8 h-8 rounded-full bg-nature-golden text-nature-deep font-bold flex items-center justify-center text-sm">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="userDropdown" x-cloak class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl py-2 border border-gray-100 text-nature-dark">
                                <a href="{{ url('/user/profile') }}" class="flex items-center px-4 py-2.5 text-sm hover:bg-nature-cream transition">
                                    👤 โปรไฟล์ของฉัน
                                </a>
                                <a href="{{ url('/user/bookings') }}" class="flex items-center px-4 py-2.5 text-sm hover:bg-nature-cream transition">
                                    🎫 ประวัติการจองทริป
                                </a>
                                <a href="{{ url('/user/wishlist') }}" class="flex items-center px-4 py-2.5 text-sm hover:bg-nature-cream transition">
                                    ❤️ รายการที่บันทึกไว้
                                </a>
                                <hr class="my-1 border-gray-100">
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                        🚪 ออกจากระบบ
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ url('/login') }}" class="text-white hover:text-nature-golden text-sm font-medium transition px-3 py-2">
                            เข้าสู่ระบบ
                        </a>
                        <a href="{{ url('/register') }}" class="px-5 py-2.5 rounded-full bg-nature-golden hover:bg-[#c9904d] text-nature-deep font-semibold text-sm transition shadow-lg hover:shadow-nature-golden/30">
                            สมัครสมาชิก
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenu = !mobileMenu" class="text-white p-2 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenu" x-cloak class="md:hidden glass-dark px-6 py-5 border-t border-white/10 space-y-4">
            <a href="{{ url('/') }}" class="block text-white font-medium">หน้าแรก</a>
            <a href="{{ url('/trips') }}" class="block text-white/90">ทริปเดินป่าทั้งหมด</a>
            <a href="{{ url('/promotions') }}" class="block text-white/90">โปรโมชั่น</a>
            <a href="{{ url('/news') }}" class="block text-white/90">ข่าวสารและประกาศ</a>
            <a href="{{ url('/reviews') }}" class="block text-white/90">รีวิว</a>
            <div class="pt-4 border-t border-white/10 flex flex-col gap-2">
                @auth
                    <a href="{{ url('/user/profile') }}" class="block text-nature-golden font-medium">บัญชีของฉัน ({{ Auth::user()->name }})</a>
                @else
                    <a href="{{ url('/login') }}" class="text-center py-2 text-white border border-white/20 rounded-xl">เข้าสู่ระบบ</a>
                    <a href="{{ url('/register') }}" class="text-center py-2 bg-nature-golden text-nature-deep font-semibold rounded-xl">สมัครสมาชิก</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-nature-dark text-white/80 pt-16 pb-8 border-t border-white/10 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
            <div class="space-y-4 md:col-span-1">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/image.png') }}';" alt="TripHub Logo" class="h-10 w-auto object-contain rounded-lg bg-white/10 p-1 border border-white/10">
                    <span class="text-xl font-bold tracking-tight text-white">TripHub</span>
                </div>
                <p class="text-sm text-white/70 leading-relaxed">
                    ระบบจองทริปเดินป่าและกิจกรรมท่องเที่ยวเชิงธรรมชาติครบวงจร ดูแลทุกก้าวเดินด้วยทีมไกด์มืออาชีพที่ได้รับมาตรฐาน
                </p>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-base">เมนูลัด</h4>
                <ul class="space-y-2 text-sm text-white/70">
                    <li><a href="{{ url('/trips') }}" class="hover:text-nature-golden transition">ทริปเดินป่าทั้งหมด</a></li>
                    <li><a href="{{ url('/promotions') }}" class="hover:text-nature-golden transition">สิทธิพิเศษ & คูปอง</a></li>
                    <li><a href="{{ url('/news') }}" class="hover:text-nature-golden transition">ประกาศอุทยาน</a></li>
                    <li><a href="{{ url('/about') }}" class="hover:text-nature-golden transition">เกี่ยวกับเรา</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-base">ประเภทระดับความยาก</h4>
                <ul class="space-y-2 text-sm text-white/70">
                    <li><span class="text-emerald-400">●</span> ระดับง่าย (เหมาะสำหรับมือใหม่)</li>
                    <li><span class="text-yellow-400">●</span> ระดับปานกลาง (มีทางชันสลับราบ)</li>
                    <li><span class="text-orange-400">●</span> ระดับยาก (ต้องใช้ทักษะเดินป่า)</li>
                    <li><span class="text-red-500">●</span> ระดับท้าทายพิเศษ (สันเขา/ระยะไกล)</li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-base">ติดต่อสอบถาม</h4>
                <div class="space-y-2 text-sm text-white/70">
                    <p>📞 โทรศัพท์: 080-053-8514</p>
                    <p>💬 LINE Official: @TripHub</p>
                    <p>✉️ อีเมล: support@TripHub.com</p>
                    <p>📍 ประจำการ: ทุกวัน 08:30 - 18:00 น.</p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-white/50 gap-4">
            <p>© 2026 TripHub (เดินกับเรา). สงวนลิขสิทธิ์ทุกประการ.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:underline">นโยบายความเป็นส่วนตัว</a>
                <a href="#" class="hover:underline">ข้อกำหนดและเงื่อนไข</a>
                <a href="{{ url('/admin/login') }}" class="text-nature-golden/70 hover:underline">สำหรับเจ้าหน้าที่ (Admin)</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>