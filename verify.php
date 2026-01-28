<?php
$pageTitle = "Verify Email";
session_start();

require_once "db.php";
require_once "mail.php"; // ✅ REAL EMAIL sending

$email = trim($_GET["email"] ?? "");
$error = "";
$success = "";
$info = "";

if (isset($_SESSION["verify_info"])) {
  $info = $_SESSION["verify_info"];
  unset($_SESSION["verify_info"]);
}

$expiresAtJs = null;

if ($email === "") {
  $error = "Missing email. Please sign up again.";
}

function loadUserForEmail($conn, $email) {
  $stmt = $conn->prepare("SELECT id, fullname, is_verified, verification_code_hash, verification_expires_at FROM users WHERE email=? LIMIT 1");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();
  $row = $res->fetch_assoc();
  $stmt->close();
  return $row ?: null;
}

function loadExpiryForEmail($conn, $email) {
  $stmt = $conn->prepare("SELECT verification_expires_at FROM users WHERE email=? LIMIT 1");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();
  $row = $res->fetch_assoc();
  $stmt->close();
  return $row["verification_expires_at"] ?? null;
}

/* ========================= RESEND ========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["resend"])) {
  $email = trim($_POST["email"] ?? "");

  if ($email === "") {
    $error = "Missing email. Please sign up again.";
  } else {
    $u = loadUserForEmail($conn, $email);

    if (!$u) {
      $error = "Account not found.";
    } elseif ((int)$u["is_verified"] === 1) {
      $success = "Your email is already verified. You can log in.";
    } else {
      $newCode   = (string)random_int(100000, 999999);
      $codeHash  = password_hash($newCode, PASSWORD_DEFAULT);
      $expiresAt = date("Y-m-d H:i:s", time() + 10 * 60); // 10 mins

      $upd = $conn->prepare("UPDATE users SET verification_code_hash=?, verification_expires_at=? WHERE id=?");
      $upd->bind_param("ssi", $codeHash, $expiresAt, $u["id"]);
      $upd->execute();
      $upd->close();

      // ✅ Send real email
      $sent = sendVerificationCode($email, $u["fullname"] ?? "User", $newCode);

      if ($sent) {
        $info = "✅ A new verification code has been sent to your email.";
      } else {
        $error = "We generated a new code, but failed to send the email. Please try again.";
      }
    }
  }
}

/* ========================= VERIFY ========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["verify"])) {
  $email = trim($_POST["email"] ?? "");
  $code  = trim($_POST["code"] ?? "");

  if ($email === "" || $code === "") {
    $error = "Enter the verification code.";
  } else {
    $u = loadUserForEmail($conn, $email);

    if (!$u) {
      $error = "Account not found.";
    } elseif ((int)$u["is_verified"] === 1) {
      $success = "Already verified. You can log in.";
    } else {
      $expires = strtotime($u["verification_expires_at"] ?? "");

      if (!$expires || time() > $expires) {
        $error = "Code expired. Please click Resend Code.";
      } elseif (!password_verify($code, $u["verification_code_hash"] ?? "")) {
        $error = "Invalid code.";
      } else {
        $upd = $conn->prepare("UPDATE users SET is_verified=1, verification_code_hash=NULL, verification_expires_at=NULL WHERE id=?");
        $upd->bind_param("i", $u["id"]);
        $upd->execute();
        $upd->close();

        $success = "✅ Email verified successfully! You can now log in.";
      }
    }
  }
}

// Load expiry for countdown
if (!$success && $email !== "") {
  $expiresAtDb = loadExpiryForEmail($conn, $email);
  if ($expiresAtDb) {
    $expiresAtJs = strtotime($expiresAtDb) * 1000;
  }
}

// ✅ Use your site header (keeps navbar consistent)
include "header.php";
?>

<div class="relative min-h-[calc(100vh-64px)] overflow-x-hidden bg-slate-50">
  <!-- soft background glow -->
  <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-br from-emerald-50 via-white to-teal-50"></div>

  <div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-8 sm:py-12">
    <div class="w-full max-w-md">

      <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">

        <!-- Header -->
        <div class="p-5 sm:p-6 bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-600 text-white">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-white/20 flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </div>
            <div class="min-w-0">
              <h1 class="text-xl sm:text-2xl font-extrabold leading-tight">Verify Your Email</h1>
              <p class="text-white/90 text-xs sm:text-sm mt-1">
                We sent a verification code to:
              </p>
              <p class="text-white font-semibold text-xs sm:text-sm break-all">
                <?php echo htmlspecialchars($email); ?>
              </p>
            </div>
          </div>
        </div>

        <div class="p-5 sm:p-6 md:p-8">

          <?php if (!$success && $expiresAtJs): ?>
            <div id="countdownBox" class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200">
              <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-700">Code expires in:</span>
                <span id="countdown" class="font-mono text-xl font-extrabold text-emerald-700">--:--</span>
              </div>
            </div>
          <?php endif; ?>

          <?php if ($info): ?>
            <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200">
              <p class="text-sm font-semibold text-emerald-800"><?php echo htmlspecialchars($info); ?></p>
            </div>
          <?php endif; ?>

          <?php if ($error): ?>
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200">
              <p class="text-sm font-semibold text-red-800"><?php echo htmlspecialchars($error); ?></p>
            </div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="mb-4 p-5 rounded-xl bg-emerald-50 border border-emerald-200">
              <p class="text-sm font-semibold text-emerald-800"><?php echo htmlspecialchars($success); ?></p>
            </div>

            <div class="grid gap-3">
              <!-- If you have login modal, this button can open it. Otherwise it redirects. -->
              <button type="button" id="openLoginAfterVerify"
                class="w-full inline-flex items-center justify-center px-6 py-3 font-extrabold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition shadow-md">
                Go to Login →
              </button>

              <a href="index.php"
                 class="w-full inline-flex items-center justify-center px-6 py-3 font-bold text-emerald-800 bg-emerald-100 rounded-xl hover:bg-emerald-200 transition border border-emerald-200">
                Back to Home
              </a>
            </div>
          <?php endif; ?>

          <?php if (!$success): ?>
            <form id="verifyForm" method="POST" action="" class="space-y-4">
              <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
              <input type="hidden" name="verify" value="1">

              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Verification Code</label>
                <input
                  id="codeInput"
                  type="text"
                  name="code"
                  maxlength="6"
                  inputmode="numeric"
                  required
                  autocomplete="one-time-code"
                  class="w-full px-4 py-4 text-center text-2xl font-mono font-extrabold tracking-widest border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500"
                  placeholder="000000">
                <p class="text-xs text-slate-500 mt-2">
                  Tip: On mobile, check your email app — some phones can auto-fill the code.
                </p>
              </div>

              <button
                id="verifyBtn"
                type="submit"
                class="w-full py-4 font-extrabold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition shadow-md">
                Verify Email
              </button>
            </form>

            <form method="POST" class="mt-4">
              <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
              <button
                id="resendBtn"
                type="submit"
                name="resend"
                class="w-full py-3 font-extrabold text-emerald-800 bg-emerald-100 rounded-xl hover:bg-emerald-200 transition border border-emerald-200">
                Resend Code
              </button>
              <p id="resendHint" class="text-xs text-slate-500 mt-2 text-center"></p>
            </form>
          <?php endif; ?>

        </div>
      </div>

      <p class="text-center text-sm text-slate-600 mt-6">
        Need help? <a href="mailto:support@example.com" class="font-semibold text-emerald-700 hover:underline">Contact Support</a>
      </p>
    </div>
  </div>
</div>

<?php if (!$success && $expiresAtJs): ?>
<script>
  const expiresAt = <?php echo (int)$expiresAtJs; ?>;
  const countdownEl = document.getElementById("countdown");
  const boxEl = document.getElementById("countdownBox");
  const codeInput = document.getElementById("codeInput");
  const verifyBtn = document.getElementById("verifyBtn");
  const verifyForm = document.getElementById("verifyForm");

  const resendBtn = document.getElementById("resendBtn");
  const resendHint = document.getElementById("resendHint");

  if (codeInput) codeInput.focus();

  // Auto-submit at 6 digits (mobile-safe)
  if (codeInput) {
    codeInput.addEventListener("input", () => {
      codeInput.value = codeInput.value.replace(/\D/g, "").slice(0, 6);
      if (codeInput.value.length === 6 && verifyBtn && !verifyBtn.disabled) {
        setTimeout(() => {
          // requestSubmit not supported on some mobile browsers
          if (verifyForm?.requestSubmit) verifyForm.requestSubmit(verifyBtn);
          else verifyForm.submit();
        }, 150);
      }
    });
  }

  // Resend cooldown (30 seconds) - purely UI
  let resendCooldown = 30;
  function startResendCooldown(){
    if (!resendBtn) return;
    resendBtn.disabled = true;
    resendBtn.classList.add("opacity-60", "cursor-not-allowed");
    const t = setInterval(() => {
      resendHint.textContent = `You can resend again in ${resendCooldown}s`;
      resendCooldown--;
      if (resendCooldown < 0) {
        clearInterval(t);
        resendBtn.disabled = false;
        resendBtn.classList.remove("opacity-60", "cursor-not-allowed");
        resendHint.textContent = "";
      }
    }, 1000);
  }

  function pad(n){ return String(n).padStart(2, "0"); }

  function setExpiredUI(){
    if (verifyBtn) {
      verifyBtn.disabled = true;
      verifyBtn.classList.add("opacity-60", "cursor-not-allowed");
    }
    if (codeInput) {
      codeInput.disabled = true;
      codeInput.classList.add("bg-slate-100", "cursor-not-allowed");
    }
    if (boxEl) {
      boxEl.className = "mb-4 p-4 rounded-xl bg-red-50 border border-red-200";
      boxEl.innerHTML = `<p class="text-sm font-semibold text-red-800">Code expired. Please click <b>Resend Code</b>.</p>`;
    }
  }

  function tick(){
    const diff = expiresAt - Date.now();
    if (diff <= 0) {
      setExpiredUI();
      clearInterval(timer);
      return;
    }
    const totalSeconds = Math.floor(diff / 1000);
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    if (countdownEl) countdownEl.textContent = `${pad(minutes)}:${pad(seconds)}`;
  }

  tick();
  const timer = setInterval(tick, 1000);

  // Start resend cooldown after click (good UX)
  if (resendBtn) {
    resendBtn.addEventListener("click", () => {
      startResendCooldown();
    });
  }
</script>
<?php endif; ?>

<script>
  // If your header has login modal, open it after verify success
  document.getElementById("openLoginAfterVerify")?.addEventListener("click", () => {
    const loginModal = document.getElementById("loginModal");
    if (loginModal) {
      loginModal.classList.remove("hidden");
      document.body.style.overflow = "hidden";
    } else {
      window.location.href = "login.php";
    }
  });
</script>

<?php include "footer.php"; ?>
