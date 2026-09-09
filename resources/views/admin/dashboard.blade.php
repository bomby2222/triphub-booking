@extends('layouts.admin')

@section('title', 'ภาพรวมระบบ | TripHub Admin')
@section('page_title', '📊 แดชบอร์ดสถิติและภาพรวมระบบ')

@section('admin_content')
<div class="space-y-8">

    <!-- KPI Metrics Cards (4 Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Revenue -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase text-gray-500 tracking-wider">💰 รายรับสุทธิทั้งหมด</span>
                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg">+18.5%</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-nature-deep">฿{{ number_format($totalRevenue, 2) }}</span>
                <span class="text-xs text-gray-400 block mt-1">อัปเดตแบบ Real-time</span>
            </div>
        </div>

        <!-- Card 2: Total Bookings -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase text-gray-500 tracking-wider">🌲 จำนวนการจอง</span>
                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg">+12.4%</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-nature-dark">{{ number_format($totalBookings) }}</span>
                <span class="text-xs text-gray-400 block mt-1">รายการทั้งหมดในระบบ</span>
            </div>
        </div>

        <!-- Card 3: Pending/Active Bookings -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase text-gray-500 tracking-wider">⏳ รอจัดการ / ตรวจสอบ</span>
                @if(($pendingPaymentsCount ?? 0) > 0)
                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-lg animate-pulse">มีรายการ</span>
                @endif
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-amber-600">{{ number_format($pendingPaymentsCount ?? 0) }}</span>
                <span class="text-xs text-gray-400 block mt-1">รายการที่อยู่ในกระบวนการ</span>
            </div>
        </div>

        <!-- Card 4: Total Users -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase text-gray-500 tracking-wider">👥 สมาชิกทั้งหมด</span>
                <span class="px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-bold rounded-lg">+8.2%</span>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-nature-dark">{{ number_format($totalUsers) }}</span>
                <span class="text-xs text-gray-400 block mt-1">ผู้ใช้งานที่ลงทะเบียน</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Revenue Trend Line Chart (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-nature-dark">กราฟรายรับรายวัน (7 วันล่าสุด)</h3>
                    <p class="text-xs text-gray-400">สถิติยอดการชำระเงินที่ได้รับการอนุมัติ</p>
                </div>
            </div>
            <div class="h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Difficulty Proportion Doughnut Chart (1 Col) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-nature-dark mb-1">สัดส่วนระดับความยากของทริป</h3>
                <p class="text-xs text-gray-400 mb-4">การกระจายตามระดับเส้นทาง</p>
            </div>
            <div class="h-60 flex items-center justify-center">
                <canvas id="difficultyChart"></canvas>
            </div>
            <div class="text-center text-xs text-gray-400 mt-2">ข้อมูลจากฐานข้อมูลกิจกรรม</div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-nature-dark">รายการจองล่าสุด</h3>
                <p class="text-xs text-gray-400">5 รายการจองล่าสุดในระบบ</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-nature-forest hover:underline">
                ดูทั้งหมด →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-xs uppercase text-gray-400">
                        <th class="pb-3">รหัสการจอง</th>
                        <th class="pb-3">ผู้จอง</th>
                        <th class="pb-3">ทริป / รอบเดินทาง</th>
                        <th class="pb-3">จำนวนที่นั่ง</th>
                        <th class="pb-3">ยอดสุทธิ</th>
                        <th class="pb-3">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentBookings as $bk)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-4 font-mono font-bold text-nature-deep">{{ $bk->booking_code }}</td>
                            <td class="py-4 font-medium">{{ $bk->user->name ?? 'บุคคลภายนอก' }}</td>
                            <td class="py-4">
                                <span class="font-medium text-gray-800 block">{{ $bk->schedule->activity->name ?? '-' }}</span>
                                <span class="text-xs text-gray-400">{{ $bk->schedule ? $bk->schedule->start_date->format('d/m/Y') : '-' }}</span>
                            </td>
                            <td class="py-4 font-bold">{{ $bk->seats_count }} ท่าน</td>
                            <td class="py-4 font-bold text-nature-forest">฿{{ number_format($bk->net_amount, 2) }}</td>
                            <td class="py-4">
                                @if($bk->status === 'confirmed')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg">สำเร็จ</span>
                                @elseif($bk->status === 'processing')
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-lg">รอตรวจสลิป</span>
                                @elseif($bk->status === 'pending')
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg">รอชำระ</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-lg">{{ $bk->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-gray-400">ยังไม่มีรายการจองในระบบ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('admin_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Revenue Line Chart
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueDates) !!},
                datasets: [{
                    label: 'รายรับ (บาท)',
                    data: {!! json_encode($revenueData) !!},
                    borderColor: '#1B4332',
                    backgroundColor: 'rgba(64, 145, 108, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#DDA15E',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Difficulty Doughnut Chart
        const ctxDifficulty = document.getElementById('difficultyChart').getContext('2d');
        const diffData = {!! json_encode($difficultyStats) !!};
        new Chart(ctxDifficulty, {
            type: 'doughnut',
            data: {
                labels: Object.keys(diffData).map(k => k.toUpperCase()),
                datasets: [{
                    data: Object.values(diffData),
                    backgroundColor: ['#40916C', '#DDA15E', '#E76F51', '#C1121F'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endpush