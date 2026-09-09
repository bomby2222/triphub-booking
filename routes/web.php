<?php

use Illuminate\Support\Facades\Route;

// Frontend Controllers
use App\Http\Controllers\TripController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PromotionController; // ใช้งาน PromotionController ฝั่งหน้าบ้าน

// Guide Portal Controller
use App\Http\Controllers\Guide\PortalController as GuidePortalController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\GuideController as AdminGuideController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController; // จัดการโปรโมชั่นหลังบ้าน
use App\Http\Controllers\Admin\NewsController as AdminNewsController;           // จัดการข่าวสารหลังบ้าน (เพิ่มใหม่)
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Middleware\AdminAuth;

/*
|--------------------------------------------------------------------------
| 1. Public Frontend Routes (TripHub)
|--------------------------------------------------------------------------
*/
Route::get('/', [TripController::class, 'home'])->name('home');
Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::get('/trips/{id}', [TripController::class, 'show'])->name('trips.show');

// โปรโมชั่นหน้าบ้าน (ดึงข้อมูลจากตาราง promotions)
Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');

// ข่าวสารและประกาศหน้าบ้าน
Route::get('/news', [PageController::class, 'news'])->name('news.index');
Route::get('/reviews', [PageController::class, 'reviews'])->name('reviews.index');
Route::post('/reviews', [PageController::class, 'storeReview'])->name('reviews.store')->middleware('auth');

/*
|--------------------------------------------------------------------------
| 2. User Authentication & Booking Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/{id}/payment', [BookingController::class, 'payment'])->name('bookings.payment');
Route::post('/bookings/{id}/payment', [BookingController::class, 'submitPayment'])->name('bookings.submit_payment');

/*
|--------------------------------------------------------------------------
| 3. Guide Portal Routes (ระบบสำหรับไกด์เข้าสู่ระบบ & ส่งรายงาน 3 รูป)
|--------------------------------------------------------------------------
*/
Route::prefix('guide')->name('guide.')->group(function () {
    // หน้าล็อกอิน & ออกจากระบบของไกด์
    Route::get('/login', [GuidePortalController::class, 'showLogin'])->name('login');
    Route::post('/login', [GuidePortalController::class, 'login'])->name('login.submit');
    Route::post('/logout', [GuidePortalController::class, 'logout'])->name('logout');

    // หน้ารับงานและส่งรายงาน (ต้องล็อกอินในระบบไกด์)
    Route::get('/jobs', [GuidePortalController::class, 'jobs'])->name('jobs');
    Route::get('/jobs/{id}', [GuidePortalController::class, 'jobDetail'])->name('job_detail');
    Route::post('/jobs/{id}/report', [GuidePortalController::class, 'submitReport'])->name('submit_report');
});

/*
|--------------------------------------------------------------------------
| 4. Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| 5. Protected Admin Backoffice Routes (ระบบจัดการหลังบ้าน)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware([AdminAuth::class])->name('admin.')->group(function () {
    // แดชบอร์ดภาพรวมระบบ
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // จัดการทริปท่องเที่ยว (Trips CRUD)
    Route::resource('activities', ActivityController::class);

    // จัดการรอบเดินทางและปฏิทิน (Schedules & Calendar)
    Route::get('/activities/{id}/schedules', [ScheduleController::class, 'index'])->name('activities.schedules.index');
    Route::post('/activities/{id}/schedules', [ScheduleController::class, 'store'])->name('activities.schedules.store');
    Route::post('/schedules/{id}/toggle-status', [ScheduleController::class, 'toggleStatus'])->name('schedules.toggle_status');
    Route::post('/schedules/{id}/update-status', [ScheduleController::class, 'updateStatus'])->name('schedules.update_status');

    // รายการจองทริปทั้งหมด & การโยนงานไกด์
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{id}/assign-guide', [BookingManagementController::class, 'assignGuide'])->name('bookings.assign_guide');
    Route::post('/bookings/{id}/pay-guide', [AdminGuideController::class, 'markAsPaid'])->name('guides.pay');

    // จัดการข้อมูลทีมไกด์ประจำพื้นที่ (เพิ่ม, ลบ, ดูข้อมูลไกด์)
    Route::get('/guides', [AdminGuideController::class, 'index'])->name('guides.index');
    Route::post('/guides', [AdminGuideController::class, 'store'])->name('guides.store');
    Route::delete('/guides/{id}', [AdminGuideController::class, 'destroy'])->name('guides.destroy');

    // 🎟️ จัดการโปรโมชั่นและคูปองส่วนลด (Promotions CRUD & สลับสถานะเปิด/ปิด)
    Route::resource('promotions', AdminPromotionController::class);
    Route::patch('/promotions/{promotion}/toggle', [AdminPromotionController::class, 'toggleStatus'])->name('promotions.toggle');

    // 📰 จัดการข่าวสารและประกาศ (News CRUD & สลับสถานะเปิด/ปิด)
    Route::resource('news', AdminNewsController::class);
    Route::patch('/news/{news}/toggle', [AdminNewsController::class, 'toggleStatus'])->name('news.toggle');

    // ตั้งค่าระบบ, ธนาคาร และ EasySlip API
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});