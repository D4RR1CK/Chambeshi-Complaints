<?php
// Start session so we can check login status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Optional: active page helper
$current = basename($_SERVER["PHP_SELF"]);
function navClass($file, $current) {
    $active = ($file === $current);
    return $active
        ? "bg-white/20 text-white"
        : "text-white/90 hover:bg-white/15 hover:text-white";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Ensures perfect scaling on mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <?php
        if(isset($pageTitle)) echo "<title>" . htmlspecialchars($pageTitle) . " | Upschool1</title>";
        else echo "<title>Upschool1</title>";
    ?>

    <!-- Your CSS -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome (for icons used below) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<!-- overflow-x-hidden stops side scrolling on phones -->
<body class="min-h-[100svh] overflow-x-hidden">

    <!-- Intro overlay -->
    <div id="intro-overlay">
        <div class="intro-text">
            A Place To Vent Your Hostel Issues.
        </div>
    </div>

    <!-- ===================== NAVBAR ===================== -->
    <header class="sticky top-0 z-50">
        <div class="bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between">

                    <!-- Logo -->
                    <a href="index.php" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center shadow-md ring-1 ring-white/20">
                            <span class="text-white font-black text-lg">U</span>
                        </div>
                        <div class="leading-tight">
                            <div class="text-white font-extrabold text-lg tracking-wide group-hover:opacity-95">
                                Upschool1
                            </div>
                            <div class="text-white/80 text-xs hidden sm:block">
                                Hostel complaints made easy
                            </div>
                        </div>
                    </a>

                    <!-- Mobile menu button -->
                    <button id="menuBtn"
                            class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/15 hover:bg-white/20 text-white ring-1 ring-white/20 transition"
                            aria-label="Toggle menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <!-- Desktop nav -->
                    <nav class="hidden md:flex items-center gap-2">

                        <a href="index.php"
                           class="px-4 py-2 rounded-xl text-sm font-semibold transition <?php echo navClass('index.php', $current); ?>">
                            Home
                        </a>

                        <?php if(isset($_SESSION["user_id"])): ?>
                            <a href="reporting.php"
                               class="px-4 py-2 rounded-xl text-sm font-semibold transition <?php echo navClass('reporting.php', $current); ?>">
                                Reporting Issues
                            </a>

                            <a href="tracking.php"
                               class="px-4 py-2 rounded-xl text-sm font-semibold transition <?php echo navClass('tracking.php', $current); ?>">
                                Issue Tracking
                            </a>

                            <a href="my_issues.php"
                               class="px-4 py-2 rounded-xl text-sm font-semibold transition <?php echo navClass('my_issues.php', $current); ?>">
                                My Issues
                            </a>
                        <?php else: ?>
                            <!-- LOCKED buttons (no redirect) -->
                            <button type="button" data-locked="1"
                                    class="px-4 py-2 rounded-xl text-sm font-semibold transition text-white/90 hover:bg-white/15 hover:text-white inline-flex items-center gap-2">
                                <i class="fa-solid fa-lock text-xs"></i>
                                Reporting Issues
                            </button>

                            <button type="button" data-locked="1"
                                    class="px-4 py-2 rounded-xl text-sm font-semibold transition text-white/90 hover:bg-white/15 hover:text-white inline-flex items-center gap-2">
                                <i class="fa-solid fa-lock text-xs"></i>
                                Issue Tracking
                            </button>
                        <?php endif; ?>

                        <a href="about.php"
                           class="px-4 py-2 rounded-xl text-sm font-semibold transition <?php echo navClass('about.php', $current); ?>">
                            About
                        </a>

                        <a href="#footer"
                           class="px-4 py-2 rounded-xl text-sm font-semibold transition text-white/90 hover:bg-white/15 hover:text-white">
                            Contact
                        </a>
                    </nav>

                    <!-- Auth area -->
                    <div class="hidden md:flex items-center gap-3">
                        <?php if(isset($_SESSION["user_id"])): ?>
                            <div class="hidden lg:flex items-center gap-2 px-3 py-2 rounded-xl bg-white/15 ring-1 ring-white/20">
                                <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="leading-tight">
                                    <div class="text-white text-sm font-bold">
                                        <?php echo htmlspecialchars($_SESSION["fullname"]); ?>
                                    </div>
                                    <div class="text-white/80 text-xs">Logged in</div>
                                </div>
                            </div>

                            <a href="logout.php"
                               class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-sm transition shadow-md">
                                Logout
                            </a>
                        <?php else: ?>
                            <!-- LOGIN OPENS MODAL (no redirect) -->
                            <button type="button" id="openLoginModal"
                               class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/20 text-white font-bold text-sm transition ring-1 ring-white/20">
                                Login
                            </button>

                            <a href="Signup.php"
                               class="px-4 py-2 rounded-xl bg-white text-emerald-700 hover:bg-emerald-50 font-extrabold text-sm transition shadow-md">
                                Sign Up
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Mobile dropdown -->
        <div id="mobileMenu" class="md:hidden hidden bg-white border-b border-slate-200 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-3 space-y-2">
                <a href="index.php" class="block px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold">Home</a>

                <?php if(isset($_SESSION["user_id"])): ?>
                    <a href="reporting.php" class="block px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold">Reporting Issues</a>
                    <a href="tracking.php" class="block px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold">Issue Tracking</a>
                    <a href="my_issues.php" class="block px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold">My Issues</a>
                <?php else: ?>
                    <button type="button" data-locked="1" class="w-full text-left px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold inline-flex items-center gap-2">
                        <i class="fa-solid fa-lock text-xs"></i> Reporting Issues
                    </button>
                    <button type="button" data-locked="1" class="w-full text-left px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold inline-flex items-center gap-2">
                        <i class="fa-solid fa-lock text-xs"></i> Issue Tracking
                    </button>
                <?php endif; ?>

                <a href="about.php" class="block px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold">About</a>
                <a href="#footer" class="block px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold">Contact</a>

                <div class="pt-2 flex gap-2">
                    <?php if(isset($_SESSION["user_id"])): ?>
                        <a href="logout.php" class="flex-1 px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-center">Logout</a>
                    <?php else: ?>
                        <button type="button" id="openLoginModalMobile" class="flex-1 px-4 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-center">
                            Login
                        </button>
                        <a href="Signup.php" class="flex-1 px-4 py-3 rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold text-center border border-emerald-200">
                            Sign Up
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- ===================== LOGIN REQUIRED MODAL ===================== -->
    <div id="loginRequiredModal" class="fixed inset-0 z-[999] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div class="relative min-h-[100svh] flex items-center justify-center p-4">
            <div class="w-full max-w-md sm:max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">

                <div class="p-6 bg-gradient-to-r from-emerald-600 to-teal-600 text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center">
                                <i class="fa-solid fa-shield-halved text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold">Login required</h3>
                                <p class="text-white/90 text-sm">Please login or create an account to continue.</p>
                            </div>
                        </div>

                        <button type="button" id="closeRequiredX" class="text-white/80 hover:text-white text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-5">
                        <p class="font-semibold text-emerald-800 mb-1">You’re almost there</p>
                        <p class="text-sm text-emerald-700">
                            Login to submit issues and track progress, or sign up if you don’t have an account yet.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" id="openLoginFromRequired"
                           class="flex-1 px-4 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-center transition shadow-md">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i> Login
                        </button>

                        <a href="Signup.php"
                           class="flex-1 px-4 py-3 rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold text-center transition shadow-sm border border-emerald-200">
                            <i class="fa-solid fa-user-plus mr-2"></i> Sign up
                        </a>
                    </div>

                    <button type="button" id="closeRequiredBtn"
                            class="mt-4 w-full px-4 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition">
                        Not now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== LOGIN MODAL ===================== -->
    <div id="loginModal" class="fixed inset-0 z-[1000] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div class="relative min-h-[100svh] flex items-center justify-center p-4">
            <div class="w-full max-w-md sm:max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">

                <div class="p-6 bg-gradient-to-r from-emerald-700 to-teal-600 text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center">
                                <i class="fa-solid fa-right-to-bracket text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold">Welcome back</h3>
                                <p class="text-white/90 text-sm">Login to submit and track hostel issues.</p>
                            </div>
                        </div>

                        <button type="button" id="closeLoginX" class="text-white/80 hover:text-white text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    <form action="login.php" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="username" required
                                   class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                   placeholder="john@example.com">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Password</label>
                            <input type="password" name="password" required
                                   class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                   placeholder="Enter your password">
                        </div>

                        <button type="submit"
                                class="w-full px-4 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-md">
                            Login
                        </button>

                        <p class="text-center text-sm text-slate-600">
                            Don’t have an account?
                            <a href="Signup.php" class="font-bold text-emerald-700 hover:underline">Sign up</a>
                        </p>
                    </form>

                    <button type="button" id="closeLoginBtn"
                            class="mt-4 w-full px-4 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>

    <main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Intro overlay only once per session
            if (sessionStorage.getItem('hasVisited')) {
                const overlay = document.getElementById('intro-overlay');
                if (overlay) overlay.style.display = 'none';
            } else {
                sessionStorage.setItem('hasVisited', 'true');
            }

            // Mobile menu toggle
            const menuBtn = document.getElementById("menuBtn");
            const mobileMenu = document.getElementById("mobileMenu");
            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener("click", () => {
                    mobileMenu.classList.toggle("hidden");
                });
            }

            // Modal elements
            const reqModal = document.getElementById("loginRequiredModal");
            const loginModal = document.getElementById("loginModal");

            function openModal(el) {
                if (!el) return;
                el.classList.remove("hidden");
                document.body.style.overflow = "hidden";
            }

            function closeModal(el) {
                if (!el) return;
                el.classList.add("hidden");
                document.body.style.overflow = "";
            }

            // Locked buttons -> open required modal
            document.querySelectorAll("[data-locked='1']").forEach(btn => {
                btn.addEventListener("click", (e) => {
                    e.preventDefault();
                    openModal(reqModal);
                });
            });

            // Required modal close
            document.getElementById("closeRequiredBtn")?.addEventListener("click", () => closeModal(reqModal));
            document.getElementById("closeRequiredX")?.addEventListener("click", () => closeModal(reqModal));

            // Open login modal from header buttons
            document.getElementById("openLoginModal")?.addEventListener("click", () => openModal(loginModal));
            document.getElementById("openLoginModalMobile")?.addEventListener("click", () => openModal(loginModal));

            // Open login modal from required modal (no redirect)
            document.getElementById("openLoginFromRequired")?.addEventListener("click", () => {
                closeModal(reqModal);
                openModal(loginModal);
            });

            // Login modal close
            document.getElementById("closeLoginBtn")?.addEventListener("click", () => closeModal(loginModal));
            document.getElementById("closeLoginX")?.addEventListener("click", () => closeModal(loginModal));

            // Backdrop click closes (only when clicking the outer modal, not the box)
            [reqModal, loginModal].forEach(m => {
                if (!m) return;
                m.addEventListener("click", (e) => {
                    if (e.target === m) closeModal(m);
                });
            });

            // ESC closes all
            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape") {
                    closeModal(reqModal);
                    closeModal(loginModal);
                }
            });
        });
    </script>
