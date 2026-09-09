<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบผู้ดูแลระบบ | TripHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Prompt', 'sans-serif'] },
                    colors: {
                        nature: { deep: '#1B4332', forest: '#40916C', golden: '#DDA15E', cream: '#F5F3EA', dark: '#1D2924' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-nature-dark min-h-screen flex items-center justify-center p-4 relative overflow-hidden font-sans">

    <!-- Background Glow -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-nature-forest/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-nature-golden/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl text-white relative z-10">
        
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/image.png') }}';" alt="TripHub Logo" class="h-16 w-auto mx-auto mb-3 bg-white/10 p-1.5 rounded-2xl shadow-lg border border-white/20">
            <h1 class="text-2xl font-black tracking-tight">TripHub Panel</h1>
            <p class="text-xs text-nature-golden mt-1 font-medium">ระบบบริหารจัดการสำหรับแอดมินและเจ้าของเว็บ</p>
        </div>

        @if(session('error'))
            <div class="mb-5 p-3.5 rounded-2xl bg-red-500/20 border border-red-500/50 text-red-200 text-xs font-medium text-center">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-5 p-3.5 rounded-2xl bg-emerald-500/20 border border-emerald-500/50 text-emerald-200 text-xs font-medium text-center">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-white/80 mb-1.5">อีเมลผู้ดูแลระบบ</label>
                <input type="email" name="email" required placeholder="owner@triphub.local" class="w-full bg-white/10 border border-white/20 rounded-2xl p-3.5 text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-nature-golden focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-white/80 mb-1.5">รหัสผ่าน</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-white/10 border border-white/20 rounded-2xl p-3.5 text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-nature-golden focus:outline-none">
            </div>

            <button type="submit" class="w-full py-4 mt-2 bg-nature-golden hover:bg-[#c9904d] text-nature-deep font-bold text-sm rounded-2xl shadow-lg transition">
                เข้าสู่ระบบผู้ดูแล
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-white/10 text-center">
            <a href="{{ url('/') }}" class="text-xs text-white/60 hover:text-white transition">
                ← กลับสู่หน้าแรกเว็บไซต์
            </a>
        </div>
    </div>

</body>
</html>