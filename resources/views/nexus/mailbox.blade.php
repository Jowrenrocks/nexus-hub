<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexus — Mailbox</title>
  <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>
<body>

<div class="aurora-bg"><div class="aurora-orb"></div></div>

<div class="mailbox-page">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <span class="dot">⬡</span> Nexus
    </div>

    <button class="compose-btn" onclick="openCompose()">✦ Compose</button>

    <div class="sidebar-label">Mailbox</div>
    <button id="nav-inbox" class="nav-item active" onclick="showInbox(this)">
      <span class="nav-left">📥 Inbox</span>
      <span class="nav-badge">3</span>
    </button>
    <button id="nav-sent" class="nav-item" onclick="showSent(this)">
      <span class="nav-left">📤 Sent</span>
      <span class="nav-badge" id="sentCount">{{ count($sent) }}</span>
    </button>
    <button class="nav-item">
      <span class="nav-left">📝 Drafts</span>
      <span class="nav-badge">0</span>
    </button>
    <button class="nav-item">
      <span class="nav-left">🗄 Archived</span>
      <span class="nav-badge">4</span>
    </button>

    <div class="sidebar-label">Navigate</div>
    <a class="nav-item" href="{{ route('nexus.chatbot') }}">
      <span class="nav-left">🤖 AI Chatbot</span>
    </a>
    <a class="nav-item" href="{{ route('nexus.hub') }}">
      <span class="nav-left">⬡ App Hub</span>
    </a>

    <div class="sidebar-footer">
      <div class="user-row">
        <div class="avatar-circle">
          {{ strtoupper(substr(session('otp_target', auth()->user()->email ?? 'U'), 0, 1)) }}
        </div>
        <div class="user-info">
          <div class="name">Verified User</div>
          <div class="email">{{ session('otp_target', auth()->user()->email ?? 'user@nexus.app') }}</div>
        </div>
      </div>
    </div>
  </aside>

  <!-- Main -->
  <main class="mail-main">
    <header class="topbar">
      <h2 id="mailTitle">Inbox</h2>
      <div class="topbar-right">
        <div class="search-wrap">
          <span class="search-icon">🔍</span>
          <input id="searchMail" class="search-input" placeholder="Search mail..." oninput="filterMail()">
        </div>
        <a class="btn-icon" href="{{ route('nexus.hub') }}" title="App Hub">⬡</a>
      </div>
    </header>

    <div class="mail-split" style="height:calc(100vh - 64px); overflow:hidden;">
      <div id="mailList" class="mail-list"></div>

      <div class="mail-preview">
        <div class="preview-header" id="previewHeader" style="display:none;">
          <h2 id="previewTitle"></h2>
          <p class="preview-meta" id="previewMeta"></p>
        </div>
        <div class="preview-body" id="previewBody">
          <div class="empty-state">
            <div class="icon">📬</div>
            <p>Select an email to read</p>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<!-- Compose Modal — posts to Laravel route -->
<div id="composeModal" class="modal-overlay">
  <div class="modal-card">
    <div class="modal-header">
      <h2>Compose Email</h2>
      <button class="modal-close" onclick="closeCompose()">✕</button>
    </div>

    <form method="POST" action="{{ route('nexus.mailbox.send') }}" id="composeForm">
      @csrf

      <label>To</label>
      <div class="input-wrap">
        <span class="input-icon">👤</span>
        <input name="to" class="nx-input" type="email" placeholder="recipient@example.com" required>
      </div>

      <label>Subject</label>
      <div class="input-wrap">
        <span class="input-icon">✏️</span>
        <input name="subject" class="nx-input" type="text" placeholder="Email subject line" required>
      </div>

      <label>Message</label>
      <textarea name="body" placeholder="Write your message here..." required></textarea>

      <button class="btn btn-primary" type="submit" style="margin-top:18px;">
        📤 Send Email
      </button>
    </form>
  </div>
</div>

{{-- Pass sent emails from Laravel session into JS --}}
<script>
  const SENT_FROM_SERVER = @json($sent);
</script>
<script src="{{ asset('assets/app.js') }}"></script>
<script>
  loadMailbox();

  @if(session('mail_sent'))
    // If we just sent an email, open the Sent tab
    window.addEventListener('DOMContentLoaded', () => {
      showSent(document.getElementById('nav-sent'));
    });
  @endif
</script>

</body>
</html>
