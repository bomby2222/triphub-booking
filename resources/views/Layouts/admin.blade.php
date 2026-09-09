<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TripHub Admin Panel')</title>

    <!-- Google Fonts: Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Prompt', 'sans-serif'] },
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
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 text-nature-dark font-sans antialiased min-h-screen flex" x-data="{ sidebarOpen: true }">

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-nature-dark text-white flex-shrink-0 transition-all duration-300 flex flex-col justify-between min-h-screen sticky top-0 z-40 border-r border-white/10 shadow-xl">
        <div>
            <!-- Header Brand -->
            <div class="h-20 flex items-center px-5 border-b border-white/10 gap-3">
                <img src="{{ asset('images/logo.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/image.png') }}';" alt="TripHub Logo" class="h-10 w-auto rounded-xl bg-white/10 p-1 border border-white/20">
                <div x-show="sidebarOpen" class="overflow-hidden transition">
                    <span class="text-base font-black tracking-tight block">TripHub</span>
                    <span class="text-[10px] text-nature-golden font-medium block">ระบบจัดการหลังบ้าน</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-3 space-y-1.5 text-xs font-semibold">
                <!-- 1. Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-nature-deep text-white shadow-md' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">📊</span>
                    <span x-show="sidebarOpen">แดชบอร์ดภาพรวม</span>
                </a>

                <!-- 2. Trips Management -->
                <a href="{{ route('admin.activities.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition {{ request()->routeIs('admin.activities.*') ? 'bg-nature-deep text-white shadow-md' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">🌲</span>
                    <span x-show="sidebarOpen">จัดการทริป & รอบเดินทาง</span>
                </a>

                <!-- 3. Bookings & Guide Assignment -->
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition {{ request()->routeIs('admin.bookings.*') ? 'bg-nature-deep text-white shadow-md' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">📑</span>
                    <span x-show="sidebarOpen">รายการจองทริป & โยนงาน</span>
                </a>

                <!-- 4. Guides Management -->
                <a href="{{ route('admin.guides.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition {{ request()->routeIs('admin.guides.*') ? 'bg-nature-deep text-white shadow-md' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">🧭</span>
                    <span x-show="sidebarOpen">จัดการทีมไกด์ประจำพื้นที่</span>
                </a>

                <!-- 5. Promotions Management -->
                <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition {{ request()->routeIs('admin.promotions.*') ? 'bg-nature-deep text-white shadow-md' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">🎟️</span>
                    <span x-show="sidebarOpen">จัดการโปรโมชั่น & คูปอง</span>
                </a>

                <!-- 6. News & Announcements Management (เพิ่มใหม่) -->
                <a href="{{ route('admin.news.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition {{ request()->routeIs('admin.news.*') ? 'bg-nature-deep text-white shadow-md' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">📰</span>
                    <span x-show="sidebarOpen">จัดการข่าวสาร & ประกาศ</span>
                </a>

                <!-- 7. Guide Portal Link -->
                <a href="{{ route('guide.login') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition text-emerald-300 hover:bg-white/10 hover:text-white">
                    <span class="text-lg">📱</span>
                    <span x-show="sidebarOpen">หน้ารับงานไกด์ (Portal) ↗</span>
                </a>

                <!-- 8. Settings -->
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-nature-deep text-white shadow-md' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">⚙️</span>
                    <span x-show="sidebarOpen">ตั้งค่าระบบ & API</span>
                </a>
            </nav>
        </div>

        <!-- Footer User Profile & Logout -->
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5" x-show="sidebarOpen">
                    <div class="w-8 h-8 rounded-full bg-nature-golden text-nature-deep font-bold flex items-center justify-center text-xs">
                        {{ mb_substr(session('admin_name', 'AD'), 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <span class="text-xs font-bold block truncate">{{ session('admin_name', 'เจ้าหน้าที่') }}</span>
                        <span class="text-[10px] text-nature-golden block font-medium">
                            {{ session('admin_role_id') == 1 ? 'เจ้าของเว็บ (Owner)' : 'ผู้ดูแลระบบ (Admin)' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-400 hover:bg-white/10 rounded-xl transition" title="ออกจากระบบ">
                        🚪
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-6 sm:px-10 sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <button type="button" @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-nature-deep p-2 rounded-xl hover:bg-gray-100 transition">
                    ☰
                </button>
                <h2 class="text-base font-bold text-nature-dark">@yield('page_title', 'ระบบจัดการหลังบ้าน')</h2>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" target="_blank" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-nature-dark rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <span>🌐</span> ดูหน้าเว็บจริง ↗
                </a>
            </div>
        </header>

        <!-- Dynamic Body Content -->
        <main class="p-6 sm:p-10 flex-1">
            @yield('admin_content')
            @yield('content')
        </main>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts เฉพาะหน้า -->
    @stack('admin_scripts')

</body>
</html>