@extends('layouts.app')

@section('title', 'ทริปเดินป่าและท่องเที่ยวธรรมชาติทั้งหมด | TripHub')

@section('content')
<!-- Header Banner -->
<section class="bg-nature-deep py-12 px-4 sm:px-6 lg:px-8 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto relative z-10">
        <span class="text-xs font-bold uppercase tracking-widest text-nature-golden block mb-1">Explore Outdoors</span>
        <h1 class="text-3xl sm:text-4xl font-black">ทริปเดินป่าและธรรมชาติทั้งหมดทั่วไทย</h1>
        <p class="text-sm text-nature-cream/80 mt-2 max-w-2xl">เลือกจุดหมายในฝันของคุณ ไม่ว่าจะเป็นเส้นทางเดินป่าสัมผัสหมอก ปีนเขาสันมีดหมอ กางเต็นท์ หรือลุยน้ำตก</p>
    </div>
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-nature-forest/20 rounded-full blur-2xl pointer-events-none"></div>
</section>

<!-- Main Filter & Grid -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Filter Bar Card (เพิ่มฟิลด์ค้นหาประเภททริปแล้ว) -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mb-10">
        <form action="{{ route('trips.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
            <!-- Keyword -->
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">🔍 ค้นหาทริป</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="ชื่อทริป หรือสถานที่..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-3 py-2.5 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
            </div>

            <!-- Category Filter (เพิ่มใหม่) -->
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">🏷️ ประเภททริป</label>
                <select name="category" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-3 py-2.5 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
                    <option value="">ทุกประเภท</option>
                    <option value="hiking" {{ request('category') == 'hiking' ? 'selected' : '' }}>🥾 เดินป่า (Hiking)</option>
                    <option value="climbing" {{ request('category') == 'climbing' ? 'selected' : '' }}>🧗 ปีนเขา (Climbing)</option>
                    <option value="camping" {{ request('category') == 'camping' ? 'selected' : '' }}>⛺ แคมป์ปิ้ง (Camping)</option>
                    <option value="waterfall" {{ request('category') == 'waterfall' ? 'selected' : '' }}>🌊 ลุยน้ำตก (Waterfall)</option>
                    <option value="nature_study" {{ request('category') == 'nature_study' ? 'selected' : '' }}>🌿 ศึกษาธรรมชาติ</option>
                </select>
            </div>

            <!-- Province -->
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">📍 จังหวัด</label>
                <select name="province" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-3 py-2.5 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
                    <option value="">ทุกจังหวัด</option>
                    @foreach(['สุโขทัย', 'กาญจนบุรี', 'เลย', 'สุราษฎร์ธานี', 'นครนายก', 'เชียงใหม่', 'น่าน'] as $prov)
                        <option value="{{ $prov }}" {{ request('province') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Difficulty -->
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">⛰️ ระดับความยาก</label>
                <select name="difficulty" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-3 py-2.5 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
                    <option value="">ทุกระดับ</option>
                    <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>ระดับง่าย (Easy)</option>
                    <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>ระดับปานกลาง (Medium)</option>
                    <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>ระดับยาก (Hard)</option>
                    <option value="extreme" {{ request('difficulty') == 'extreme' ? 'selected' : '' }}>ระดับท้าทาย (Extreme)</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">📊 เรียงตาม</label>
                <select name="sort" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-3 py-2.5 text-xs focus:ring-2 focus:ring-nature-forest focus:outline-none">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>ทริปใหม่ล่าสุด</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>ราคา: ต่ำไปสูง</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>ราคา: สูงไปต่ำ</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-nature-deep hover:bg-nature-forest text-white font-bold text-xs rounded-2xl transition shadow-md">
                    กรองข้อมูล
                </button>
                <a href="{{ route('trips.index') }}" class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-xs rounded-2xl transition text-center" title="ล้างค่า">
                    รีเซ็ต
                </a>
            </div>
        </form>
    </div>

    <!-- Trips Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($activities as $act)
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col group">
                <div class="relative h-60 overflow-hidden">
                    <img src="{{ $act->cover_image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80' }}" alt="{{ $act->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    
                    <div class="absolute top-4 left-4 flex gap-2 flex-wrap">
                        @if(isset($act->badge) && $act->badge !== 'NONE')
                            <span class="bg-red-500/90 text-white backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                🔥 {{ $act->badge }}
                            </span>
                        @endif

                        <!-- ป้ายแสดงหมวดหมู่ทริป -->
                        <span class="bg-nature-deep/90 text-nature-golden backdrop-blur-md px-3 py-1 rounded-full text-xs font-medium border border-nature-golden/40">
                            @if($act->category === 'climbing') 🧗 ปีนเขา
                            @elseif($act->category === 'camping') ⛺ แคมป์ปิ้ง
                            @elseif($act->category === 'waterfall') 🌊 น้ำตก
                            @elseif($act->category === 'nature_study') 🌿 ศึกษาธรรมชาติ
                            @else 🥾 เดินป่า
                            @endif
                        </span>
                    </div>

                    <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-md text-white px-2.5 py-1 rounded-xl text-xs font-medium">
                        📍 จ.{{ $act->province }}
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                            <span class="font-bold text-nature-forest">● ระดับ {{ ucfirst($act->difficulty_level) }}</span>
                            <span class="bg-gray-100 px-2.5 py-0.5 rounded-lg text-[11px] font-semibold text-gray-600">{{ $act->duration_text }}</span>
                        </div>
                        <h3 class="text-base font-bold text-nature-dark group-hover:text-nature-forest transition line-clamp-1">
                            {{ $act->name }}
                        </h3>
                        <p class="text-xs text-gray-600 mt-2 line-clamp-2 leading-relaxed">
                            {{ $act->description }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-gray-400 block">ราคาเริ่มต้น</span>
                            <span class="text-xl font-extrabold text-nature-deep">฿{{ number_format($act->base_price) }} <span class="text-xs font-normal text-gray-500">/ ท่าน</span></span>
                        </div>
                        <a href="{{ route('trips.show', $act->id) }}" class="px-4 py-2.5 rounded-xl bg-nature-deep hover:bg-nature-forest text-white font-bold text-xs transition shadow-md">
                            ดูรอบและจอง
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-gray-100">
                <span class="text-4xl block mb-2">🏕️</span>
                <p class="text-sm font-bold text-gray-600">ไม่พบทริปตามเงื่อนไขที่คุณเลือก</p>
                <a href="{{ route('trips.index') }}" class="mt-3 inline-block text-xs text-nature-forest font-bold underline">ดูทริปทั้งหมด</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $activities->withQueryString()->links() }}
    </div>
</section>
@endsection