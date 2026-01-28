</main>

<footer id="footer" class="bg-gray-50 pt-10 pb-6 border-t border-gray-200 text-gray-700 text-sm">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12 lg:px-24">
        <div class="grid md:grid-cols-3 gap-8 mb-8">
            <!-- Location Column -->
            <div class="text-left">
              <h2 class="text-base font-semibold border-b-2 border-gray-300 inline-block pb-1 mb-4 text-gray-900">Location</h2>
              <ul class="space-y-3">
                <li class="flex items-start gap-3">
                  <div class="mt-0.5 text-gray-600 flex-shrink-0">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s6-5.686 6-10a6 6 0 10-12 0c0 4.314 6 10 6 10z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a2 2 0 100-4 2 2 0 000 4z" />
                      </svg>
                  </div>
                  <div class="flex flex-col">
                      <span>Great North Road, Kabwe,<br>Central Province, Zambia</span>
                      <span class="mt-1 font-medium text-gray-500">PCC</span>
                  </div>
                </li>
              </ul>
            </div>

            <!-- Contact Column -->
            <div class="text-left">
              <h2 class="text-base font-semibold border-b-2 border-gray-300 inline-block pb-1 mb-4 text-gray-900">Contact</h2>
              <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <div class="mt-0.5 text-gray-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <span>+260 76 759 8798, +260 97 62 80398,<br>+260 977 96 3747</span>
                </li>
                <li class="flex items-center gap-3">
                    <div class="text-gray-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span>Project Manager: moongamunsanje@gmail.com</span>
                </li>
                <li class="flex items-center gap-3">
                    <div class="text-gray-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <span>Lead Developer: givennkonde535@gmail.com</span>
                </li>
              </ul>
            </div>

            <!-- Links Column -->
            <div class="text-left">
              <h2 class="text-base font-semibold border-b-2 border-gray-300 inline-block pb-1 mb-4 text-gray-900">Know more here</h2>
              <a href="/about.php" class="block text-blue-600 hover:text-blue-800 transition-colors">About Us</a>
            </div>
        </div>
        
        <div class="text-center pt-6 border-t border-gray-200 text-gray-500 text-xs">
            <?php echo "&copy; " . date("Y") . " Upschool1. All Rights Reserved."; ?>
        </div>
    </div>
</footer>

<!-- ===================== AUTH MODALS (LOGIN + SIGNUP) ===================== -->

<!-- LOGIN MODAL -->
<div id="loginModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="modal-box bg-white p-6 rounded-lg shadow-xl w-full max-w-md relative">
    <button id="closeLogin" class="modal-close absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl leading-none" type="button">&times;</button>
    <h2 class="text-xl font-bold mb-4 text-center">LOGIN</h2>

    <form action="login.php" method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Username or Email</label>
        <input type="text" name="username" placeholder="e.g. john_doe@email.com"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" placeholder="Enter your password"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" required>
      </div>

      <button type="submit"
        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 shadow-md">
        Login
      </button>

      <p class="text-center text-sm text-gray-600">
        Don't have an account?
        <button type="button" id="switchToSignup" class="text-green-600 font-bold hover:underline">
          Sign Up here
        </button>
      </p>
    </form>
  </div>
</div>

<!-- SIGNUP MODAL -->
<div id="signupModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="modal-box bg-white p-6 rounded-lg shadow-xl w-full max-w-md relative">
    <button id="closeSignup" class="modal-close absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl leading-none" type="button">&times;</button>

    <h2 class="text-xl font-bold mb-1 text-center">Create Account</h2>
    <p class="text-sm text-center text-gray-500 mb-4">Join the Upschool1 community</p>

    <form action="Signup.php" method="POST" class="space-y-4">
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
        <input type="password" id="signupPassword" name="password" placeholder="Min. 8 characters"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" required>

        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
          <div id="strength-bar-modal" class="bg-red-500 h-1.5 rounded-full transition-all duration-500" style="width: 5%"></div>
        </div>
        <p id="password-hint-modal" class="text-xs text-gray-500 mt-1">Strength: Too weak</p>
      </div>

      <button type="submit"
        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transform transition active:scale-95">
        Sign Up
      </button>

      <p class="text-center text-sm text-gray-600">
        Already have an account?
        <button type="button" id="switchToLogin" class="text-green-600 font-bold hover:underline">
          Login here
        </button>
      </p>
    </form>
  </div>
