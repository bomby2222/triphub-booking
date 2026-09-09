@extends('layouts.app')

@section('title', 'โปรโมชั่น & สิทธิพิเศษ | TripHub')

@section('content')
<section class="bg-nature-deep py-12 px-4 sm:px-6 lg:px-8 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto relative z-10 text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-nature-golden block mb-1">Special Offers</span>
        <h1 class="text-3xl sm:text-5xl font-black">คูปองส่วนลดและสิทธิพิเศษ</h1>
        <p class="text-sm text-nature-cream/80 mt-2 max-w-xl mx-auto">คัดลอกโค้ดส่วนลดด้านล่างไปใส่ในหน้าชำระเงินเพื่อรับส่วนลดค่าทริปทันที</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{
    copiedCode: '',
    copy(code) {
        navigator.clipboard.writeText(code);
        this.copiedCode = code;
        setTimeout(() => this.copiedCode = '', 2500);
    }
}">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($promotions as $promo)
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 flex flex-col justify-between p-6 relative hover:shadow-xl transition">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 bg-nature-golden/20 text-nature-deep border border-nature-golden/40 rounded-full text-xs font-bold">
                            {{ $promo->discount_type === 'percent' ? 'ลด ' . (int)$promo->discount_value . '%' : 'ลด ฿' . number_format($promo->discount_value) }}
                        </span>
                        <span class="text-xs text-gray-400">คงเหลือ {{ max(0, ($promo->quota ?? 100) - ($promo->used_count ?? 0)) }} สิทธิ์</span>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-nature-dark">{{ $promo->title }}</h3>
                        <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">{{ $promo->description ?? 'ใช้เป็นส่วนลดสำหรับการจองทริปบนระบบ TripHub' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-3 text-xs space-y-1 text-gray-500">
                        <p>• ยอดจองขั้นต่ำ: {{ $promo->min_spend > 0 ? '฿' . number_format($promo->min_spend) : 'ไม่มีขั้นต่ำ' }}</p>
                        @if($promo->max_discount)
                            <p>• ส่วนลดสูงสุด: ฿{{ number_format($promo->max_discount) }}</p>
                        @endif
                        <p>• ใช้ได้ถึง: {{ $promo->end_date ? $promo->end_date->format('d/m/Y') : 'ไม่มีกำหนดหมดอายุ' }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-dashed border-gray-200 flex items-center justify-between gap-3">
                    <div class="px-4 py-2 bg-nature-cream rounded-xl font-mono font-bold text-sm text-nature-deep border border-nature-golden/40">
                        {{ $promo->code }}
                    </div>
                    <button type="button" @click="copy('{{ $promo->code }}')" class="flex-1 py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5" :class="copiedCode === '{{ $promo->code }}' ? 'bg-emerald-600 text-white' : 'bg-nature-deep hover:bg-nature-forest text-white'">
                        <span x-text="copiedCode === '{{ $promo->code }}' ? '✓ คัดลอกแล้ว!' : '📋 คัดลอกโค้ด'"></span>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-gray-100">
                <span class="text-4xl block mb-2">🎟️</span>
                <p class="text-sm font-bold text-gray-600">ยังไม่มีโปรโมชั่นหรือคูปองส่วนลดในขณะนี้</p>
            </div>
        @endforelse
    </div>
</section>
@endsection