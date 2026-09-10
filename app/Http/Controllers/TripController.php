<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * หน้าแรก (Homepage) แสดงทริปยอดฮิต
     */
    public function home()
    {
        $activities = Activity::where('is_published', true)
            ->with(['schedules'])
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('activities'));
    }

    /**
     * หน้ารวมทริปทั้งหมด พร้อมระบบค้นหาและตัวกรอง (/trips)
     */
    public function index(Request $request)
    {
        $query = Activity::where('is_published', true)->with(['schedules']);

        // ค้นหาตามชื่อทริป หรือสถานที่
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('location', 'like', '%' . $request->keyword . '%')
                  ->orWhere('province', 'like', '%' . $request->keyword . '%');
            });
        }

        // 🏷️ กรองตามประเภททริป (Category) - เพิ่มใหม่
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 📍 กรองตามจังหวัด
        if ($request->filled('province')) {
            $query->where('province', $request->province);
        }

        // ⛰️ กรองตามระดับความยาก
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // 📅 กรองตามเดือนที่เดินทาง (เช่น 2026-09) - เพิ่มใหม่
        if ($request->filled('month')) {
            $month = $request->month;
            $query->whereHas('schedules', function ($q) use ($month) {
                $q->where('start_date', 'like', $month . '%');
            });
        }

        // 💰 กรองตามช่วงราคา
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // 📊 การเรียงลำดับ
        if ($request->sort === 'price_asc') {
            $query->orderBy('base_price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('base_price', 'desc');
        } else {
            $query->latest();
        }

        $activities = $query->paginate(9)->withQueryString();

        return view('trips.index', compact('activities'));
    }

    /**
     * หน้ารายละเอียดทริป (/trips/{id})
     */
    public function show($slugOrId)
    {
        $activity = Activity::where('id', $slugOrId)
            ->orWhere('slug', $slugOrId)
            ->with(['schedules' => function ($q) {
                $q->where('start_date', '>=', now()->toDateString())
                  ->orderBy('start_date');
            }, 'itineraries', 'includes', 'excludes'])
            ->firstOrFail();

        return view('trips.show', compact('activity'));
    }
}       