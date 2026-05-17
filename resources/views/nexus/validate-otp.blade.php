<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexus — Validate OTP</title>
  <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>
<body>

<div class="aurora-bg"><div class="aurora-orb"></div></div>

<div class="center-screen">
  <div class="card">
    <div class="brand"><span class="brand-dot"></span>Nexus Hub</div>

    <div class="steps">
      <div class="step-dot done"></div>
      <div class="step-dot active"></div>
      <div class="step-dot"></div>
    </div>

    <h1>Enter Your Code</h1>
    <p class="muted">
      Code sent to
      <strong style="color:var(--accent2)">
        {{ session('otp_target', 'your account') }}
      </strong>.
      Check your inbox.
    </p>

    {{-- Wrong OTP error --}}
    @if(session('otp_error'))
      <div class="alert alert-error">
        <span>✕</span><span>{{ session('otp_error') }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('otp.verify.submit') }}">
      @csrf

      {{-- 6 individual boxes — JS auto-advances, form collects as otp[] --}}
      <div class="otp-grid">
        @for($i = 0; $i < 6; $i++)
          <input
            maxlength="1"
            class="otp-input"
            inputmode="numeric"
            name="otp[]"
            autocomplete="off"
          >
        @endfor
      </div>

      <button class="btn btn-primary" type="submit">
        Verify &amp; Continue <span>→</span>
      </button>
    </form>

    <p class="text-center mt-sm" style="font-size:13px; color:var(--muted);">
      Didn't receive a code?
      <a href="{{ route('nexus.hub') }}" style="color:var(--accent2); text-decoration:none; font-weight:600;">Resend</a>
    </p>

    <a class="link" href="{{ route('nexus.hub') }}">← Back to Hub</a>
  </div>
</div>

<script>
  // Auto-advance OTP boxes
  document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.otp-input');
    inputs.forEach((inp, i) => {
      inp.addEventListener('input', () => {
        inp.value = inp.value.replace(/[^0-9]/g, '');
        inp.classList.toggle('filled', !!inp.value);
        if (inp.value && i < inputs.length - 1) inputs[i + 1].focus();
      });
      inp.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !inp.value && i > 0) {
          inputs[i - 1].focus();
          inputs[i - 1].classList.remove('filled');
        }
      });
    });
    if (inputs.length) inputs[0].focus();
  });
</script>

</body>
</html>
