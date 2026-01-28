<?php
session_start();

$pageTitle = "Chambeshi Complaints";
include "header.php";

$isLoggedIn = isset($_SESSION["user_id"]);
?>

<!-- Hero -->
<section class="hero-section rounded-b-[50px] overflow-hidden bg-cover bg-center shadow-lg mb-0"
         style="background-image: url('Programming and developers vector seamless pattern_ Internet and coding black linear print with computer languages.jpg');">
  <div class="hero-content text-black text-5xl font-bold p-4 bg-white/80 rounded-xl backdrop-blur-sm">
    A product of bootcamp Tech
  </div>
</section>

<section class="py-12">
  <h1 class="text-5xl font-bold text-center text-gray-800">
    Welcome to Chambeshi Complaints
  </h1>
</section>

<section class="grid grid-rows-3 gap-16 px-8">

  <!-- BLOCK 1 -->
  <div class="scroll-block">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-8 items-center">
      <div class="scroll-content">
        <h3 class="text-2xl font-semibold text-gray-800 border-l-4 border-green-600 pl-4">
          A site mainly focused on submitting hostel issues
        </h3>
      </div>

      <div class="text-center">
        <?php if ($isLoggedIn): ?>
          <a href="reporting.php"
             class="inline-flex items-center justify-center text-xl px-6 py-3 font-bold text-emerald-700 bg-emerald-100 rounded-full hover:bg-emerald-200 transition shadow-md hover:shadow-lg">
            <i class="fas fa-bullhorn mr-2"></i>
            Report Issues
          </a>
        <?php else: ?>
          <button type="button"
                  data-locked="1"
                  class="inline-flex items-center justify-center text-xl px-6 py-3 font-bold text-gray-600 bg-gray-200 rounded-full hover:bg-gray-300 transition shadow-md hover:shadow-lg">
            <i class="fas fa-lock mr-2"></i>
            Report Issues
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- BLOCK 2 -->
  <div class="scroll-block align-right bg-gray-100 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-8 items-center">
      <div class="text-center">
        <a href="about.php"
           class="inline-flex items-center justify-center text-xl px-6 py-3 font-bold text-emerald-700 bg-emerald-100 rounded-full hover:bg-emerald-200 transition shadow-md hover:shadow-lg">
          <i class="fas fa-diagram-project mr-2"></i>
          About Our Workflow
        </a>
      </div>
      <div class="scroll-content-right">
        <h3 class="text-2xl font-semibold text-gray-800 border-r-4 border-green-600 pr-4">
          We ensure your complaints are heard and resolved efficiently.
        </h3>
      </div>
    </div>
  </div>

  <!-- BLOCK 3 -->
  <div class="scroll-block py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-8 items-center">
      <div class="scroll-content">
        <h3 class="text-2xl font-semibold text-gray-800 border-l-4 border-green-600 pl-4">
          Track your issue status in real-time from submission to resolution.
        </h3>
      </div>

      <div class="text-center">
        <?php if ($isLoggedIn): ?>
          <a href="tracking.php"
             class="inline-flex items-center justify-center text-xl px-6 py-3 font-bold text-emerald-700 bg-emerald-100 rounded-full hover:bg-emerald-200 transition shadow-md hover:shadow-lg">
            <i class="fas fa-route mr-2"></i>
            See Report Progress
          </a>
        <?php else: ?>
          <button type="button"
                  data-locked="1"
                  class="inline-flex items-center justify-center text-xl px-6 py-3 font-bold text-gray-600 bg-gray-200 rounded-full hover:bg-gray-300 transition shadow-md hover:shadow-lg">
            <i class="fas fa-lock mr-2"></i>
            See Report Progress
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>

</section>

<script>
  // Use the modals that already exist in header.php:
  //  - #loginRequiredModal (the "Login required" modal)
  //  - #loginModal (the actual login form modal)

  document.addEventListener("DOMContentLoaded", () => {
    const reqModal = document.getElementById("loginRequiredModal");
    const loginModal = document.getElementById("loginModal");

    const openModal = (el) => {
      if (!el) return;
      el.classList.remove("hidden");
      document.body.style.overflow = "hidden";
    };

    const closeModal = (el) => {
      if (!el) return;
      el.classList.add("hidden");
      document.body.style.overflow = "";
    };

    // ✅ Any locked buttons on this page open the required modal
    document.querySelectorAll("[data-locked='1']").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        openModal(reqModal);
      });
    });

    // ✅ Hook the "Login" button inside the required modal to open the login form modal
    // (Your header.php already has #openLoginFromRequired, we just ensure it works here too.)
    const openLoginFromRequired = document.getElementById("openLoginFromRequired");
    if (openLoginFromRequired) {
      openLoginFromRequired.addEventListener("click", (e) => {
        e.preventDefault();
        closeModal(reqModal);
        openModal(loginModal);
      });
    }

    // If you ever need to open login directly from this page:
    // openModal(loginModal);
  });
</script>

<?php include "footer.php"; ?>
