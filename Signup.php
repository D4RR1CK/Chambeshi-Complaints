<?php
$pageTitle = "Create Account";
require_once "db.php";
require_once "mail.php"; // REAL email sending

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";
$success = "";

// Handle Signup (PROCESS FIRST - no HTML output above this line)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    // Basic validation
    if ($fullname === "" || $email === "" || $password === "" || $confirm === "") {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();
        $exists = $res->fetch_assoc();
        $check->close();

        if ($exists) {
            $error = "An account with this email already exists.";
        } else {
            // Create user (not verified yet)
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password_hash, is_verified) VALUES (?, ?, ?, 0)");
            if (!$stmt) {
                $error = "Something went wrong. Please try again.";
            } else {
                $stmt->bind_param("sss", $fullname, $email, $passwordHash);

                if ($stmt->execute()) {
                    $stmt->close();

                    // Generate verification code
                    $code = (string)random_int(100000, 999999);
                    $codeHash = password_hash($code, PASSWORD_DEFAULT);
                    $expiresAt = date("Y-m-d H:i:s", time() + 10 * 60); // 10 minutes

                    // Store hash + expiry
                    $upd = $conn->prepare("UPDATE users SET verification_code_hash=?, verification_expires_at=? WHERE email=?");
                    $upd->bind_param("sss", $codeHash, $expiresAt, $email);
                    $upd->execute();
                    $upd->close();

                    // Send code via email
                    $sent = sendVerificationCode($email, $fullname, $code);

                    $_SESSION["verify_info"] = $sent
                        ? "We sent a verification code to your email."
                        : "Account created, but we couldn't send the email. Please click Resend Code.";

                    // Redirect to verification page
                    header("Location: verify.php?email=" . urlencode($email));
                    exit;
                } else {
                    $error = "Could not create account. Please try again.";
                    $stmt->close();
                }
            }
        }
    }
}

// NOW we can safely include header (HTML starts here)
include "header.php";
?>

<div class="relative min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-8 sm:py-12 bg-slate-50 overflow-x-hidden">
    <!-- soft background glow (won't cause horizontal scroll) -->
    <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-br from-emerald-50 via-white to-teal-50"></div>

    <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <!-- Header -->
        <div class="p-5 sm:p-6 bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-600 text-white">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-user-plus text-lg sm:text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold leading-tight">Create Account</h2>
                    <p class="text-white/90 text-xs sm:text-sm">Join Upschool1 and start reporting issues.</p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6 md:p-8">
            <!-- Messages -->
            <?php if ($error): ?>
                <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <div><?php echo htmlspecialchars($error); ?></div>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold flex items-start gap-3">
                    <i class="fa-solid fa-circle-check mt-0.5"></i>
                    <div><?php echo htmlspecialchars($success); ?></div>
                </div>
            <?php endif; ?>

            <form action="Signup.php" method="POST" class="space-y-4">
                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" name="fullname" placeholder="John Doe"
                               value="<?php echo htmlspecialchars($_POST["fullname"] ?? ""); ?>"
                               class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                               required>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" placeholder="john@example.com"
                               value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>"
                               class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                               required>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" placeholder="Min. 8 characters"
                               class="w-full pl-10 pr-12 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                               required>
                        <button type="button" id="togglePwd"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700 text-sm font-semibold">
                            Show
                        </button>
                    </div>

                    <!-- Strength bar -->
                    <div class="mt-3">
                        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                            <div id="strength-bar"
                                 class="h-2 rounded-full transition-all duration-300"
                                 style="width: 5%; background: #ef4444;"></div>
                        </div>
                        <p id="password-hint" class="text-xs text-slate-600 mt-2">Strength: Too weak</p>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Confirm Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-shield-halved"></i>
                        </span>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat your password"
                               class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                               required>
                    </div>
                    <p id="match-message" class="text-xs mt-2 hidden"></p>
                </div>

                <button type="submit"
                        class="w-full px-4 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold transition shadow-md">
                    Sign Up
                </button>

                <p class="text-center text-sm text-slate-600 pt-1">
                    Already have an account?
                    <button type="button" id="openLoginFromSignup" class="font-bold text-emerald-700 hover:underline">
                        Login here
                    </button>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const strengthBar = document.getElementById('strength-bar');
    const hint = document.getElementById('password-hint');
    const matchMessage = document.getElementById('match-message');
    const togglePwd = document.getElementById('togglePwd');

    function setStrength(pct, color, text) {
        strengthBar.style.width = pct + '%';
        strengthBar.style.background = color;
        hint.innerText = 'Strength: ' + text;
    }

    function checkMatch() {
        if (confirmInput.value.length > 0) {
            matchMessage.classList.remove('hidden');
            if (passwordInput.value === confirmInput.value) {
                matchMessage.innerText = 'Passwords match';
                matchMessage.className = 'text-xs mt-2 text-emerald-700 font-semibold';
            } else {
                matchMessage.innerText = 'Passwords do not match';
                matchMessage.className = 'text-xs mt-2 text-red-600 font-semibold';
            }
        } else {
            matchMessage.classList.add('hidden');
        }
    }

    passwordInput.addEventListener('input', () => {
        const val = passwordInput.value;
        if (val.length === 0) setStrength(5, '#ef4444', 'Too weak');
        else if (val.length < 6) setStrength(30, '#f97316', 'Weak');
        else if (val.length < 10) setStrength(60, '#eab308', 'Good');
        else setStrength(100, '#22c55e', 'Strong!');
        checkMatch();
    });

    confirmInput.addEventListener('input', checkMatch);

    togglePwd?.addEventListener('click', () => {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        togglePwd.textContent = isHidden ? 'Hide' : 'Show';
    });

    // If you already have login modal in header.php, this can open it
    document.getElementById('openLoginFromSignup')?.addEventListener('click', () => {
        // Try to open the login modal if it exists
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
            loginModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            // fallback
            window.location.href = 'login.php';
        }
    });
</script>

<?php include "footer.php"; ?>
