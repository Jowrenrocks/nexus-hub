/* =====================================================
   NEXUS APP HUB — app.js
   Prototype OTP: 123456
   ===================================================== */

const PROTOTYPE_OTP = "123456";

/* ── OTP Send ── */
function sendPhoneOtp() {
  const phone = document.getElementById("phone").value.trim();
  if (!phone) { showInputError("phone", "Please enter a phone number."); return; }
  localStorage.setItem("otp_target", phone);
  localStorage.setItem("otp_type", "phone");
  window.location.href = "validate-otp.html";
}

function sendEmailOtp() {
  const email = document.getElementById("email").value.trim();
  if (!email) { showInputError("email", "Please enter an email address."); return; }
  localStorage.setItem("otp_target", email);
  localStorage.setItem("otp_type", "email");
  window.location.href = "validate-otp.html";
}

function showInputError(id, msg) {
  const el = document.getElementById(id);
  if (!el) return;
  el.style.borderColor = "var(--error)";
  el.style.boxShadow  = "0 0 0 3px rgba(251,113,133,0.18)";
  el.focus();
  setTimeout(() => { el.style.borderColor = ""; el.style.boxShadow = ""; }, 2500);
}

/* ── OTP Validate ── */
document.addEventListener("DOMContentLoaded", () => {
  const target = document.getElementById("otpTarget");
  if (target) {
    target.textContent = localStorage.getItem("otp_target") || "your account";
  }

  const otpInputs = document.querySelectorAll(".otp-input");
  otpInputs.forEach((input, i) => {
    input.addEventListener("input", () => {
      input.value = input.value.replace(/[^0-9]/g, "");
      if (input.value) {
        input.classList.add("filled");
        if (i < otpInputs.length - 1) otpInputs[i + 1].focus();
      } else {
        input.classList.remove("filled");
      }
    });
    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !input.value && i > 0) {
        otpInputs[i - 1].focus();
        otpInputs[i - 1].classList.remove("filled");
      }
    });
  });

  if (otpInputs.length) otpInputs[0].focus();
});

function validateOtp() {
  const inputs = document.querySelectorAll(".otp-input");
  let otp = "";
  inputs.forEach(i => otp += i.value);

  const msgEl = document.getElementById("message");

  if (otp.length < 6) {
    showAlert(msgEl, "error", "⚠️ Please enter all 6 digits.");
    return;
  }

  if (otp === PROTOTYPE_OTP) {
    localStorage.setItem("verified_user", localStorage.getItem("otp_target") || "user@nexus.app");
    // Success flash
    showAlert(msgEl, "success", "✓ Verified! Redirecting…");
    setTimeout(() => window.location.href = "mailbox.html", 800);
  } else {
    showAlert(msgEl, "error", "✕ Invalid code. Hint: try 123456");
    inputs.forEach(i => { i.value = ""; i.classList.remove("filled"); });
    inputs[0].focus();
  }
}

function showAlert(el, type, text) {
  if (!el) return;
  el.style.display = "flex";
  el.className = `alert alert-${type}`;
  el.textContent = text;
}

/* ── Google login seeder ── */
function loginWithGoogle() {
  localStorage.setItem("verified_user", "google.user@gmail.com");
  localStorage.setItem("auth_provider", "google");
  localStorage.setItem("user_name", "Google User");
  window.location.href = "mailbox.html";
}

/* ── Mailbox ── */
const inboxEmails = [
  {
    title: "Welcome to Nexus Mail",
    from: "Nexus Team",
    time: "Just now",
    body: "Your secure mailbox is now ready. You can receive workspace updates, system notifications, and team messages here. We're glad to have you on board!"
  },
  {
    title: "OTP Verification Successful",
    from: "Security",
    time: "2 min ago",
    body: "Your account verification was completed successfully. This helps keep your mailbox and workspace protected. If you did not perform this action, please contact support immediately."
  },
  {
    title: "Project Workspace Invitation",
    from: "Douglas Hill",
    time: "1 hr ago",
    body: "You have been added to a Nexus workspace. Open your dashboard to view tasks, repositories, and updates. Looking forward to collaborating with you!"
  }
];

// Use server-side sent emails (Laravel) when available, fallback to localStorage
let sentEmails = (typeof SENT_FROM_SERVER !== 'undefined') ? SENT_FROM_SERVER : (JSON.parse(localStorage.getItem("sent_emails")) || []);
let currentBox = "inbox";

function loadMailbox() {
  const userEmailEl = document.getElementById("userEmail");
  if (userEmailEl) {
    userEmailEl.textContent = localStorage.getItem("verified_user") || "user@nexus.app";
  }
  // update avatar letter
  const av = document.querySelector(".avatar-circle");
  if (av) {
    const u = localStorage.getItem("verified_user") || "U";
    av.textContent = u[0].toUpperCase();
  }
  showInbox();
}

function renderEmails(emails) {
  const list = document.getElementById("mailList");
  list.innerHTML = "";

  if (!emails.length) {
    list.innerHTML = `<div class="mail-item"><span class="sender">No messages</span><span class="subject">This folder is empty.</span></div>`;
    return;
  }

  emails.forEach((mail, i) => {
    const item = document.createElement("div");
    item.className = "mail-item";
    item.onclick = () => openEmail(mail, item);
    item.innerHTML = `
      <span class="sender">${mail.from || "To: " + mail.to}</span>
      <span class="subject">${mail.title || mail.subject}</span>
      <span class="snippet">${mail.body}</span>
    `;
    list.appendChild(item);
    if (i === 0) { item.classList.add("active"); openEmail(mail, item); }
  });
}

