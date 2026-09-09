<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->paginate(15);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'           => 'required|string|max:50|unique:promotions,code',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'discount_type'  => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'badge_text'     => 'nullable|string|max:50',
            'min_spend'      => 'nullable|numeric|min:0',
            'max_discount'   => 'nullable|numeric|min:0',
            'quota'          => 'required|integer|min:1',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'is_active'      => 'nullable|boolean',
        ]);

        // กำหนดป้าย Badge อัตโนมัติหากไม่ได้กรอก
        if (empty($validated['badge_text'])) {
            $validated['badge_text'] = $validated['discount_type'] === 'percent' 
                ? 'ลด ' . (int)$validated['discount_value'] . '%' 
                : 'ลด ฿' . number_format($validated['discount_value']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['used_count'] = 0;

        Promotion::create($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'เพิ่มคูปองส่วนลดเรียบร้อยแล้ว');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'code'           => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'discount_type'  => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'badge_text'     => 'nullable|string|max:50',
            'min_spend'      => 'nullable|numeric|min:0',
            'max_discount'   => 'nullable|numeric|min:0',
            'quota'          => 'required|integer|min:1',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
        ]);

        if (empty($validated['badge_text'])) {
            $validated['badge_text'] = $validated['discount_type'] === 'percent' 
                ? 'ลด ' . (int)$validated['discount_value'] . '%' 
                : 'ลด ฿' . number_format($validated['discount_value']);
        }

        $validated['is_active'] = $request->has('is_active');

        $promotion->update($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'อัปเดตข้อมูลคูปองเรียบร้อยแล้ว');
    }

    public function toggleStatus(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);
        return back()->with('success', 'เปลี่ยนสถานะการใช้งานคูปองเรียบร้อย');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'ลบคูปองส่วนลดเรียบร้อยแล้ว');
    }
}