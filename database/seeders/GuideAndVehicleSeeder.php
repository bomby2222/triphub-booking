<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuideAndVehicleSeeder extends Seeder
{
    public function run(): void
    {
        $guideAdmin = DB::table('admins')->where('email', 'guide@hiking.local')->first();

        // 1. Guides
        $guides = [
            [
                'admin_id' => $guideAdmin ? $guideAdmin->id : null,
                'name' => 'สมชาย ยอดดอย',
                'nickname' => 'พี่ชาย',
                'phone' => '084-567-8901',
                'bio' => 'ผู้เชี่ยวชาญการเดินป่าภาคเหนือและภาคกลาง ประสบการณ์นำทริปเดินป่ากว่า 8 ปี ผ่านการอบรมปฐมพยาบาลระดับสูง (WFA)',
                'experience_years' => 8,
                'license_number' => 'GD-6901-0023',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin_id' => null,
                'name' => 'นราวิชญ์ พงษ์ไพร',
                'nickname' => 'ไกด์อาร์ต',
                'phone' => '089-998-8776',
                'bio' => 'ไกด์ท้องถิ่นชำนาญเส้นทางเดินป่าสันเขา ประสบการณ์นำทริปเขาช้างเผือกและโมโกจู',
                'experience_years' => 5,
                'license_number' => 'GD-6901-0088',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin_id' => null,
                'name' => 'วิภาดา พนาวัลย์',
                'nickname' => 'ฟ้า',
                'phone' => '081-445-5667',
                'bio' => 'ไกด์สายถ่ายภาพและการอนุรักษ์ธรรมชาติ แนะนำมุมถ่ายภาพดาวและทะเลหมอก',
                'experience_years' => 4,
                'license_number' => 'GD-6901-0105',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($guides as $guide) {
            DB::table('guides')->updateOrInsert(['phone' => $guide['phone']], $guide);
        }

        // 2. Vehicles
        $vehicles = [
            [
                'vehicle_type' => 'รถตู้ Toyota Commuter VIP (10 ที่นั่ง)',
                'license_plate' => '3ขก-4589 กทม.',
                'capacity' => 10,
                'driver_name' => 'ลุงประเสริฐ',
                'driver_phone' => '081-112-2334',
                'status' => 'available',
                'notes' => 'มีระบบ GPS และที่ชาร์จ Type-C ประจำทุกเบาะ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_type' => 'รถตู้ Toyota Commuter VIP (10 ที่นั่ง)',
                'license_plate' => '1นข-8921 สุโขทัย',
                'capacity' => 10,
                'driver_name' => 'นายสมเกียรติ',
                'driver_phone' => '082-223-3445',
                'status' => 'available',
                'notes' => 'เบาะนวดปรับเอน พร้อม WiFi พกพา',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_type' => 'รถกระบะขับเคลื่อน 4 ล้อ (4x4 Offroad)',
                'license_plate' => 'บท-5544 กาญจนบุรี',
                'capacity' => 8,
                'driver_name' => 'พรานบุญ',
                'driver_phone' => '083-334-4556',
                'status' => 'available',
                'notes' => 'ใช้สำหรับรับ-ส่งช่วงขึ้นเขาเส้นทางทุรกันดาร',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($vehicles as $v) {
            DB::table('vehicles')->updateOrInsert(['license_plate' => $v['license_plate']], $v);
        }
    }
}