<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexus — AI Chatbot</title>
  <link rel="stylesheet" href="{{ asset('nexus/styles.css') }}">
</head>
<body>

<div class="aurora-bg"><div class="aurora-orb"></div></div>

<div class="chatbot-page">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <span class="dot">⬡</span> Nexus
    </div>

    <div class="sidebar-label">AI Chat</div>
    <button class="nav-item active">
      <span class="nav-left">💬 Current Chat</span>
    </button>
    <button class="nav-item" onclick="clearChat()">
      <span class="nav-left">➕ New Chat</span>
    </button>

    <div class="sidebar-label">Navigate</div>
    <a class="nav-item" href="{{ route('nexus.mailbox') }}">
      <span class="nav-left">📬 Mailbox</span>
    </a>
    <a class="nav-item" href="{{ route('nexus.hub') }}">
      <span class="nav-left">⬡ App Hub</span>
    </a>

    <div class="sidebar-footer">
      <div class="user-row">
        <div class="avatar-circle" style="background:linear-gradient(135deg,var(--accent),var(--teal))">🤖</div>
        <div class="user-info">
          <div class="name">Nexus AI</div>
          <div class="email">Always available</div>
        </div>
      </div>
    </div>
  </aside>

  <!-- Chat Main -->
  <main class="chat-main">
    <header class="topbar">
      <div style="display:flex; align-items:center; gap:12px;">
        <div style="width:10px; height:10px; border-radius:50%; background:var(--teal); box-shadow:0 0 8px var(--teal);"></div>
        <h2>AI Assistant</h2>
      </div>
      <div class="topbar-right">
        <a class="btn-icon" href="{{ route('nexus.hub') }}" title="App Hub">⬡</a>
      </div>
    </header>

    <div id="chatWindow" class="chat-window"></div>

    <div class="chat-input-area">
      <div class="quick-chips">
        <button class="chip" onclick="quickAsk('Summarize my emails')">📋 Summarize emails</button>
        <button class="chip" onclick="quickAsk('Help me compose an email')">✍️ Compose email</button>
        <button class="chip" onclick="quickAsk('Show my sent history')">📤 Sent history</button>
        <button class="chip" onclick="quickAsk('What is OTP verification?')">🔐 About OTP</button>
      </div>
      <div class="chat-input-row">
        <input id="chatInput" class="nx-input-bare" type="text" placeholder="Ask anything…" onkeydown="handleChatKey(event)">
        <button class="send-btn" onclick="sendChat()">➤</button>
      </div>
    </div>
  </main>
</div>

<script src="{{ asset('nexus/app.js') }}"></script>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    appendMessage("👋 Hello! I'm the Nexus AI assistant. I can help you with mailbox summaries, email composition, OTP verification info, and general questions. What can I do for you?", "bot");
  });

  function clearChat() {
    document.getElementById('chatWindow').innerHTML = '';
    appendMessage("Chat cleared! How can I help you?", "bot");
  }
</script>

</body>
</html>
