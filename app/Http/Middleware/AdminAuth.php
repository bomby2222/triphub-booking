<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // ถ้าไม่มี Session แอดมิน ให้ดีดกลับไปหน้าล็อกอินทันที
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login')->with('error', 'กรุณาเข้าสู่ระบบก่อนเข้าใช้งานส่วนผู้ดูแลระบบ');
        }

        return $next($request);
    }
}