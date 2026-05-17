<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexus — Phone Verification</title>
  <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>
<body>

<div class="aurora-bg"><div class="aurora-orb"></div></div>

<div class="center-screen">
  <div class="card">
    <div class="brand"><span class="brand-dot"></span>Nexus Hub</div>

    <div class="steps">
      <div class="step-dot active"></div>
      <div class="step-dot"></div>
      <div class="step-dot"></div>
    </div>

    <h1>Phone Verification</h1>
    <p class="muted">Enter your phone number and we'll send a 6-digit code.</p>

    {{-- Show error if SMS failed --}}
    @if(session('sms_error'))
      <div class="alert alert-error">
        <span>⚠️</span><span>{{ session('sms_error') }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('otp.phone.send') }}">
      @csrf

      <label>Phone Number</label>
      <div class="input-wrap">
        <span class="input-icon">📱</span>
        <input
          name="phone"
          class="nx-input"
          type="tel"
          placeholder="+63 900 000 0000"
          value="{{ old('phone') }}"
          required
        >
      </div>

      @error('phone')
        <div class="alert alert-error" style="margin-bottom:12px;">
          <span>⚠️</span><span>{{ $message }}</span>
        </div>
      @enderror

      <button class="btn btn-primary" type="submit">
        Send Verification Code <span>→</span>
      </button>
    </form>

    <a class="link" href="{{ route('nexus.hub') }}">← Back to Hub</a>
  </div>
</div>

</body>
</html>
