<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $newsList = News::latest()->paginate(15);
        return view('admin.news.index', compact('newsList'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:general,weather,closed',
            'badge_text'   => 'nullable|string|max:50',
            'content'      => 'required|string',
        ]);

        if (empty($validated['badge_text'])) {
            $badges = [
                'general' => '📢 ประกาศ',
                'weather' => '⚠️ สภาพอากาศ',
                'closed'  => '🚫 ปิดเส้นทาง',
            ];
            $validated['badge_text'] = $badges[$validated['category']];
        }

        $validated['is_published'] = $request->has('is_published');

        News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'เพิ่มประกาศข่าวสารเรียบร้อยแล้ว');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:general,weather,closed',
            'badge_text'   => 'nullable|string|max:50',
            'content'      => 'required|string',
        ]);

        if (empty($validated['badge_text'])) {
            $badges = [
                'general' => '📢 ประกาศ',
                'weather' => '⚠️ สภาพอากาศ',
                'closed'  => '🚫 ปิดเส้นทาง',
            ];
            $validated['badge_text'] = $badges[$validated['category']];
        }

        $validated['is_published'] = $request->has('is_published');

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'อัปเดตประกาศเรียบร้อยแล้ว');
    }

    public function toggleStatus(News $news)
    {
        $news->update(['is_published' => !$news->is_published]);
        return back()->with('success', 'เปลี่ยนสถานะการแสดงผลข่าวสารแล้ว');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'ลบข่าวสารเรียบร้อยแล้ว');
    }
}