function openEmail(mail, el) {
  document.querySelectorAll(".mail-item").forEach(i => i.classList.remove("active"));
  if (el) el.classList.add("active");

  const ph = document.getElementById("previewHeader");
  const pt = document.getElementById("previewTitle");
  const pm = document.getElementById("previewMeta");
  const pb = document.getElementById("previewBody");

  if (ph) ph.style.display = "block";
  if (pt) pt.textContent = mail.title || mail.subject;
  if (pm) pm.textContent = mail.from ? `From: ${mail.from}  ·  ${mail.time || ""}` : `To: ${mail.to}  ·  ${mail.date || ""}`;
  if (pb) pb.innerHTML = `<p>${mail.body}</p>`;
}

function showInbox(btn) {
  currentBox = "inbox";
  document.getElementById("mailTitle").textContent = "Inbox";
  setActiveNav(btn);
  renderEmails(inboxEmails);
}

function showSent(btn) {
  currentBox = "sent";
  document.getElementById("mailTitle").textContent = "Sent";
  setActiveNav(btn);
  renderEmails(sentEmails);
}

function setActiveNav(btn) {
  if (!btn) return;
  document.querySelectorAll(".nav-item").forEach(n => n.classList.remove("active"));
  btn.classList.add("active");
}

function filterMail() {
  const kw = document.getElementById("searchMail").value.toLowerCase();
  const emails = currentBox === "inbox" ? inboxEmails : sentEmails;
  renderEmails(emails.filter(m => JSON.stringify(m).toLowerCase().includes(kw)));
}

function openCompose() {
  document.getElementById("composeModal").classList.add("active");
}

function closeCompose() {
  document.getElementById("composeModal").classList.remove("active");
}

function sendEmail() {
  const to      = document.getElementById("composeTo").value.trim();
  const subject = document.getElementById("composeSubject").value.trim();
  const body    = document.getElementById("composeBody").value.trim();

  if (!to || !subject || !body) {
    alert("Please fill in all fields before sending.");
    return;
  }

  const email = { to, subject, title: subject, body, date: new Date().toLocaleString() };
  sentEmails.unshift(email);
  localStorage.setItem("sent_emails", JSON.stringify(sentEmails));

  const sc = document.getElementById("sentCount");
  if (sc) sc.textContent = sentEmails.length;

  document.getElementById("composeTo").value = "";
  document.getElementById("composeSubject").value = "";
  document.getElementById("composeBody").value = "";

  closeCompose();
  showSent(document.getElementById('nav-sent'));
}

/* ── Chatbot ── */
function sendChat() {
  const input = document.getElementById("chatInput");
  const message = input.value.trim();
  if (!message) return;

  appendMessage(message, "user");
  input.value = "";

  showTyping();
  setTimeout(() => {
    removeTyping();
    appendMessage(generateBotReply(message), "bot");
  }, 900);
}

function quickAsk(text) {
  document.getElementById("chatInput").value = text;
  sendChat();
}

function handleChatKey(e) {
  if (e.key === "Enter") sendChat();
}

function appendMessage(text, sender) {
  const win = document.getElementById("chatWindow");
  const wrap = document.createElement("div");
  wrap.className = `chat-message ${sender}`;
  wrap.innerHTML = `
    <div class="avatar-sm">${sender === "user" ? "👤" : "✦"}</div>
    <div class="bubble">${text}</div>
  `;
  win.appendChild(wrap);
  win.scrollTop = win.scrollHeight;
}

function showTyping() {
  const win = document.getElementById("chatWindow");
  const t = document.createElement("div");
  t.className = "chat-message bot"; t.id = "typingIndicator";
  t.innerHTML = `
    <div class="avatar-sm">✦</div>
    <div class="bubble"><div class="typing-wrap"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div></div>
  `;
  win.appendChild(t);
  win.scrollTop = win.scrollHeight;
}

function removeTyping() {
  const t = document.getElementById("typingIndicator");
  if (t) t.remove();
}

function generateBotReply(msg) {
  const t = msg.toLowerCase();
  if (t.includes("email") || t.includes("summarize") || t.includes("inbox"))
    return "Your inbox has 3 messages: a welcome from the Nexus team, a security verification confirmation, and a workspace invitation from Douglas Hill. Want me to open one?";
  if (t.includes("sent"))
    return "You can check your sent mail in the Mailbox. Click 'Sent' in the sidebar to see all composed messages.";
  if (t.includes("compose") || t.includes("write"))
    return "To compose an email, open the Mailbox and click the '✦ Compose' button. I can also help you draft your message — just tell me who it's for!";
  if (t.includes("otp") || t.includes("verification") || t.includes("code"))
    return "OTP (One-Time Password) is a 6-digit code sent to your phone or email to verify your identity. In this prototype, the OTP is always <strong>123456</strong>.";
  if (t.includes("mailbox") || t.includes("mail"))
    return "Your mailbox is fully set up. Navigate there anytime using the sidebar or the App Hub.";
  if (t.includes("hello") || t.includes("hi") || t.includes("hey"))
    return "Hi there! 👋 I'm your Nexus AI assistant. Ask me about your emails, OTP verification, or anything else I can help with.";
  return "I can help with email summaries, composing messages, OTP verification, and navigating your Nexus workspace. What would you like to do?";
}
