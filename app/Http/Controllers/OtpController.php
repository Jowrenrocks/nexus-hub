<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function showVerify()
    {
        return view('nexus.validate-otp');
    }

    public function verify(Request $request)
    {
        // Combine the 6 individual boxes into one string
        $entered = implode('', $request->input('otp', []));
        $stored  = session('otp_code');

        if ($stored && $entered == $stored) {
            session()->forget('otp_code');
            return redirect()
                ->route('nexus.mailbox')
                ->with('verified', true);
        }

        return back()->with('otp_error', 'Invalid code. Please try again.');
    }
}
