<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * 1. หน้าเข้าสู่ระบบ
     */
    public function showLoginForm(Request $request)
    {
        $redirectUrl = $request->query('redirect');

        if (Auth::check()) {
            return $redirectUrl ? redirect($redirectUrl) : redirect()->intended('/');
        }

        return view('auth.login', compact('redirectUrl'));
    }

    /**
     * 2. ตรวจสอบการเข้าสู่ระบบ
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'redirect' => 'nullable|string',
        ], [
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();

            // ถ้ามี URL ส่งต่อที่มาจากหน้าจองทริป ให้พาผู้ใช้กลับไปจองต่อทันที
            if ($request->filled('redirect')) {
                $target = $request->input('redirect');
                // ป้องกัน Open Redirect โดยตรวจสอบว่าเป็น Relative path หรือ Host เดียวกัน
                if (Str::startsWith($target, '/') || parse_url($target, PHP_URL_HOST) === $request->getHost()) {
                    return redirect($target)->with('success', 'เข้าสู่ระบบสำเร็จ พร้อมดำเนินการจองทริปต่อได้ทันที!');
                }
            }

            return redirect()->intended('/')->with('success', 'เข้าสู่ระบบสำเร็จ ยินดีต้อนรับกลับมา!');
        }

        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
        ])->onlyInput('email');
    }

    /**
     * 3. หน้าสมัครสมาชิก
     */
    public function showRegisterForm(Request $request)
    {
        $redirectUrl = $request->query('redirect');

        if (Auth::check()) {
            return $redirectUrl ? redirect($redirectUrl) : redirect('/');
        }

        return view('auth.register', compact('redirectUrl'));
    }

    /**
     * 4. บันทึกข้อมูลสมาชิกใหม่และเข้าสู่ระบบอัตโนมัติ
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'redirect' => 'nullable|string',
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.unique' => 'อีเมลนี้ถูกใช้งานไปแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
            'password.confirmed' => 'การยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'points' => 100, // แจกแต้มเริ่มต้น 100 แต้ม
            'membership_tier' => 'Bronze',
            'is_active' => true,
        ]);

        Auth::login($user);

        // ตรวจสอบ URL ส่งต่อหลังการสมัครสมาชิก
        if ($request->filled('redirect')) {
            $target = $request->input('redirect');
            if (Str::startsWith($target, '/') || parse_url($target, PHP_URL_HOST) === $request->getHost()) {
                return redirect($target)->with('success', 'สมัครสมาชิกสำเร็จ! ยินดีต้อนรับสู่ TripHub พร้อมรับ 100 แต้มสะสม');
            }
        }

        return redirect('/')->with('success', 'ยินดีต้อนรับสู่ TripHub! สมัครสมาชิกและรับแต้มสะสมฟรี 100 แต้มเรียบร้อยแล้ว');
    }

    /**
     * 5. ออกจากระบบ
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }
}