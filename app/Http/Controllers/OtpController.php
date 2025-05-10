<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $this->otpService->generateAndSendOtp($request->email);

        return response()->json([
            'message' => 'OTP đã được gửi đến email của bạn'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $isValid = $this->otpService->verifyOtp($request->email, $request->otp);

        if ($isValid) {
            return response()->json([
                'message' => 'OTP hợp lệ'
            ]);
        }

        return response()->json([
            'message' => 'OTP không hợp lệ hoặc đã hết hạn'
        ], 400);
    }
} 