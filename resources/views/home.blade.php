@extends('layouts.app')

@section('title', 'TripHub (เดินกับเรา) - จองทริปเดินป่า ปีนเขา กางเต็นท์ ชมน้ำตกทั่วไทย')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[640px] lg:min-h-[720px] flex items-center justify-center bg-nature-deep overflow-hidden">
    <!-- Background Hero Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=1920&q=85" alt="Hiking Nature" class="w-full h-full object-cover object-center opacity-35 transform scale-105 transition-transform duration-1000">
        <div class="absolute inset-0 bg-gradient-to-t from-nature-deep via-nature-deep/60 to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        
        <!-- Logo Emblem & Season Badge -->
        <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-white/10 border border-white/20 backdrop-blur-md mb-6 shadow-xl">
            <img src="{{ asset('images/logo.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/image.png') }}';" alt="TripHub Logo" class="w-8 h-8 object-contain">
            <span class="text-white text-xs font-semibold tracking-wider">
                TripHub • ชุมชนคนรักการเดินทางธรรมชาติ "เดินกับเรา"
            </span>
        </div>

        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-tight mb-6 drop-shadow-md">
            เปิดประสบการณ์ใหม่ <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-nature-golden via-amber-200 to-nature-cream">
                เดินป่า ปีนเขา กางเต็นท์ & ชมน้ำตก
            </span>
        </h1>
        
        <p class="text-base sm:text-lg text-nature-cream/90 max-w-2xl mx-auto mb-8 font-light leading-relaxed">
            จองทริปธรรมชาติครบวงจรทั่วไทย จองง่าย ตัดสต็อกที่นั่งทันที ตรวจสลิปอัตโนมัติ พร้อมไกด์ท้องถิ่นผู้เชี่ยวชาญดูแลตลอดเส้นทาง
        </p>

        <!-- 4 Core Activity Badges (กดคลิกเพื่อกรองหมวดหมู่ได้ทันที) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-3xl mx-auto mb-10">
            <a href="{{ url('/trips?category=hiking') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md rounded-2xl p-3 text-white transition flex items-center justify-center gap-2 shadow-sm group">
                <span class="text-xl group-hover:scale-110 transition">🥾</span>
                <span class="text-xs font-bold">เดินป่า (Hiking)</span>
            </a>
            <a href="{{ url('/trips?category=climbing') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md rounded-2xl p-3 text-white transition flex items-center justify-center gap-2 shadow-sm group">
                <span class="text-xl group-hover:scale-110 transition">🧗</span>
                <span class="text-xs font-bold">ปีนเขา (Climbing)</span>
            </a>
            <a href="{{ url('/trips?category=camping') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md rounded-2xl p-3 text-white transition flex items-center justify-center gap-2 shadow-sm group">
                <span class="text-xl group-hover:scale-110 transition">⛺</span>
                <span class="text-xs font-bold">แคมป์ปิ้ง (Camping)</span>
            </a>
            <a href="{{ url('/trips?category=waterfall') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md rounded-2xl p-3 text-white transition flex items-center justify-center gap-2 shadow-sm group">
                <span class="text-xl group-hover:scale-110 transition">🌊</span>
                <span class="text-xs font-bold">ชมน้ำตก (Waterfalls)</span>
            </a>
        </div>

        <!-- Glassmorphism Floating Search Box (เพิ่มช่องเลือกประเภททริป) -->
        <div class="max-w-5xl mx-auto glass-card rounded-3xl p-4 sm:p-6 shadow-2xl text-left text-nature-dark border border-white/60">
            <form action="{{ url('/trips') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                
                <!-- Location / Province Filter -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-nature-deep mb-1.5">📍 จุดหมาย / จังหวัด</label>
                    <select name="province" class="w-full bg-white/90 border border-gray-200 rounded-xl px-3 py-2.5 text-xs font-medium focus:ring-2 focus:ring-nature-forest focus:outline-none">
                        <option value="">ทุกจังหวัดทั่วไทย</option>
                        <option value="สุโขทัย" {{ request('province') == 'สุโขทัย' ? 'selected' : '' }}>สุโขทัย (เขาหลวง)</option>
                        <option value="กาญจนบุรี" {{ request('province') == 'กาญจนบุรี' ? 'selected' : '' }}>กาญจนบุรี (เขาช้างเผือก)</option>
                        <option value="เลย" {{ request('province') == 'เลย' ? 'selected' : '' }}>เลย (ภูกระดึง)</option>
                        <option value="สุราษฎร์ธานี" {{ request('province') == 'สุราษฎร์ธานี' ? 'selected' : '' }}>สุราษฎร์ธานี (เขาสก)</option>
                        <option value="นครนายก" {{ request('province') == 'นครนายก' ? 'selected' : '' }}>นครนายก (เขาช่องลม)</option>
                        <option value="เชียงใหม่" {{ request('province') == 'เชียงใหม่' ? 'selected' : '' }}>เชียงใหม่ (เชียงดาว)</option>
                    </select>
                </div>

                <!-- Category Filter (เพิ่มใหม่) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-nature-deep mb-1.5">🏷️ ประเภททริป</label>
                    <select name="category" class="w-full bg-white/90 border border-gray-200 rounded-xl px-3 py-2.5 text-xs font-medium focus:ring-2 focus:ring-nature-forest focus:outline-none">
                        <option value="">ทุกประเภท</option>
                        <option value="hiking" {{ request('category') == 'hiking' ? 'selected' : '' }}>🥾 เดินป่า (Hiking)</option>
                        <option value="climbing" {{ request('category') == 'climbing' ? 'selected' : '' }}>🧗 ปีนเขา (Climbing)</option>
                        <option value="camping" {{ request('category') == 'camping' ? 'selected' : '' }}>⛺ แคมป์ปิ้ง (Camping)</option>
                        <option value="waterfall" {{ request('category') == 'waterfall' ? 'selected' : '' }}>🌊 ลุยน้ำตก (Waterfall)</option>
                        <option value="nature_study" {{ request('category') == 'nature_study' ? 'selected' : '' }}>🌿 ศึกษาธรรมชาติ</option>
                    </select>
                </div>

                <!-- Difficulty Filter -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-nature-deep mb-1.5">⛰️ ระดับความยาก</label>
                    <select name="difficulty" class="w-full bg-white/90 border border-gray-200 rounded-xl px-3 py-2.5 text-xs font-medium focus:ring-2 focus:ring-nature-forest focus:outline-none">
                        <option value="">ทุกระดับ</option>
                        <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>ระดับง่าย (Easy)</option>
                        <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>ระดับปานกลาง (Medium)</option>
                        <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>ระดับยาก (Hard)</option>
                        <option value="extreme" {{ request('difficulty') == 'extreme' ? 'selected' : '' }}>ระดับพิเศษ (Extreme)</option>
                    </select>
                </div>

                <!-- Travel Date / Month -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-nature-deep mb-1.5">📅 เดือนเดินทาง</label>
                    <input type="month" name="month" value="{{ request('month', '2026-09') }}" class="w-full bg-white/90 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-nature-forest focus:outline-none">
                </div>

                <!-- Search CTA Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-nature-deep to-nature-forest hover:from-[#143326] hover:to-[#357759] text-white font-bold py-3 px-4 rounded-xl transition-all shadow-lg hover:shadow-nature-forest/40 flex items-center justify-center gap-1.5 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        ค้นหาทริป
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Stats Highlights -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card rounded-2xl p-5 text-center shadow-lg border border-white/60">
            <span class="text-3xl font-extrabold text-nature-deep block">50+</span>
            <span class="text-xs text-nature-forest font-semibold uppercase">เส้นทางธรรมชาติทั่วไทย</span>
        </div>
        <div class="glass-card rounded-2xl p-5 text-center shadow-lg border border-white/60">
            <span class="text-3xl font-extrabold text-nature-deep block">100%</span>
            <span class="text-xs text-nature-forest font-semibold uppercase">ไกด์ผ่านการอบรมดูแลใกล้ชิด</span>
        </div>
        <div class="glass-card rounded-2xl p-5 text-center shadow-lg border border-white/60">
            <span class="text-3xl font-extrabold text-nature-deep block">4.9 ★</span>
            <span class="text-xs text-nature-forest font-semibold uppercase">รีวิวความพึงพอใจ</span>
        </div>
        <div class="glass-card rounded-2xl p-5 text-center shadow-lg border border-white/60">
            <span class="text-3xl font-extrabold text-nature-deep block">12k+</span>
            <span class="text-xs text-nature-forest font-semibold uppercase">นักผจญภัยร่วมเดินทาง</span>
        </div>
    </div>
</section>

<!-- Featured Trips Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12">
        <div>
            <span class="text-nature-forest font-semibold text-xs tracking-wider uppercase">TripHub Recommended</span>
            <h2 class="text-3xl font-bold text-nature-dark mt-1">ทริปยอดฮิต แนะนำสำหรับคุณ</h2>
            <p class="text-xs text-gray-500 mt-1">เดินป่า • ปีนเขา • กางเต็นท์นอนดูดาว • ลุยน้ำตกและลำธาร</p>
        </div>
        <a href="{{ url('/trips') }}" class="mt-4 sm:mt-0 inline-flex items-center gap-1.5 text-nature-forest hover:text-nature-deep font-bold text-sm transition">
            ดูทริปทั้งหมด <span>→</span>
        </a>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @if(isset($activities) && $activities->count() > 0)
            @foreach($activities as $act)
                <div class="bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition duration-300 border border-gray-100 flex flex-col group">
                    <div class="relative h-60 overflow-hidden">
                        <img src="{{ $act->cover_image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80' }}" alt="{{ $act->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        
                        <!-- Badges -->
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
                            <a href="{{ url('/trips/' . $act->id) }}" class="px-4 py-2.5 rounded-xl bg-nature-deep hover:bg-nature-forest text-white font-bold text-xs transition shadow-md">
                                ดูรายละเอียด
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Card ตัวอย่าง -->
            <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-gray-100 flex flex-col group">
                <div class="relative h-60 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80" alt="เขาหลวงสุโขทัย" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow">🔥 HOT</span>
                        <span class="bg-nature-deep text-nature-golden px-3 py-1 rounded-full text-xs font-medium">🥾 เดินป่า</span>
                    </div>
                    <div class="absolute bottom-3 right-3 bg-black/60 text-white px-2.5 py-1 rounded-xl text-xs">📍 สุโขทัย</div>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="font-bold text-orange-600">● ระดับยาก (Hard)</span>
                            <span class="bg-gray-100 px-2.5 py-0.5 rounded-lg text-[11px] text-gray-600">2 วัน 1 คืน</span>
                        </div>
                        <h3 class="text-base font-bold text-nature-dark mt-1">ทริปเดินป่าพิชิตยอดเขาหลวงสุโขทัย</h3>
                        <p class="text-xs text-gray-600 mt-2">ชมวิวทะเลหมอก 360 องศา สัมผัสความหนาวเย็นและพระอาทิตย์ตกดิน ณ ผานารายณ์</p>
                    </div>
                    <div class="mt-6 pt-4 border-t flex items-center justify-between">
                        <span class="text-xl font-extrabold text-nature-deep">฿2,890 <span class="text-xs text-gray-500 font-normal">/ ท่าน</span></span>
                        <a href="{{ url('/trips/1') }}" class="px-4 py-2 bg-nature-deep text-white rounded-xl text-xs font-bold">ดูรายละเอียด</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Call to Action Banner -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
    <div class="rounded-3xl bg-gradient-to-r from-nature-deep to-nature-forest p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="relative z-10 max-w-xl">
            <span class="text-nature-golden font-semibold text-xs tracking-wider uppercase">Member Privilege</span>
            <h3 class="text-2xl sm:text-3xl font-bold mt-2 mb-3">รับส่วนลด 100 บาท ทันทีในการจองครั้งแรก</h3>
            <p class="text-white/80 text-sm leading-relaxed">
                กรอกโค้ด <span class="font-bold text-nature-golden bg-white/10 px-2 py-0.5 rounded border border-nature-golden/40">HIKING100</span> ในขั้นตอนชำระเงิน สมัครสมาชิก TripHub วันนี้พร้อมสะสมแต้มแลกอุปกรณ์เดินป่า
            </p>
        </div>
        <div class="relative z-10 flex-shrink-0">
            <a href="{{ url('/trips') }}" class="px-8 py-4 rounded-full bg-nature-golden hover:bg-[#c9904d] text-nature-deep font-bold transition shadow-xl hover:scale-105 inline-block text-sm">
                สำรวจทริปทั้งหมด
            </a>
        </div>
        <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-white/5 pointer-events-none"></div>
    </div>
</section>
@endsection