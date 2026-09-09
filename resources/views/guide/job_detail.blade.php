<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานทริป | Guide Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Prompt', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen pb-10">
    <div class="bg-[#1B4332] text-white p-4 sticky top-0 z-30 shadow flex items-center justify-between">
        <a href="{{ route('guide.jobs') }}" class="text-xs text-[#DDA15E] font-bold">← ย้อนกลับ</a>
        <h1 class="text-sm font-bold">รหัส: {{ $job->booking_code }}</h1>
        <div></div>
    </div>

    <div class="max-w-xl mx-auto p-4 space-y-6">

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                🎉 {{ session('success') }}
            </div>
        @endif

        <!-- ข้อมูลทริป -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border space-y-2 text-xs">
            <h2 class="text-sm font-bold text-[#1B4332]">{{ $job->schedule->activity->name }}</h2>
            <p><strong>วันเดินทาง:</strong> {{ $job->schedule->start_date->format('d/m/Y') }} - {{ $job->schedule->end_date->format('d/m/Y') }}</p>
            <p><strong>ผู้ติดต่อหลัก:</strong> {{ $job->user->name }} (โทร: {{ $job->user->phone ?? '-' }})</p>
        </div>

        <!-- รายชื่อลูกทัวร์ & ข้อมูลโรคประจำตัว -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border space-y-3">
            <h3 class="font-bold text-xs text-gray-800 uppercase">👥 รายชื่อลูกทัวร์ & ข้อมูลสุขภาพสำคัญ</h3>
            <div class="space-y-2 text-xs">
                @foreach($job->members as $idx => $m)
                    <div class="p-3 bg-gray-50 rounded-xl border flex justify-between items-center">
                        <div>
                            <span class="font-bold block">{{ $idx + 1 }}. {{ $m->full_name }}</span>
                            <span class="text-gray-400 font-mono text-[11px]">{{ $m->phone }}</span>
                        </div>
                        <div>
                            @if($m->medical_conditions)
                                <span class="px-2 py-1 bg-red-100 text-red-700 font-bold rounded text-[10px]">⚠️ {{ $m->medical_conditions }}</span>
                            @else
                                <span class="text-emerald-700 text-[10px]">สุขภาพปกติ</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ฟอร์มส่งรายงานรูปภาพ 3 รูป -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border space-y-4">
            <div class="border-b pb-2">
                <h3 class="font-bold text-sm text-[#1B4332]">📸 ถ่ายรูปรายงานการปฏิบัติงาน (3 รูป)</h3>
                <p class="text-[11px] text-gray-500">ถ่ายรูปยืนยันทั้ง 3 จุดเพื่อส่งให้แอดมินโอนเงินค่าจ้าง</p>
            </div>

            @if($job->guide_status === 'paid')
                <div class="p-4 bg-emerald-50 text-emerald-800 text-center rounded-2xl font-bold text-xs border border-emerald-200">
                    💰 แอดมินโอนเงินค่าจ้างให้คุณเรียบร้อยแล้ว ขอบคุณสำหรับการปฏิบัติหน้าที่!
                </div>
            @elseif($job->guide_status === 'report_submitted')
                <div class="p-4 bg-amber-50 text-amber-800 text-center rounded-2xl font-bold text-xs border border-amber-200 animate-pulse">
                    ⏳ คุณส่งรายงาน 3 รูปแล้ว อยู่ระหว่างรอแอดมินตรวจสอบและโอนเงินเข้าบัญชีของคุณ
                </div>
            @else
                <form action="{{ route('guide.submit_report', $job->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf

                    <!-- รูปที่ 1: เจอลูกค้า -->
                    <div class="p-3.5 bg-gray-50 rounded-2xl border">
                        <label class="block font-bold text-gray-800 mb-1">1. รูปเจอลูกค้า / จุดนัดพบบริการ *</label>
                        <input type="file" name="photo_meet" required accept="image/*" capture="environment" class="w-full text-xs text-gray-500 file:py-2 file:px-3 file:rounded-xl file:border-0 file:bg-[#1B4332] file:text-white">
                    </div>

                    <!-- รูปที่ 2: เริ่มเดินทาง -->
                    <div class="p-3.5 bg-gray-50 rounded-2xl border">
                        <label class="block font-bold text-gray-800 mb-1">2. รูปเริ่มเดินทาง / จุดปล่อยตัวเดินเท้า *</label>
                        <input type="file" name="photo_start" required accept="image/*" capture="environment" class="w-full text-xs text-gray-500 file:py-2 file:px-3 file:rounded-xl file:border-0 file:bg-[#1B4332] file:text-white">
                    </div>

                    <!-- รูปที่ 3: จบทริป -->
                    <div class="p-3.5 bg-gray-50 rounded-2xl border">
                        <label class="block font-bold text-gray-800 mb-1">3. รูปจบทริป / ส่งลูกทัวร์ปลอดภัย *</label>
                        <input type="file" name="photo_end" required accept="image/*" capture="environment" class="w-full text-xs text-gray-500 file:py-2 file:px-3 file:rounded-xl file:border-0 file:bg-[#1B4332] file:text-white">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">หมายเหตุเพิ่มเติมถึงแอดมิน (ถ้ามี)</label>
                        <textarea name="notes" rows="2" placeholder="เช่น ทุกคนกลับลงมาปลอดภัย มีอาการเหนื่อยล้าเล็กน้อย" class="w-full bg-gray-50 border rounded-xl p-3 text-xs"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-[#1B4332] to-[#40916C] text-white font-bold rounded-2xl shadow transition">
                        📤 กดส่งรายงาน 3 รูป & รอแอดมินโอนเงิน
                    </button>
                </form>
            @endif
        </div>

    </div>
</body>
</html>