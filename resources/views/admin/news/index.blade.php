@extends('layouts.admin')

@section('title', 'จัดการข่าวสาร & ประกาศ | TripHub Panel')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800">จัดการข่าวสารและประกาศ</h1>
            <p class="text-xs text-gray-500 mt-1">อัปเดตสถานการณ์ แจ้งเตือนสภาพอากาศ และประกาศเปิด-ปิดเส้นทาง</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-nature-deep hover:bg-nature-forest text-white rounded-2xl text-xs font-bold shadow-md transition">
            + สร้างประกาศใหม่
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-500 uppercase font-bold border-b border-gray-100">
                    <tr>
                        <th class="p-4">ประเภท</th>
                        <th class="p-4">หัวข้อข่าวสาร</th>
                        <th class="p-4">วันที่ลงประกาศ</th>
                        <th class="p-4 text-center">สถานะ</th>
                        <th class="p-4 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($newsList as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold 
                                    {{ $item->category === 'weather' ? 'bg-amber-100 text-amber-900' : ($item->category === 'closed' ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800') }}">
                                    {{ $item->badge_text }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-gray-800 block text-sm">{{ $item->title }}</span>
                                <span class="text-[11px] text-gray-400 line-clamp-1">{{ $item->content }}</span>
                            </td>
                            <td class="p-4 text-gray-500">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.news.toggle', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1 rounded-full text-[11px] font-bold transition {{ $item->is_published ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                        {{ $item->is_published ? 'เผยแพร่' : 'ซ่อน' }}
                                    </button>
                                </form>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.news.edit', $item->id) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-700 font-bold transition">
                                    แก้ไข
                                </a>
                                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันที่จะลบประกาศนี้?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold transition">
                                        ลบ
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400">ยังไม่มีข้อมูลข่าวสารในระบบ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($newsList->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $newsList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection