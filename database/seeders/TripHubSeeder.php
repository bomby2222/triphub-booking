<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TripHubSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ตั้งค่า Roles 3 ระดับ
        $roles = [
            ['id' => 1, 'name' => 'เจ้าของเว็บ (Owner)', 'slug' => 'owner', 'description' => 'เจ้าของแพลตฟอร์ม จัดการการเงิน บัญชี และสิทธิ์ทั้งหมด'],
            ['id' => 2, 'name' => 'แอดมิน (Admin)', 'slug' => 'admin', 'description' => 'ผู้ดูแลระบบ จัดการทริป รอบเดินทาง และการจอง'],
            ['id' => 3, 'name' => 'ผู้ใช้ทั่วไป (User)', 'slug' => 'user', 'description' => 'นักท่องเที่ยว ค้นหาและจองกิจกรรม'],
        ];
        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(['id' => $r['id']], $r);
        }

        // 2. บัญชี Admin & Owner ตัวอย่าง (รหัสผ่าน: 12345678)
        DB::table('admins')->updateOrInsert(
            ['email' => 'owner@triphub.local'],
            [
                'role_id' => 1,
                'name' => 'เจ้าของเว็บ TripHub',
                'password' => Hash::make('12345678'),
                'phone' => '081-999-0001',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('admins')->updateOrInsert(
            ['email' => 'admin@triphub.local'],
            [
                'role_id' => 2,
                'name' => 'แอดมินดูแลทริป',
                'password' => Hash::make('12345678'),
                'phone' => '082-999-0002',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. ปรับชื่อเว็บไซต์เป็น TripHub ในตาราง settings
        DB::table('settings')->updateOrInsert(
            ['key' => 'site_name'],
            ['value' => 'TripHub - จองทริปธรรมชาติ เดินป่า กางเต็นท์ น้ำตกทั่วไทย', 'group' => 'site', 'created_at' => now(), 'updated_at' => now()]
        );

        // 4. ล้างข้อมูลกิจกรรมเดิมเพื่อลงชุดทริปยอดฮิตทั่วไทย
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('activity_itineraries')->truncate();
        DB::table('activity_includes')->truncate();
        DB::table('activity_excludes')->truncate();
        DB::table('activity_schedules')->truncate();
        DB::table('activities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 5. ข้อมูลทริปยอดฮิต 6 จังหวัด หลากหลายแนว
        $trips = [
            [
                'name' => 'ทริปเดินป่าพิชิตยอดเขาหลวงสุโขทัย ชมทะเลหมอก 360 องศา',
                'slug' => 'khao-luang-sukhothai',
                'cover_image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=1200&q=80',
                'description' => 'เส้นทางเดินป่าสุดคลาสสิก ชมวิวทะเลหมอกยอดผานารายณ์ และพระอาทิตย์ตกดินสุดโรแมนติกที่ผาแม่ย่า กางเต็นท์รับลมหนาวบนยอดเขา',
                'location' => 'อุทยานแห่งชาติรามคำแหง',
                'province' => 'สุโขทัย',
                'difficulty_level' => 'hard',
                'duration_text' => '2 วัน 1 คืน',
                'distance_km' => 3.70,
                'altitude_meters' => 1200,
                'suitable_season' => 'กันยายน - กุมภาพันธ์',
                'base_price' => 2890.00,
                'badge' => 'HOT',
                'cautions' => 'ทางชันเฉลี่ย 45 องศาตลอดทาง ควรเตรียม Trekking Pole',
                'schedules' => [
                    ['start' => '2026-09-12', 'end' => '2026-09-13', 'seats' => 20, 'avail' => 8],
                    ['start' => '2026-09-19', 'end' => '2026-09-20', 'seats' => 20, 'avail' => 15],
                ]
            ],
            [
                'name' => 'พิชิตสันมีดหมอสุดหวาดเสียว เขาช้างเผือก',
                'slug' => 'khao-chang-phueak-kanchanaburi',
                'cover_image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1200&q=80',
                'description' => 'ทริปปีนเขาในฝันของสายลุย เดินไต่ระดับบนสันเขาคมมีดกว้างเพียงก้าวเท้า ชมวิวทิวทัศน์ 360 องศาของเทือกเขาตะนาวศรี',
                'location' => 'อุทยานแห่งชาติทองผาภูมิ',
                'province' => 'กาญจนบุรี',
                'difficulty_level' => 'extreme',
                'duration_text' => '2 วัน 1 คืน',
                'distance_km' => 8.00,
                'altitude_meters' => 1249,
                'suitable_season' => 'พฤศจิกายน - มกราคม',
                'base_price' => 3890.00,
                'badge' => 'HOT',
                'cautions' => 'ไม่เหมาะกับผู้ที่เป็นโรคหัวใจหรือกลัวความสูงระดับรุนแรง',
                'schedules' => [
                    ['start' => '2026-11-07', 'end' => '2026-11-08', 'seats' => 10, 'avail' => 4],
                    ['start' => '2026-11-14', 'end' => '2026-11-15', 'seats' => 10, 'avail' => 10],
                ]
            ],
            [
                'name' => 'เดินป่ากางเต็นท์สัมผัสลมหนาว พิชิตภูกระดึง',
                'slug' => 'phu-kradueng-loei',
                'cover_image' => 'https://images.unsplash.com/photo-1510312305653-8ed496efae75?w=1200&q=80',
                'description' => 'เส้นทางเดินป่าในตำนาน กางเต็นท์ใต้ดงสน ชมพระอาทิตย์ขึ้นผานกแอ่น พระอาทิตย์ตกผาหล่มสัก และแวะชมน้ำตกเพ็ญพบใหม่',
                'location' => 'อุทยานแห่งชาติภูกระดึง',
                'province' => 'เลย',
                'difficulty_level' => 'medium',
                'duration_text' => '3 วัน 2 คืน',
                'distance_km' => 9.00,
                'altitude_meters' => 1288,
                'suitable_season' => 'ตุลาคม - พฤษภาคม',
                'base_price' => 3200.00,
                'badge' => 'PROMOTION',
                'cautions' => 'เดินเท้าขึ้นเขาช่วงซำแฮกค่อนข้างชัน มีลูกหาบบริการ',
                'schedules' => [
                    ['start' => '2026-10-10', 'end' => '2026-10-12', 'seats' => 30, 'avail' => 14],
                    ['start' => '2026-10-23', 'end' => '2026-10-25', 'seats' => 30, 'avail' => 25],
                ]
            ],
            [
                'name' => 'ผจญภัยป่าดงดิบ ล่องแพกางเต็นท์ & ถ้ำน้ำทะลุ เขาสก',
                'slug' => 'khao-sok-rainforest-surat-thani',
                'cover_image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=1200&q=80',
                'description' => 'สำรวจผืนป่าดงดิบที่เก่าแก่ที่สุดในโลก เดินป่าส่องสัตว์ ลุยน้ำตกและถ้ำน้ำทะลุ พร้อมนอนแพกางเต็นท์ริมทะเลสาบเชี่ยวหลานกุ้ยหลินเมืองไทย',
                'location' => 'อุทยานแห่งชาติเขาสก',
                'province' => 'สุราษฎร์ธานี',
                'difficulty_level' => 'medium',
                'duration_text' => '2 วัน 1 คืน',
                'distance_km' => 6.50,
                'altitude_meters' => 450,
                'suitable_season' => 'ตลอดทั้งปี',
                'base_price' => 3450.00,
                'badge' => 'NEW',
                'cautions' => 'ต้องเตรียมถุงกันน้ำและเสื้อชูชีพตลอดการเดินทางทางน้ำ',
                'schedules' => [
                    ['start' => '2026-09-26', 'end' => '2026-09-27', 'seats' => 16, 'avail' => 8],
                    ['start' => '2026-10-03', 'end' => '2026-10-04', 'seats' => 16, 'avail' => 16],
                ]
            ],
            [
                'name' => 'เดินป่าลำธารลุยน้ำตก แคมป์ปิ้งเขาช่องลม & คลองมะเดื่อ',
                'slug' => 'khao-chong-lom-nakhon-nayok',
                'cover_image' => 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=1200&q=80',
                'description' => 'เส้นทางเดินป่าเขียวขจี ลุยธารน้ำตกธรรมชาติใกล้กรุง กางเต็นท์ริมลำธารคลองมะเดื่อ สูดโอโซนบริสุทธิ์แบบ One Day หรือ 2 วัน 1 คืน',
                'location' => 'เขื่อนขุนด่านปราการชล',
                'province' => 'นครนายก',
                'difficulty_level' => 'easy',
                'duration_text' => '2 วัน 1 คืน',
                'distance_km' => 4.00,
                'altitude_meters' => 200,
                'suitable_season' => 'มิถุนายน - พฤศจิกายน',
                'base_price' => 1990.00,
                'badge' => 'NONE',
                'cautions' => 'หินริมลำธารลื่น ควรใช้รองเท้าแตะรัดส้นสำหรับลุยน้ำ',
                'schedules' => [
                    ['start' => '2026-09-12', 'end' => '2026-09-13', 'seats' => 20, 'avail' => 12],
                    ['start' => '2026-09-19', 'end' => '2026-09-20', 'seats' => 20, 'avail' => 18],
                ]
            ],
            [
                'name' => 'อลังการเทือกเขาหินปูน พิชิตดอยหลวงเชียงดาว',
                'slug' => 'doi-luang-chiang-dao',
                'cover_image' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=1200&q=80',
                'description' => 'ยอดเขาสูงอันดับ 3 ของไทย ชมพรรณไม้กึ่งอัลไพน์หายาก กางเต็นท์ชมทะเลดาวระยิบระยับยามค่ำคืน และทะเลหมอกพระอาทิตย์ขึ้น',
                'location' => 'เขตรักษาพันธุ์สัตว์ป่าเชียงดาว',
                'province' => 'เชียงใหม่',
                'difficulty_level' => 'hard',
                'duration_text' => '3 วัน 2 คืน',
                'distance_km' => 8.50,
                'altitude_meters' => 2225,
                'suitable_season' => 'พฤศจิกายน - กุมภาพันธ์',
                'base_price' => 4500.00,
                'badge' => 'HOT',
                'cautions' => 'ไม่มีน้ำประปาและไฟฟ้า กรุณาเตรียมถุงนอนทนความหนาวต่ำกว่า 5 องศา',
                'schedules' => [
                    ['start' => '2026-11-20', 'end' => '2026-11-22', 'seats' => 15, 'avail' => 5],
                ]
            ],
        ];

        foreach ($trips as $data) {
            $schedules = $data['schedules'];
            unset($data['schedules']);

            $data['is_published'] = true;
            $data['created_at'] = now();
            $data['updated_at'] = now();

            $actId = DB::table('activities')->insertGetId($data);

            // สิ่งที่รวม
            DB::table('activity_includes')->insert([
                ['activity_id' => $actId, 'item_name' => 'รถตู้ VIP ไป-กลับ พร้อมน้ำมันและคนขับมืออาชีพ', 'created_at' => now(), 'updated_at' => now()],
                ['activity_id' => $actId, 'item_name' => 'ค่าธรรมเนียมเข้าอุทยานและค่ากางเต็นท์', 'created_at' => now(), 'updated_at' => now()],
                ['activity_id' => $actId, 'item_name' => 'อาหารและน้ำดื่มตลอดกิจกรรม', 'created_at' => now(), 'updated_at' => now()],
                ['activity_id' => $actId, 'item_name' => 'ไกด์นำทางท้องถิ่นและสตาฟดูแลความปลอดภัย', 'created_at' => now(), 'updated_at' => now()],
                ['activity_id' => $actId, 'item_name' => 'ประกันอุบัติเหตุการเดินทางวงเงิน 1,000,000 บาท', 'created_at' => now(), 'updated_at' => now()],
            ]);

            // สิ่งที่ไม่รวม
            DB::table('activity_excludes')->insert([
                ['activity_id' => $actId, 'item_name' => 'ค่าลูกหาบส่วนบุคคล (คิดตามน้ำหนักกิโลกรัม)', 'created_at' => now(), 'updated_at' => now()],
                ['activity_id' => $actId, 'item_name' => 'อุปกรณ์แคมป์ปิ้งส่วนตัว (เต็นท์, ถุงนอน)', 'created_at' => now(), 'updated_at' => now()],
                ['activity_id' => $actId, 'item_name' => 'ค่าใช้จ่ายส่วนตัวนอกเหนือจากที่ระบุ', 'created_at' => now(), 'updated_at' => now()],
            ]);

            // กำหนดการเดินทาง
            DB::table('activity_itineraries')->insert([
                ['activity_id' => $actId, 'day_number' => 1, 'time_slot' => '06:00:00', 'title' => 'รวมตัว ณ จุดนัดพบ & เดินทางสู่จุดเริ่มเดิน', 'description' => 'ตรวจเช็คสัมภาระ แนะนำทีมงาน และรับประทานอาหารเช้า', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['activity_id' => $actId, 'day_number' => 1, 'time_slot' => '08:30:00', 'title' => 'เริ่มเดินเท้าขึ้นสู่ลานแคมป์', 'description' => 'ศึกษาธรรมชาติ ลัดเลาะลำธารและชมวิวทิวทัศน์', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['activity_id' => $actId, 'day_number' => 1, 'time_slot' => '17:00:00', 'title' => 'ชมพระอาทิตย์ตกดิน & รับประทานอาหารค่ำ', 'description' => 'กางเต็นท์ ล้อมวงทานมื้อค่ำท่ามกลางธรรมชาติ', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['activity_id' => $actId, 'day_number' => 2, 'time_slot' => '05:30:00', 'title' => 'ชมทะเลหมอกและพระอาทิตย์ขึ้น', 'description' => 'จิบกาแฟยามเช้า เก็บแคมป์ และเดินทางกลับ', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ]);

            // รอบเดินทาง
            foreach ($schedules as $s) {
                DB::table('activity_schedules')->insert([
                    'activity_id' => $actId,
                    'start_date' => $s['start'],
                    'end_date' => $s['end'],
                    'total_seats' => $s['seats'],
                    'available_seats' => $s['avail'],
                    'price_override' => null,
                    'status' => $s['avail'] > 0 ? 'open' : 'full',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}