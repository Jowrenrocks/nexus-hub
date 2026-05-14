<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        // Generate and store OTP in session
        $code = rand(100000, 999999);
        session([
            'otp_code'   => $code,
            'otp_target' => $request->email,
            'otp_type'   => 'email',
        ]);

        $response = Http::withToken(config('services.repohive_email.token'))
            ->acceptJson()
            ->timeout(30)
            ->post(rtrim(config('services.repohive_email.base_url'), '/') . '/email/send', [
                'to'      => $request->email,
                'subject' => 'Your Nexus Verification Code',
                'html'    => '<p style="font-family:sans-serif;">Your verification code is <strong style="font-size:24px;letter-spacing:4px;">' . $code . '</strong>.<br><br>Do not share this with anyone. This code expires in 10 minutes.</p>',
                'text'    => 'Your Nexus verification code is: ' . $code . '. Do not share this with anyone.',
            ]);

        if ($response->successful()) {
            return redirect()->route('otp.verify');
        }

        return back()
            ->withInput()
            ->with('email_error', 'Failed to send OTP. Please try again.');
    }
}