</div>

<script>
    // ===================== NAV MENU TOGGLE =====================
    const toggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.nav-menu');

    if (toggle && menu) {
      toggle.addEventListener('click', () => {
          menu.classList.toggle('active');
      });
    }

    // ===================== SCROLL ANIMATION OBSERVER =====================
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }
        });
    }, { threshold: 0.2 });

    const hiddenElements = document.querySelectorAll('.scroll-content, .scroll-content-right');
    hiddenElements.forEach((el) => observer.observe(el));

    // ===================== AUTH MODALS OPEN/CLOSE =====================
    const loginModal = document.getElementById("loginModal");
    const signupModal = document.getElementById("signupModal");

    const openLoginBtn = document.getElementById("openLogin"); // Ensure you have a button with this ID in header.php
    const openSignupBtn = document.getElementById("openSignup"); // Ensure you have a button with this ID in header.php

    const closeLoginBtn = document.getElementById("closeLogin");
    const closeSignupBtn = document.getElementById("closeSignup");

    if (openLoginBtn && loginModal) {
      openLoginBtn.addEventListener("click", () => loginModal.classList.remove("hidden"));
    }

    if (openSignupBtn && signupModal) {
      openSignupBtn.addEventListener("click", () => signupModal.classList.remove("hidden"));
    }

    if (closeLoginBtn && loginModal) {
      closeLoginBtn.addEventListener("click", () => loginModal.classList.add("hidden"));
    }

    if (closeSignupBtn && signupModal) {
      closeSignupBtn.addEventListener("click", () => signupModal.classList.add("hidden"));
    }

    // Close modal when clicking outside the modal box
    window.addEventListener("click", (e) => {
      if (loginModal && !loginModal.classList.contains("hidden") && e.target === loginModal) {
          loginModal.classList.add("hidden");
      }
      if (signupModal && !signupModal.classList.contains("hidden") && e.target === signupModal) {
          signupModal.classList.add("hidden");
      }
    });

    // ===================== SWITCH MODALS =====================
    const switchToSignup = document.getElementById("switchToSignup");
    const switchToLogin = document.getElementById("switchToLogin");

    // Login -> Signup
    if (switchToSignup && loginModal && signupModal) {
      switchToSignup.addEventListener("click", () => {
        loginModal.classList.add("hidden");
        signupModal.classList.remove("hidden");
      });
    }

    // Signup -> Login
    if (switchToLogin && loginModal && signupModal) {
      switchToLogin.addEventListener("click", () => {
        signupModal.classList.add("hidden");
        loginModal.classList.remove("hidden");
      });
    }

    // ===================== PASSWORD STRENGTH (MODAL ONLY) =====================
    const passwordInputModal = document.getElementById('signupPassword');
    const strengthBarModal = document.getElementById('strength-bar-modal');
    const hintModal = document.getElementById('password-hint-modal');

    if (passwordInputModal && strengthBarModal && hintModal) {
      passwordInputModal.addEventListener('input', () => {
          const val = passwordInputModal.value;

          if (val.length === 0) {
              strengthBarModal.style.width = '5%';
              strengthBarModal.className = 'bg-red-500 h-1.5 rounded-full transition-all duration-500';
              hintModal.innerText = 'Strength: Too weak';
          } else if (val.length < 6) {
              strengthBarModal.style.width = '30%';
              strengthBarModal.className = 'bg-orange-500 h-1.5 rounded-full transition-all duration-500';
              hintModal.innerText = 'Strength: Weak';
          } else if (val.length < 10) {
              strengthBarModal.style.width = '60%';
              strengthBarModal.className = 'bg-yellow-500 h-1.5 rounded-full transition-all duration-500';
              hintModal.innerText = 'Strength: Good';
          } else {
              strengthBarModal.style.width = '100%';
              strengthBarModal.className = 'bg-green-500 h-1.5 rounded-full transition-all duration-500';
              hintModal.innerText = 'Strength: Strong!';
          }
      });
    }
</script>
</body>
</html>
