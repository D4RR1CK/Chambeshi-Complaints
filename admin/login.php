<?php
session_start();
require_once "../db.php";
$error = "";
$success = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    if ($email === "" || $password === "") {
        $error = "Please enter email and password.";
    } else {
        $sql = "SELECT id, name, email, password_hash FROM admins WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $admin = $res->fetch_assoc();
        $stmt->close();
        if (!$admin) {
            $error = "Admin account not found.";
        } elseif (!password_verify($password, $admin["password_hash"])) {
            $error = "Incorrect password.";
        } else {
            //  login success
            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_name"] = $admin["name"];
            $_SESSION["admin_email"] = $admin["email"];
            $success = "Login successful! Redirecting...";
            header("refresh:1;url=dashboard.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hostel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 px-4 py-8">
    <div class="w-full max-w-md">
        <!-- Logo/Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 shadow-lg mb-4">
                <i class="fas fa-user-shield text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                Admin Login
            </h1>
            <p class="text-slate-600 mt-2">Hostel Complaint Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-2xl border border-white">
            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-semibold text-red-800 text-sm">Error</p>
                        <p class="text-red-700 text-sm mt-1"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-semibold text-green-800 text-sm">Success</p>
                        <p class="text-green-700 text-sm mt-1"><?php echo htmlspecialchars($success); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-envelope text-slate-400 mr-2"></i>Email Address
                    </label>
                    <div class="relative">
                        <input type="email" name="email" required
                               class="w-full px-4 py-3 pl-11 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white"
                               placeholder="admin@upschool1.com">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-lock text-slate-400 mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" required id="password"
                               class="w-full px-4 py-3 pl-11 pr-11 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white"
                               placeholder="••••••••">
                        <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>

            <!-- Security Notice -->
            <div class="mt-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="flex items-start gap-3">
                    <i class="fas fa-shield-alt text-blue-600 mt-0.5"></i>
                    <div>
                        <p class="text-xs font-semibold text-slate-700">Security Notice</p>
                        <p class="text-xs text-slate-600 mt-1">
                            Keep your admin credentials private and secure. Never share your login details.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-slate-600">
                <i class="fas fa-lock text-slate-400"></i>
                Secured Admin Access
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>