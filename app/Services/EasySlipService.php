<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EasySlipService
{
    /**
     * ส่งรูปภาพสลิปไปตรวจกับ EasySlip API เซิร์ฟเวอร์จริง
     */
    public function verifySlip($imageFile, float $expectedAmount): array
    {
        $apiKey = Setting::get('easyslip_api_key', '');
        $enabled = Setting::get('easyslip_enabled', '1') === '1';

        if (!$enabled) {
            return [
                'success' => false,
                'status' => 'error',
                'message' => 'ระบบ EasySlip ถูกปิดใช้งานอยู่ กรุณาเปิดใช้งานที่หน้าตั้งค่าระบบ',
            ];
        }

        if (empty($apiKey)) {
            return [
                'success' => false,
                'status' => 'error',
                'message' => 'ยังไม่ได้ระบุ EasySlip API Key ในหน้าตั้งค่าระบบหลังบ้าน',
            ];
        }

        try {
            $fileStream = fopen($imageFile->getRealPath(), 'r');

            // เรียก API EasySlip
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . trim($apiKey),
                'Accept' => 'application/json',
            ])->attach(
                'file',
                $fileStream,
                $imageFile->getClientOriginalName()
            )->post('https://api.easyslip.com/v1/verify');

            if (is_resource($fileStream)) {
                fclose($fileStream);
            }

            $resData = $response->json();
            Log::info('EasySlip Response:', (array) $resData);

            // ตรวจสอบสถานะการตอบกลับ
            $isSuccess = ($response->successful() && (
                (isset($resData['status']) && (int)$resData['status'] === 200) ||
                (isset($resData['success']) && $resData['success'] === true)
            ));

            if ($isSuccess) {
                $slipData = $resData['data'] ?? [];
                
                // ดึงยอดเงินจากสลิป (รองรับทั้ง v1, v2 และโครงสร้างตัวเลขตรงๆ)
                $slipAmount = 0.0;
                if (isset($slipData['amount']['amount'])) {
                    $slipAmount = (float) $slipData['amount']['amount'];
                } elseif (isset($slipData['rawSlip']['amount']['amount'])) {
                    $slipAmount = (float) $slipData['rawSlip']['amount']['amount'];
                } elseif (isset($slipData['amount']) && is_numeric($slipData['amount'])) {
                    $slipAmount = (float) $slipData['amount'];
                }

                $transRef = $slipData['transRef'] ?? ($slipData['rawSlip']['transRef'] ?? null);

                // ตรวจสอบยอดเงิน (ยอดในสลิปต้องครบตามยอดจอง)
                if (round($slipAmount, 2) < round($expectedAmount, 2)) {
                    return [
                        'success' => false,
                        'status' => 'rejected',
                        'message' => "ยอดเงินในสลิป (฿" . number_format($slipAmount, 2) . ") ไม่ครบตามยอดที่ต้องชำระ (฿" . number_format($expectedAmount, 2) . ")",
                    ];
                }

                return [
                    'success' => true,
                    'status' => 'approved',
                    'message' => 'ตรวจสอบสลิปสำเร็จ ยอดเงินถูกต้องครบถ้วน',
                    'trans_ref' => $transRef,
                    'slip_data' => $slipData,
                ];
            }

            // กรณีอ่าน QR ไม่สำเร็จ หรือ API แจ้ง Error
            $apiMessage = $resData['message'] ?? ($resData['error']['message'] ?? 'ไม่สามารถตรวจสอบสลิปได้ (รูปภาพไม่ชัดเจน หรือตรวจไม่พบ QR Code ธนาคาร)');
            return [
                'success' => false,
                'status' => 'rejected',
                'message' => $apiMessage,
            ];

        } catch (\Exception $e) {
            Log::error('EasySlip API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการเชื่อมต่อ EasySlip: ' . $e->getMessage(),
            ];
        }
    }
}