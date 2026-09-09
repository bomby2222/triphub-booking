@extends('layouts.admin')

@section('title', 'จัดการข้อมูลไกด์ | TripHub Admin')
@section('page_title', '🧭 จัดการทีมไกด์ประจำพื้นที่ (Guides Management)')

@section('admin_content')
<div class="space-y-6" x-data="{ createModal: false }">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-nature-dark">รายชื่อไกด์และพื้นที่ประจำการ</h3>
            <p class="text-xs text-gray-400">สร้างรหัสผ่านให้ไกด์ใช้ล็อกอินเข้าระบบรับงาน และส่งรูปรายงาน 3 ขั้นตอน</p>
        </div>
        <button type="button" @click="createModal = true" class="px-5 py-2.5 bg-nature-deep hover:bg-nature-forest text-white text-xs font-bold rounded-2xl shadow transition flex items-center gap-1.5">
            <span>➕</span> เพิ่มไกด์ใหม่ & สร้างรหัสเข้าใช้งาน
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Guides Table -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                        <th class="p-4">รหัสไกด์ (Username)</th>
                        <th class="p-4">ชื่อ - นามสกุล</th>
                        <th class="p-4">เบอร์ติดต่อ</th>
                        <th class="p-4">ไกด์ประจำพื้นที่ / จังหวัด</th>
                        <th class="p-4">ข้อมูลบัญชีรับเงิน</th>
                        <th class="p-4 text-center">งานที่ดูแล</th>
                        <th class="p-4 text-center">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($guides as $g)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 font-mono font-bold text-nature-forest">{{ $g->guide_code }}</td>
                            <td class="p-4 font-bold text-nature-dark">{{ $g->name }}</td>
                            <td class="p-4 font-mono">{{ $g->phone }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 bg-nature-cream text-nature-deep font-bold rounded-lg border border-nature-golden/40">
                                    📍 {{ $g->location_area }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-gray-700 block">{{ $g->bank_name }}</span>
                                <span class="font-mono text-gray-500">{{ $g->bank_account_no }} ({{ $g->bank_account_name }})</span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg">
                                    {{ $g->active_jobs }} งาน
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.guides.destroy', $g->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบไกด์ท่านนี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition font-bold">
                                        🗑️ ลบ
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400">ยังไม่มีข้อมูลไกด์ในระบบ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $guides->links() }}</div>
    </div>

    <!-- Modal เพิ่มไกด์ใหม่ -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div @click.outside="createModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <h4 class="font-bold text-base text-nature-dark">➕ เพิ่มไกด์ใหม่ & สร้างรหัสเข้าใช้งาน</h4>
                <button @click="createModal = false" class="text-gray-400 text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('admin.guides.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-gray-700 uppercase mb-1">รหัสไกด์ / Username *</label>
                        <input type="text" name="guide_code" required placeholder="เช่น GD01, GUIDE-KHAOYAI" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-nature-forest font-mono">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 uppercase mb-1">รหัสผ่านเข้าเว็บ *</label>
                        <input type="password" name="password" required placeholder="อย่างน้อย 6 ตัวอักษร" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-nature-forest">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-gray-700 uppercase mb-1">ชื่อ - นามสกุล *</label>
                        <input type="text" name="name" required placeholder="นายสมชาย เดินป่า" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-nature-forest">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 uppercase mb-1">เบอร์โทรศัพท์ *</label>
                        <input type="text" name="phone" required placeholder="0812345678" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-nature-forest font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase mb-1">📍 ไกด์ประจำพื้นที่ / เส้นทางไหน *</label>
                    <input type="text" name="location_area" required placeholder="เช่น อุทยานฯ รามคำแหง (สุโขทัย), ดอยหลวงเชียงดาว, ภูกระดึง" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-nature-forest">
                </div>

                <div class="border-t pt-3 space-y-3">
                    <span class="font-bold text-gray-700 block">💳 บัญชีธนาคารสำหรับรับโอนค่าจ้าง:</span>
                    <div class="grid grid-cols-3 gap-3">
                        <input type="text" name="bank_name" required placeholder="ชื่อธนาคาร (เช่น กสิกร)" class="bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none">
                        <input type="text" name="bank_account_no" required placeholder="เลขที่บัญชี" class="bg-gray-50 border border-gray-200 rounded-xl p-3 font-mono focus:outline-none">
                        <input type="text" name="bank_account_name" required placeholder="ชื่อบัญชี" class="bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none">
                    </div>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="createModal = false" class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl">ยกเลิก</button>
                    <button type="submit" class="flex-1 py-3 bg-nature-deep text-white font-bold rounded-xl shadow">💾 บันทึกข้อมูลไกด์</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection