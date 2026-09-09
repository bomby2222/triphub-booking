<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Hero Banners
        DB::table('banners')->insertOrIgnore([
            [
                'title' => 'สัมผัสธรรมชาติอันบริสุทธิ์ พิชิตยอดเขาในฝัน',
                'subtitle' => 'ระบบจองทริปเดินป่าครบวงจร การันตีความปลอดภัย พร้อมไกด์มืออาชีพ',
                'image_path' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1600&q=80',
                'button_text' => 'สำรวจทริปทั้งหมด',
                'link_url' => '/trips',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Announcements
        DB::table('announcements')->insertOrIgnore([
            [
                'title' => '🌲 ประกาศเปิดฤดูกาลเดินป่าเขาหลวงสุโขทัย ประจำปี 2569',
                'type' => 'general',
                'image' => null,
                'content' => 'ทางอุทยานแห่งชาติรามคำแหงประกาศเปิดเส้นทางเดินป่าศึกษาธรรมชาติอย่างเป็นทางการ สามารถสำรองที่นั่งล่วงหน้าผ่านระบบได้แล้ววันนี้',
                'publish_date' => '2026-08-30',
                'expire_date' => '2026-10-31',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => '⚠️ แจ้งเตือนสภาพอากาศ: มรสุมเข้าภาคใต้ตอนบน',
                'type' => 'weather_warning',
                'image' => null,
                'content' => 'โปรดตรวจสอบประกาศและเตรียมอุปกรณ์กันฝนให้พร้อม สำหรับผู้ที่เดินทางในสัปดาห์นี้',
                'publish_date' => '2026-08-31',
                'expire_date' => '2026-09-05',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. System Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'Hiking Nature Journey', 'group' => 'site'],
            ['key' => 'site_contact_phone', 'value' => '02-999-8888', 'group' => 'contact'],
            ['key' => 'site_contact_email', 'value' => 'support@hikingnature.com', 'group' => 'contact'],
            ['key' => 'promptpay_number', 'value' => '0812345678', 'group' => 'payment'],
            ['key' => 'bank_account_name', 'value' => 'บจก. ไฮกิ้ง เนเจอร์ เจอร์นีย์', 'group' => 'payment'],
            ['key' => 'bank_account_number', 'value' => '123-4-56789-0', 'group' => 'payment'],
            ['key' => 'bank_name', 'value' => 'ธนาคารกรุงเทพ', 'group' => 'payment'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(['key' => $setting['key']], $setting);
        }
    }
}