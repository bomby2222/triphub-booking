<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = ['id'];

    /**
     * ดึงค่าการตั้งค่าตาม Key (ดึงตรงจากฐานข้อมูล ป้องกันปัญหาตาราง cache หาย)
     */
    public static function get(string $key, $default = null)
    {
        try {
            $setting = self::where('key', $key)->first();
            return ($setting && !is_null($setting->value) && $setting->value !== '') ? $setting->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * บันทึกหรืออัปเดตการตั้งค่า
     */
    public static function set(string $key, $value, string $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value ?? '',
                'group' => $group,
            ]
        );
    }
}