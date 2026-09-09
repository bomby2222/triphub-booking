@extends('layouts.app')

@section('title', 'ข่าวสารและประกาศเส้นทางธรรมชาติ | TripHub')

@section('content')
<!-- Header Banner -->
<section class="bg-nature-deep py-16 px-4 sm:px-6 lg:px-8 text-center text-white relative overflow-hidden">
    <div class="max-w-4xl mx-auto relative z-10">
        <span class="text-xs font-bold uppercase tracking-widest text-nature-golden block mb-3">TRIPHUB NEWS</span>
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight mb-4">ข่าวสารและประกาศเส้นทางธรรมชาติ</h1>
        <p class="text-xs sm:text-sm text-nature-cream/80 max-w-xl mx-auto font-light leading-relaxed">
            อัปเดตสถานการณ์สภาพอากาศ ฤดูกาลเปิด-ปิดเส้นทางเดินป่า และประกาศสำคัญจากอุทยาน
        </p>
    </div>
    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-nature-forest/20 rounded-full blur-3xl pointer-events-none"></div>
</section>

<!-- Filter & News Grid -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-[500px]">
    @php
        // ดึงค่าหมวดหมู่ปัจจุบัน ป้องกันปัญหา Undefined Variable
        $currentCategory = request('category', $category ?? 'all');
        $items = $newsList ?? $announcements ?? collect();
    @endphp

    <!-- Category Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8">
        <a href="{{ route('news.index') }}" 
           class="px-5 py-2 rounded-full text-xs font-bold transition whitespace-nowrap {{ empty($currentCategory) || $currentCategory === 'all' ? 'bg-nature-dark text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            ทั้งหมด
        </a>
        <a href="{{ route('news.index', ['category' => 'general']) }}" 
           class="px-5 py-2 rounded-full text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 {{ $currentCategory === 'general' ? 'bg-nature-dark text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            📢 ข่าวสารทั่วไป
        </a>
        <a href="{{ route('news.index', ['category' => 'weather']) }}" 
           class="px-5 py-2 rounded-full text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 {{ $currentCategory === 'weather' ? 'bg-nature-dark text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            ⚠️ สภาพอากาศ
        </a>
        <a href="{{ route('news.index', ['category' => 'closed']) }}" 
           class="px-5 py-2 rounded-full text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 {{ $currentCategory === 'closed' ? 'bg-nature-dark text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            🚫 ปิดเส้นทาง
        </a>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($items as $item)
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg transition duration-200 border border-gray-100 flex flex-col justify-between">
                <div class="space-y-3">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold 
                            {{ $item->category === 'weather' ? 'bg-amber-100 text-amber-900' : ($item->category === 'closed' ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800') }}">
                            {{ $item->badge_text ?? ($item->category === 'weather' ? '⚠️ สภาพอากาศ' : ($item->category === 'closed' ? '🚫 ปิดเส้นทาง' : '📢 ข่าวสารทั่วไป')) }}
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-nature-dark leading-snug">
                        {{ $item->title }}
                    </h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        {{ $item->content }}
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-400">
                    <span>📅 {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-gray-100">
                <span class="text-4xl block mb-2">📰</span>
                <p class="text-sm font-bold text-gray-600">ไม่มีข่าวสารหรือประกาศในหมวดหมู่นี้</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($items, 'hasPages') && $items->hasPages())
        <div class="mt-10">
            {{ $items->withQueryString()->links() }}
        </div>
    @endif
</section>
@endsection