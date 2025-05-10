<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpVerificationController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'type' => 'required|in:registration,login,reset_password,order_confirmation',
        ]);
        
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated',
            ], 401);
        }
        
        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        
        // Delete existing OTPs of same type
        OtpVerification::where('user_id', $user->id)
            ->where('type', $request->type)
            ->delete();
            
        // Create new OTP
        $otpVerification = new OtpVerification();
        $otpVerification->user_id = $user->id;
        $otpVerification->otp_code = $otp;
        $otpVerification->phone = $user->phone;
        $otpVerification->email = $user->email;
        $otpVerification->type = $request->type;
        $otpVerification->expires_at = now()->addMinutes(15);
        $otpVerification->save();
        
        // TODO: Integrate with SMS and Email service to send OTP
        // For now, we'll just return success and show the OTP in development
        if (config('app.env') === 'local') {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'otp' => $otp, // Only for development!
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
        ]);
    }
    
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
            'type' => 'required|in:registration,login,reset_password,order_confirmation',
        ]);
        
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated',
            ], 401);
        }
        
        $otpVerification = OtpVerification::where('user_id', $user->id)
            ->where('otp_code', $request->otp_code)
            ->where('type', $request->type)
            ->where('is_verified', false)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$otpVerification) {
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn.',
            ], 400);
        }
        
        $otpVerification->verify();
        
        // If registration verification, mark user as verified
        if ($request->type === 'registration') {
            $user->is_verified = true;
            $user->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
        ]);
    }
    
    public function resendOtp(Request $request)
    {
        return $this->sendOtp($request);
    }
}
