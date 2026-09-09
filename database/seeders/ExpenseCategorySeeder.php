<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'ค่าไกด์และทีมงานสตาฟ', 'description' => 'ค่าจ้างไกด์นำทางท้องถิ่นและผู้ช่วยดูแลความปลอดภัย'],
            ['name' => 'ค่าอาหารและเครื่องดื่ม', 'description' => 'วัตถุดิบและอาหารจัดเลี้ยงทุกมื้อของทริป'],
            ['name' => 'ค่าเดินทางและน้ำมันรถ', 'description' => 'ค่าน้ำมัน ค่าทางด่วน ค่าเช่ารถตู้หรือรถขับเคลื่อน 4 ล้อ'],
            ['name' => 'ค่าธรรมเนียมอุทยาน', 'description' => 'ค่าเข้าอุทยาน ค่ากางเต็นท์ ค่าขออนุญาตเดินป่า'],
            ['name' => 'ค่าอุปกรณ์และแคมป์ปิ้ง', 'description' => 'ค่าเช่าหรือซ่อมบำรุงเต็นท์ แก๊สสนาม ถังน้ำ และยาปฐมพยาบาล'],
            ['name' => 'ค่าการตลาดและโฆษณา', 'description' => 'โฆษณา Facebook, Google Ads, กิจกรรมส่งเสริมการขาย'],
            ['name' => 'ค่าใช้จ่ายเบ็ดเตล็ด', 'description' => 'ค่าใช้จ่ายอื่นๆ ที่ไม่เข้าพวก'],
        ];

        foreach ($categories as $cat) {
            DB::table('expense_categories')->updateOrInsert(['name' => $cat['name']], $cat);
        }
    }
}