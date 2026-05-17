<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexus — Email Verification</title>
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

    <h1>Email Verification</h1>
    <p class="muted">Enter your email address and we'll send a 6-digit code.</p>

    {{-- Show error if email failed --}}
    @if(session('email_error'))
      <div class="alert alert-error">
        <span>⚠️</span><span>{{ session('email_error') }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('otp.email.send') }}">
      @csrf

      <label>Email Address</label>
      <div class="input-wrap">
        <span class="input-icon">📧</span>
        <input
          name="email"
          class="nx-input"
          type="email"
          placeholder="you@example.com"
          value="{{ old('email') }}"
          required
        >
      </div>

      @error('email')
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
