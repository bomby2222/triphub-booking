<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. สรุปตัวเลขสถิติหลัก
        $totalRevenue = Schema::hasTable('bookings') 
            ? (Booking::where('status', 'confirmed')->sum('net_amount') ?? 0) 
            : 0;
            
        $totalBookings = Schema::hasTable('bookings') ? Booking::count() : 0;

        // สลิปรอตรวจสอบ
        $pendingPaymentsCount = 0;
        if (Schema::hasTable('payments')) {
            $pendingPaymentsCount = Payment::whereIn('status', ['under_review', 'pending'])->count();
        }
        if ($pendingPaymentsCount === 0 && Schema::hasTable('bookings')) {
            $pendingPaymentsCount = Booking::where('status', 'processing')->count();
        }
        $pendingPayments = $pendingPaymentsCount;

        $totalActivities = Schema::hasTable('activities') ? Activity::count() : 0;
        $totalUsers = Schema::hasTable('users') ? User::count() : 0;

        // 2. ข้อมูลกราฟรายรับย้อนหลัง 7 วัน (Line Chart)
        $revenueDates = [];
        $revenueData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateString = $date->toDateString();
            $revenueDates[] = $date->format('d/m');

            $dailySum = Schema::hasTable('bookings')
                ? (Booking::where('status', 'confirmed')
                    ->whereDate('created_at', $dateString)
                    ->sum('net_amount') ?? 0)
                : 0;

            $revenueData[] = (float) $dailySum;
        }

        // 3. สถิติระดับความยากของทริป (Doughnut Chart)
        $difficultyStats = [
            'easy' => 0,
            'medium' => 0,
            'hard' => 0,
            'extreme' => 0,
        ];

        if (Schema::hasTable('activities')) {
            $diffCounts = Activity::selectRaw('difficulty_level, count(*) as total')
                ->groupBy('difficulty_level')
                ->pluck('total', 'difficulty_level')
                ->toArray();

            foreach ($diffCounts as $lvl => $count) {
                $key = strtolower($lvl);
                if (array_key_exists($key, $difficultyStats)) {
                    $difficultyStats[$key] = (int) $count;
                }
            }
        }

        // 4. รายการจอง 5 รายการล่าสุด
        $recentBookings = collect();
        if (Schema::hasTable('bookings')) {
            $recentBookings = Booking::with(['user', 'schedule.activity'])
                ->latest()
                ->take(5)
                ->get();
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalBookings',
            'pendingPayments',
            'pendingPaymentsCount',
            'totalActivities',
            'totalUsers',
            'revenueDates',
            'revenueData',
            'difficultyStats',
            'recentBookings'
        ));
    }
}