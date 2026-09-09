<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * แสดงหน้าฟอร์มตั้งค่า
     */
    public function index()
    {
        $settings = [
            'easyslip_api_key' => Setting::get('easyslip_api_key', ''),
            'easyslip_enabled' => Setting::get('easyslip_enabled', '1'),
            'promptpay_number' => Setting::get('promptpay_number', '0812345678'),
            'bank_name' => Setting::get('bank_name', 'ธนาคารกสิกรไทย (KBANK)'),
            'bank_account_name' => Setting::get('bank_account_name', 'บจก. ทริปฮับ (ไทยแลนด์)'),
            'bank_account_number' => Setting::get('bank_account_number', '123-4-56789-0'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * บันทึกการตั้งค่าลงฐานข้อมูล
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'easyslip_api_key' => 'nullable|string|max:255',
            'easyslip_enabled' => 'nullable|in:0,1',
            'promptpay_number' => 'required|string|max:20',
            'bank_name' => 'required|string|max:100',
            'bank_account_name' => 'required|string|max:150',
            'bank_account_number' => 'required|string|max:50',
        ], [
            'promptpay_number.required' => 'กรุณาระบุเบอร์โทรหรือเลขพร้อมเพย์',
            'bank_name.required' => 'กรุณาระบุชื่อธนาคาร',
            'bank_account_name.required' => 'กรุณาระบุชื่อเจ้าของบัญชี',
            'bank_account_number.required' => 'กรุณาระบุเลขที่บัญชี',
        ]);

        // บันทึกค่าลงใน Setting Model
        Setting::set('easyslip_api_key', $request->input('easyslip_api_key', ''), 'payment');
        Setting::set('easyslip_enabled', $request->has('easyslip_enabled') ? '1' : '0', 'payment');
        Setting::set('promptpay_number', $validated['promptpay_number'], 'payment');
        Setting::set('bank_name', $validated['bank_name'], 'payment');
        Setting::set('bank_account_name', $validated['bank_account_name'], 'payment');
        Setting::set('bank_account_number', $validated['bank_account_number'], 'payment');

        return redirect()->back()->with('success', 'บันทึกการตั้งค่าระบบการเงินและ EasySlip API เรียบร้อยแล้ว');
    }
}