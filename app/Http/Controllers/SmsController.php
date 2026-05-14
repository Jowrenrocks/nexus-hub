<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:30'],
        ]);

        // Generate and store OTP in session
        $code = rand(100000, 999999);
        session([
            'otp_code'   => $code,
            'otp_target' => $request->phone,
            'otp_type'   => 'phone',
        ]);

        $response = Http::withToken(config('services.repohive_sms.token'))
            ->acceptJson()
            ->timeout(30)
            ->post(rtrim(config('services.repohive_sms.base_url'), '/') . '/messages', [
                'phone'   => $request->phone,
                'message' => 'Your Nexus verification code is: ' . $code . '. Do not share this with anyone.',
            ]);

        if ($response->successful()) {
            return redirect()->route('otp.verify');
        }

        return back()
            ->withInput()
            ->with('sms_error', 'Failed to send OTP. Please try again.');
    }
}
