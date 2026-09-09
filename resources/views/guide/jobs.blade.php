<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>งานที่ได้รับมอบหมาย | Guide Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js สำหรับสลับแท็บ -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Prompt', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800 pb-10" x-data="{ currentTab: 'pending' }">

    <!-- Navbar -->
    <div class="bg-[#1B4332] text-white p-4 sticky top-0 z-30 shadow-md flex justify-between items-center">
        <div>
            <span class="text-xs text-[#DDA15E] block font-bold">ไกด์: {{ $guide->name }} ({{ $guide->location_area }})</span>
            <h1 class="text-base font-black">รายการงานนำทริป</h1>
        </div>
        <form action="{{ route('guide.logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs bg-white/10 px-3 py-1.5 rounded-xl text-red-200 hover:bg-white/20 transition">ออกจากระบบ</button>
        </form>
    </div>

    @php
        // แยกงานออกเป็น 2 กลุ่มอย่างชัดเจน
        $completedJobs = $jobs->filter(function($job) {
            return in_array($job->guide_status, ['paid', 'completed']) || $job->guide_payment_status === 'paid';
        });

        $pendingJobs = $jobs->reject(function($job) {
            return in_array($job->guide_status, ['paid', 'completed']) || $job->guide_payment_status === 'paid';
        });
    @endphp

    <div class="max-w-xl mx-auto p-4 space-y-5">

        <!-- Tabs Selector (สลับดู งานรอทำ vs งานที่สำเร็จแล้ว) -->
        <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-gray-200 flex gap-1">
            <button type="button" 
                    @click="currentTab = 'pending'" 
                    :class="currentTab === 'pending' ? 'bg-[#1B4332] text-white shadow' : 'text-gray-600 hover:bg-gray-50'"
                    class="flex-1 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5">
                <span>🧭 งานที่ต้องทำ</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="currentTab === 'pending' ? 'bg-[#DDA15E] text-[#1B4332]' : 'bg-gray-200 text-gray-700'">
                    {{ $pendingJobs->count() }}
                </span>
            </button>

            <button type="button" 
                    @click="currentTab = 'completed'" 
                    :class="currentTab === 'completed' ? 'bg-[#1B4332] text-white shadow' : 'text-gray-600 hover:bg-gray-50'"
                    class="flex-1 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5">
                <span>✅ งานที่ทำแล้ว</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="currentTab === 'completed' ? 'bg-[#DDA15E] text-[#1B4332]' : 'bg-gray-200 text-gray-700'">
                    {{ $completedJobs->count() }}
                </span>
            </button>
        </div>

        <!-- ================= แท็บที่ 1: งานที่ต้องทำ (Pending / รอตรวจเงิน) ================= -->
        <div x-show="currentTab === 'pending'" x-cloak class="space-y-4">
            @forelse($pendingJobs as $job)
                <a href="{{ route('guide.job_detail', $job->id) }}" class="block bg-white rounded-3xl p-5 shadow-sm border border-gray-200 hover:border-[#1B4332] hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-mono font-bold text-[#40916C]">{{ $job->booking_code }}</span>
                        
                        @if($job->guide_status === 'report_submitted' || !empty($job->report_meet_photo))
                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-full animate-pulse flex items-center gap-1 border border-amber-200">
                                <span>⏳</span> ส่งงานแล้ว (รอแอดมินโอนเงิน)
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-800 text-[10px] font-bold rounded-full flex items-center gap-1 border border-blue-200">
                                <span>🧭</span> รอดำเนินการ / นำเที่ยว
                            </span>
                        @endif
                    </div>

                    <h3 class="font-bold text-sm text-gray-900 mt-2">{{ $job->schedule->activity->name ?? '-' }}</h3>
                    <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                        <p>🗓️ วันเดินทาง: {{ $job->schedule && $job->schedule->start_date ? $job->schedule->start_date->format('d/m/Y') : '-' }}</p>
                        <p>👥 ลูกทัวร์: <span class="font-bold text-gray-800">{{ $job->seats_count }} ท่าน</span></p>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center text-xs">
                        <span class="text-gray-400 text-[11px]">รหัสทริป: #{{ $job->id }}</span>
                        <span class="font-bold text-[#1B4332] flex items-center gap-1">
                            @if($job->guide_status === 'report_submitted' || !empty($job->report_meet_photo))
                                ดูรูปรายงานที่ส่งไปแล้ว →
                            @else
                                คลิกเพื่อดูรายชื่อ & ส่งรูปรายงาน →
                            @endif
                        </span>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-3xl p-10 text-center border border-gray-200 text-gray-400 space-y-2">
                    <span class="text-3xl block">🎉</span>
                    <p class="text-xs font-medium">ไม่มีงานคงค้าง ทุกภารกิจเรียบร้อยแล้ว!</p>
                </div>
            @endforelse
        </div>

        <!-- ================= แท็บที่ 2: งานที่ทำเสร็จแล้ว (Completed / จ่ายเงินแล้ว) ================= -->
        <div x-show="currentTab === 'completed'" x-cloak class="space-y-4">
            @forelse($completedJobs as $job)
                <a href="{{ route('guide.job_detail', $job->id) }}" class="block bg-white rounded-3xl p-5 shadow-sm border border-emerald-100 hover:border-emerald-400 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-mono font-bold text-gray-400">{{ $job->booking_code }}</span>
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full flex items-center gap-1 border border-emerald-300">
                            <span>💰</span> จบทริป & ได้รับเงินแล้ว
                        </span>
                    </div>

                    <h3 class="font-bold text-sm text-gray-800 mt-2 line-through text-gray-600">{{ $job->schedule->activity->name ?? '-' }}</h3>
                    <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                        <p>🗓️ วันเดินทาง: {{ $job->schedule && $job->schedule->start_date ? $job->schedule->start_date->format('d/m/Y') : '-' }}</p>
                        <p>👥 ลูกทัวร์: {{ $job->seats_count }} ท่าน</p>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center text-xs">
                        <span class="text-[10px] text-emerald-600 font-medium">✓ ปิดงานสมบูรณ์</span>
                        <span class="font-bold text-gray-500 hover:text-[#1B4332]">ดูประวัติงานย้อนหลัง →</span>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-3xl p-10 text-center border border-gray-200 text-gray-400 space-y-2">
                    <span class="text-3xl block">📋</span>
                    <p class="text-xs font-medium">ยังไม่มีประวัติงานที่เสร็จสิ้น</p>
                </div>
            @endforelse
        </div>

    </div>

</body>
</html>