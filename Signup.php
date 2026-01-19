<?php
$pageTitle = "Create Account";
include "header.php"; 
?>

<div class="flex items-center justify-center min-h-screen bg-gray-100 px-4 py-12">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-lg">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Create Account</h2>
        <p class="text-center text-gray-500 mb-6">Join the Upschool1 community</p>
        
        <form action="#" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="fullname" placeholder="John Doe" 
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" placeholder="john@example.com" 
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="Min. 8 characters" 
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" required>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                    <div id="strength-bar" class="bg-red-500 h-1.5 rounded-full transition-all duration-500" style="width: 5%"></div>
                </div>
                <p id="password-hint" class="text-xs text-gray-500 mt-1">Strength: Too weak</p>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transform transition active:scale-95">
                Sign Up
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Already have an account? <a href="login.php" class="text-green-600 font-bold hover:underline">Login here</a>
        </p>
    </div>
</div>

<script>
    // Real-time Password Strength logic
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strength-bar');
    const hint = document.getElementById('password-hint');

    passwordInput.addEventListener('input', () => {
        const val = passwordInput.value;
        if (val.length === 0) {
            strengthBar.style.width = '5%';
            strengthBar.className = 'bg-red-500 h-1.5 rounded-full';
            hint.innerText = 'Strength: Too weak';
        } else if (val.length < 6) {
            strengthBar.style.width = '30%';
            strengthBar.className = 'bg-orange-500 h-1.5 rounded-full';
            hint.innerText = 'Strength: Weak';
        } else if (val.length < 10) {
            strengthBar.style.width = '60%';
            strengthBar.className = 'bg-yellow-500 h-1.5 rounded-full';
            hint.innerText = 'Strength: Good';
        } else {
            strengthBar.style.width = '100%';
            strengthBar.className = 'bg-green-500 h-1.5 rounded-full';
            hint.innerText = 'Strength: Strong!';
        }
    });
</script>