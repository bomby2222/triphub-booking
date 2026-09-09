<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RoleAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['id' => 1, 'name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'ผู้ดูแลระบบสูงสุด จัดการได้ทุกฟังก์ชัน'],
            ['id' => 2, 'name' => 'Admin', 'slug' => 'admin', 'description' => 'ผู้ดูแลระบบ จัดการกิจกรรม รอบเดินทาง และการจอง'],
            ['id' => 3, 'name' => 'Finance', 'slug' => 'finance', 'description' => 'เจ้าหน้าที่การเงิน ตรวจสอบสลิปและบันทึกรายรับ-รายจ่าย'],
            ['id' => 4, 'name' => 'Guide', 'slug' => 'guide', 'description' => 'ไกด์นำทาง ดูข้อมูลทริปและเช็คชื่อผู้เข้าร่วม'],
        ];
        DB::table('roles')->insertOrIgnore($roles);

        // 2. Permissions
        $permissions = [
            ['name' => 'จัดการกิจกรรม', 'slug' => 'manage_activities', 'group' => 'Activities'],
            ['name' => 'จัดการรอบเดินทาง', 'slug' => 'manage_schedules', 'group' => 'Activities'],
            ['name' => 'จัดการการจอง', 'slug' => 'manage_bookings', 'group' => 'Bookings'],
            ['name' => 'ตรวจสอบการเงินและสลิป', 'slug' => 'verify_payments', 'group' => 'Finance'],
            ['name' => 'จัดการรายรับรายจ่าย', 'slug' => 'manage_accounting', 'group' => 'Finance'],
            ['name' => 'เช็คชื่อผู้เข้าร่วม', 'slug' => 'checkin_members', 'group' => 'Checkin'],
            ['name' => 'จัดการรายงาน', 'slug' => 'manage_reports', 'group' => 'Reports'],
            ['name' => 'จัดการสิทธิ์ผู้ดูแล', 'slug' => 'manage_admins', 'group' => 'System'],
        ];
        foreach ($permissions as $perm) {
            DB::table('permissions')->updateOrInsert(['slug' => $perm['slug']], $perm);
        }

        // 3. Super Admin & Staff Accounts (Password: 12345678)
        $admins = [
            [
                'role_id' => 1,
                'name' => 'Super Admin',
                'email' => 'superadmin@hiking.local',
                'password' => Hash::make('12345678'),
                'phone' => '0812345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'name' => 'Trip Manager Admin',
                'email' => 'admin@hiking.local',
                'password' => Hash::make('12345678'),
                'phone' => '0823456789',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'name' => 'Finance Officer',
                'email' => 'finance@hiking.local',
                'password' => Hash::make('12345678'),
                'phone' => '0834567890',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'name' => 'Lead Guide Somchai',
                'email' => 'guide@hiking.local',
                'password' => Hash::make('12345678'),
                'phone' => '0845678901',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($admins as $admin) {
            DB::table('admins')->updateOrInsert(['email' => $admin['email']], $admin);
        }

        // 4. Sample Users (Password: 12345678)
        $users = [
            [
                'name' => 'ธนภัทร ชาญบำรุง',
                'email' => 'user@hiking.local',
                'password' => Hash::make('12345678'),
                'phone' => '0891122334',
                'birth_date' => '1998-05-15',
                'points' => 150,
                'membership_tier' => 'Silver',
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'กิตติศักดิ์ พงษ์ไพร',
                'email' => 'kittisak@hiking.local',
                'password' => Hash::make('12345678'),
                'phone' => '0867788990',
                'birth_date' => '1995-10-20',
                'points' => 420,
                'membership_tier' => 'Gold',
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(['email' => $user['email']], $user);
        }
    }
}