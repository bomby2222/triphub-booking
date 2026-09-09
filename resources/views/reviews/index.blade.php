@extends('layouts.app')

@section('title', 'รีวิวจากเพื่อนร่วมทริป | TripHub')

@section('content')
<!-- Header Banner -->
<section class="bg-nature-deep py-12 px-4 sm:px-6 lg:px-8 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="max-w-xl">
            <span class="text-xs font-bold uppercase tracking-widest text-nature-golden block mb-1">Real Experiences</span>
            <h1 class="text-3xl sm:text-5xl font-black">ความประทับใจจากเพื่อนร่วมทริป</h1>
            <p class="text-sm text-nature-cream/80 mt-2">รีวิวจากนักเดินทางตัวจริงที่ร่วมก้าวเดินไปกับไกด์และทีมงาน TripHub</p>
        </div>

        <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 text-center shrink-0 w-full sm:w-auto">
            <span class="text-4xl font-black text-nature-golden block">4.9</span>
            <div class="text-amber-400 text-sm tracking-widest my-1">★★★★★</div>
            <span class="text-xs text-white/80">คะแนนความพึงพอใจเฉลี่ย</span>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10" x-data="{
    rating: 5,
    hoverRating: 5,
    previewImages: [],
    handleFiles(event) {
        this.previewImages = [];
        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            this.previewImages.push(URL.createObjectURL(files[i]));
        }
    }
}">

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3">
            <span class="text-2xl">🎉</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- ✍️ กล่องเขียนรีวิว (แสดงเฉพาะเมื่อล็อกอินแล้ว) -->
    @auth
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-gray-100">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-nature-golden text-nature-deep font-bold flex items-center justify-center text-sm shadow">
                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-base font-bold text-nature-dark">แชร์ประสบการณ์การเดินทางของคุณ ({{ Auth::user()->name }})</h3>
                    <p class="text-xs text-gray-400">ความคิดเห็นและภาพถ่ายของคุณจะช่วยเป็นแนวทางให้เพื่อนนักเดินป่าคนอื่นๆ</p>
                </div>
            </div>

            <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- เลือกทริป -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">📍 เลือกทริปที่ต้องการรีวิว *</label>
                        <select name="activity_id" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3 text-xs font-medium focus:ring-2 focus:ring-nature-forest focus:outline-none">
                            <option value="">-- กรุณาเลือกทริป --</option>
                            @foreach($activities as $act)
                                <option value="{{ $act->id }}">{{ $act->name }} (จ.{{ $act->province }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ให้คะแนนดาว (Interactive Star Rating) -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">⭐ ให้คะแนนความประทับใจ *</label>
                        <div class="flex items-center gap-1.5 h-11">
                            <input type="hidden" name="rating" :value="rating">
                            <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                <button type="button" 
                                        @click="rating = star" 
                                        @mouseenter="hoverRating = star" 
                                        @mouseleave="hoverRating = rating"
                                        class="text-2xl transition focus:outline-none"
                                        :class="(hoverRating >= star) ? 'text-amber-400 scale-110' : 'text-gray-300'">
                                    ★
                                </button>
                            </template>
                            <span class="text-xs font-bold text-gray-500 ml-2" x-text="rating + ' / 5 ดาว'"></span>
                        </div>
                    </div>
                </div>

                <!-- ข้อความรีวิว -->
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">💬 รายละเอียดรีวิว & ความประทับใจ *</label>
                    <textarea name="comment" required rows="3" placeholder="เล่าถึงเส้นทาง ความยากง่าย บรรยากาศ การดูแลของไกด์ หรืออาหารในทริป..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3.5 text-xs text-nature-dark focus:ring-2 focus:ring-nature-forest focus:outline-none leading-relaxed"></textarea>
                </div>

                <!-- แนบรูปภาพรีวิว (รองรับหลายรูป) -->
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">📸 แนบภาพบรรยากาศจากทริป (เลือกได้หลายรูป)</label>
                    <input type="file" name="images[]" multiple accept="image/*" @change="handleFiles($event)" class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-nature-cream file:text-nature-deep hover:file:bg-nature-golden/30 cursor-pointer border border-gray-200 rounded-2xl p-2 bg-gray-50 focus:outline-none">
                    
                    <!-- แสดงตัวอย่างรูปภาพก่อนอัปโหลด -->
                    <div x-show="previewImages.length > 0" class="flex flex-wrap gap-2.5 mt-3">
                        <template x-for="(imgSrc, index) in previewImages" :key="index">
                            <div class="relative w-20 h-20 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                <img :src="imgSrc" class="w-full h-full object-cover">
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-7 py-3 bg-gradient-to-r from-nature-deep to-nature-forest hover:from-[#143326] hover:to-[#357759] text-white font-bold text-xs rounded-2xl shadow-md hover:shadow-nature-forest/30 transition flex items-center gap-2">
                        <span>📤</span> เผยแพร่รีวิวของคุณ
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- ถ้ายังไม่ล็อกอิน ให้แสดงกล่องเชิญชวน -->
        <div class="bg-gradient-to-r from-nature-deep to-nature-forest rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <h3 class="text-lg font-bold text-nature-golden">เคยร่วมเดินป่ากับ TripHub แล้วใช่ไหม?</h3>
                <p class="text-xs text-white/80 mt-1">เข้าสู่ระบบเพื่อเขียนรีวิวและแบ่งปันภาพถ่ายสวยๆ ให้เพื่อนนักเดินป่า</p>
            </div>
            <a href="{{ route('login') }}" class="px-6 py-3 bg-nature-golden hover:bg-[#c9904d] text-nature-deep font-bold text-xs rounded-xl shadow transition shrink-0">
                เข้าสู่ระบบเพื่อรีวิว →
            </a>
        </div>
    @endauth

    <!-- แสดงรายการรีวิวทั้งหมด -->
    <div>
        <h3 class="text-xl font-bold text-nature-dark mb-6">รีวิวล่าสุดจากนักเดินทาง ({{ $reviews->total() }} รายการ)</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($reviews as $rev)
                <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl transition border border-gray-100 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="text-amber-400 text-sm tracking-wide">
                                @for($s = 1; $s <= 5; $s++)
                                    {{ $s <= $rev->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                            <span class="text-[11px] text-gray-400">{{ $rev->created_at->format('d/m/Y') }}</span>
                        </div>

                        <p class="text-xs text-gray-700 leading-relaxed italic whitespace-pre-line">
                            "{{ $rev->comment }}"
                        </p>

                        <!-- ภาพถ่ายรีวิวที่แนบมา -->
                        @if($rev->images && $rev->images->count() > 0)
                            <div class="flex flex-wrap gap-2 pt-2">
                                @foreach($rev->images as $img)
                                    <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank" class="block w-16 h-16 rounded-xl overflow-hidden border border-gray-100 hover:opacity-90 transition">
                                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="ภาพรีวิว" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-nature-forest text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-sm">
                            {{ mb_substr($rev->user->name ?? 'สมาชิก', 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-xs font-bold text-nature-dark block truncate">{{ $rev->user->name ?? 'สมาชิกนิรนาม' }}</span>
                            <span class="text-[10px] text-nature-forest truncate block">ทริป: {{ $rev->activity->name ?? 'เดินป่าธรรมชาติ' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-gray-100 text-gray-400 text-sm">
                    ยังไม่มีรีวิวในระบบ เป็นคนแรกที่เริ่มเขียนรีวิวเลย!
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    </div>

</section>
@endsection