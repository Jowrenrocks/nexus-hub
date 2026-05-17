<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\MailboxController;

// Root redirects to Nexus Hub
Route::get('/', fn() => redirect()->route('nexus.hub'));

// Nexus Hub
Route::get('/nexus', fn() => view('nexus.index'))->name('nexus.hub');

// OTP — Phone
Route::get('/otp/phone',  fn() => view('nexus.otp-phone'))->name('nexus.otp.phone');
Route::post('/otp/phone', [SmsController::class, 'sendSms'])->name('otp.phone.send');

// OTP — Email
Route::get('/otp/email',  fn() => view('nexus.otp-email'))->name('nexus.otp.email');
Route::post('/otp/email', [EmailController::class, 'sendEmail'])->name('otp.email.send');

// OTP — Validate
Route::get('/otp/verify',  [OtpController::class, 'showVerify'])->name('otp.verify');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify.submit');

// Mailbox
Route::get('/nexus/mailbox',  [MailboxController::class, 'index'])->name('nexus.mailbox');
Route::post('/nexus/mailbox', [MailboxController::class, 'send'])->name('nexus.mailbox.send');

// AI Chatbot
Route::get('/nexus/chatbot', fn() => view('nexus.ai-chatbot'))->name('nexus.chatbot');