<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบทีมไกด์ | TripHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Prompt', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 shadow-xl border border-gray-100 space-y-6">
        <div class="text-center">
            <span class="w-16 h-16 bg-[#1B4332]/10 text-3xl rounded-2xl flex items-center justify-center mx-auto mb-3">🧭</span>
            <h2 class="text-xl font-black text-[#1B4332]">ระบบงานไกด์นำเที่ยว</h2>
            <p class="text-xs text-gray-500 mt-1">TripHub Guide Portal</p>
        </div>

        @if(session('error'))
            <div class="p-3.5 rounded-xl bg-red-50 text-red-700 text-xs font-bold border border-red-200">
                ❌ {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('guide.login.submit') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-gray-700 mb-1">รหัสไกด์ (Guide Code / Username)</label>
                <input type="text" name="guide_code" required placeholder="เช่น GD01" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-sm font-mono focus:ring-2 focus:ring-[#1B4332] focus:outline-none">
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1">รหัสผ่าน (Password)</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-[#1B4332] focus:outline-none">
            </div>

            <button type="submit" class="w-full py-3.5 bg-[#1B4332] hover:bg-[#40916C] text-white font-bold rounded-xl shadow transition">
                🚀 เข้าสู่ระบบรับงาน
            </button>
        </form>
    </div>
</body>
</html>