<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\News; // ใช้โมเดล News ที่เชื่อมกับระบบหลังบ้าน
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\Activity;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    /**
     * หน้ารวมโปรโมชั่น & คูปองส่วนลด (/promotions)
     */
    public function promotions()
    {
        $promotions = Promotion::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now()->toDateString());
            })
            ->latest()
            ->get();

        return view('promotions.index', compact('promotions'));
    }

    /**
     * หน้าข่าวสารและประกาศเส้นทางธรรมชาติ (/news)
     */
    public function news(Request $request)
    {
        // รับค่าหมวดหมู่ (รองรับทั้ง category และ type)
        $category = $request->query('category') ?? $request->query('type');

        $query = News::where('is_published', true);

        if (!empty($category) && $category !== 'all') {
            $query->where('category', $category);
        }

        $newsList = $query->latest()->paginate(6)->withQueryString();
        $announcements = $newsList; // กำหนดเผื่อไว้กรณี View เรียกใช้ชื่อตัวแปรเดิม

        return view('news.index', compact('newsList', 'announcements', 'category'));
    }

    /**
     * หน้ารวมรีวิว พร้อมส่งรายการทริปสำหรับทำ Dropdown (/reviews)
     */
    public function reviews()
    {
        $reviews = Review::with(['user', 'activity', 'images'])
            ->where('is_hidden', false)
            ->latest()
            ->paginate(9);

        $activities = Activity::where('is_published', true)->select('id', 'name', 'province')->get();

        return view('reviews.index', compact('reviews', 'activities'));
    }

    /**
     * ฟังก์ชันบันทึกรีวิวและรูปภาพ
     */
    public function storeReview(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'rating'      => 'required|integer|min:1|max:5',
            'comment'     => 'required|string|max:1000',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'activity_id.required' => 'กรุณาเลือกทริปที่ต้องการรีวิว',
            'rating.required'      => 'กรุณาให้คะแนนดาว',
            'comment.required'     => 'กรุณาระบุความคิดเห็น',
            'images.*.max'         => 'รูปภาพต้องมีขนาดไม่เกิน 5MB ต่อรูป',
        ]);

        // ค้นหาการจองของผู้ใช้ (ถ้ามี)
        $booking = Booking::where('user_id', Auth::id())
            ->whereHas('schedule', function ($q) use ($request) {
                $q->where('activity_id', $request->activity_id);
            })->first();

        // บันทึกรีวิว
        $review = Review::create([
            'user_id'     => Auth::id(),
            'activity_id' => $request->activity_id,
            'booking_id'  => $booking ? $booking->id : null,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
            'is_pinned'   => false,
            'is_hidden'   => false,
        ]);

        // อัปโหลดและบันทึกรูปภาพ
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('reviews', 'public');
                ReviewImage::create([
                    'review_id'  => $review->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('reviews.index')->with('success', 'ขอบคุณสำหรับรีวิว! ข้อมูลและรูปภาพของคุณถูกเผยแพร่เรียบร้อยแล้ว');
    }
}