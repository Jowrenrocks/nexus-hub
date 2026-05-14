<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailboxController extends Controller
{
    public function index()
    {
        $sent = session('sent_emails', []);
        return view('nexus.mailbox', compact('sent'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'to'      => ['required', 'email'],
            'subject' => ['required', 'string', 'max:255'],
            'body'    => ['required', 'string'],
        ]);

        $sent   = session('sent_emails', []);
        $sent[] = [
            'to'      => $request->to,
            'subject' => $request->subject,
            'body'    => $request->body,
            'date'    => now()->format('M d, Y h:i A'),
        ];

        session(['sent_emails' => $sent]);

        return redirect()
            ->route('nexus.mailbox')
            ->with('mail_sent', true);
    }
}
