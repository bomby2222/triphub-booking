<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'code' => 'HIKING100',
                'title' => 'ส่วนลดพิเศษต้อนรับเปิดฤดูกาลเดินป่า',
                'description' => 'รับส่วนลดทันที 100 บาท เมื่อจองทริปใดก็ได้ไม่มีขั้นต่ำ',
                'discount_type' => 'fixed',
                'discount_value' => 100.00,
                'min_spend' => 0.00,
                'max_discount' => null,
                'quota' => 200,
                'used_count' => 35,
                'limit_per_user' => 1,
                'start_date' => '2026-08-01',
                'end_date' => '2026-12-31',
                'activity_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FOREST20',
                'title' => 'ส่วนลด DeepForest 20%',
                'description' => 'รับส่วนลด 20% สูงสุด 500 บาท เมื่อมียอดจองขั้นต่ำ 2,500 บาท',
                'discount_type' => 'percent',
                'discount_value' => 20.00,
                'min_spend' => 2500.00,
                'max_discount' => 500.00,
                'quota' => 50,
                'used_count' => 12,
                'limit_per_user' => 1,
                'start_date' => '2026-08-15',
                'end_date' => '2026-10-31',
                'activity_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($promotions as $promo) {
            DB::table('promotions')->updateOrInsert(['code' => $promo['code']], $promo);
        }
    }
}