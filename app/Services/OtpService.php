<?php

namespace App\Services;

use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpService
{
    public function generateAndSendOtp($email)
    {
        // Xóa OTP cũ nếu có
        Otp::where('email', $email)->delete();

        // Tạo OTP mới
        $otp = Str::random(6);
        $expiresAt = now()->addMinutes(5);

        Otp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => $expiresAt
        ]);

        // Gửi email
        Mail::raw("Mã OTP của bạn là: {$otp}. Mã này sẽ hết hạn sau 5 phút.", function($message) use ($email) {
            $message->to($email)
                   ->subject('Mã xác thực OTP');
        });

        return true;
    }

    public function verifyOtp($email, $otp)
    {
        $otpRecord = Otp::where('email', $email)
                       ->where('otp', $otp)
                       ->where('expires_at', '>', now())
                       ->first();

        if ($otpRecord) {
            $otpRecord->delete();
            return true;
        }

        return false;
    }
} 