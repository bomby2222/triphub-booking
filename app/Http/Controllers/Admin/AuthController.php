<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // แสดงหน้าฟอร์มเข้าสู่ระบบแอดมิน
    public function showLoginForm()
    {
        if (session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    // ตรวจสอบอีเมลและรหัสผ่าน
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ค้นหาข้อมูลในตาราง admins
        $admin = DB::table('admins')->where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            if (!$admin->is_active) {
                return back()->with('error', 'บัญชีนี้ถูกระงับการใช้งานชั่วคราว');
            }

            // บันทึก Session การเข้าสู่ระบบ
            session([
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
                'admin_role_id' => $admin->role_id,
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'เข้าสู่ระบบสำเร็จ ยินดีต้อนรับคุณ ' . $admin->name);
        }

        return back()->with('error', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
    }

    // ออกจากระบบแอดมิน
    public function logout()
    {
        session()->forget(['admin_id', 'admin_name', 'admin_email', 'admin_role_id']);
        return redirect()->route('admin.login')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }
}