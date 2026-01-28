<?php
  $pageTitle = "About Us";
  
  include "header.php";
?>

<!-- Hero Section with Overlay -->
<section class="pt-10">
  <div class="relative flex flex-col items-center justify-center min-h-[100px]" style="background-image: url('download (9).jpg');">
    
    <!-- Content -->
    <div class="relative z-10 text-center px-4">
      <h1 class="text-4xl md:text-4xl font-bold text-white mb-4">About The Company</h1>
      <p class="text-white text-lg md:text-lg max-w-lg mx-auto">This page seeks to explain more on what we do.</p>
    </div>
  </div>
</section>

<!-- Main Content -->
<div class="bg-gray-50 py-16">
  <div class="max-w-6xl mx-auto px-4 space-y-12">
    
    <!-- Who Are We Section -->
    <section>
      <div class="bg-white border border-gray-200 rounded-3xl p-8 md:p-12 shadow-lg hover:shadow-xl transition-shadow duration-300">
        <h2 class="text-4xl font-bold text-center mb-6 text-gray-800">Who Are We?</h2>
        <div class="space-y-4 text-gray-700 leading-relaxed">
          <p>bootCamp Tech is a small yet highly motivated and skilled group of developers composed entirely of university students, all of whom are pursuing Bachelor's degrees in technology-related programs. The group was founded in December 2025 with a clear and unified purpose: to continuously improve the technical abilities, practical experience, and theoretical understanding of every member involved in the field of software engineering and systems development.</p>
          <p>The team was created to bridge the gap between classroom theory and real-world application. Through teamwork, research, and hands-on project development, BootCamp Tech seeks to cultivate strong problem-solving skills, professional discipline, and innovation among its members. Each project undertaken is viewed not only as a service to clients, but also as a learning platform that strengthens collaboration, leadership, and accountability.</p>
          <p>The Chambeshi Complaints System is one of bootCamp Tech's active projects and is currently under development by a team of five (5) developers. The project team is structured to reflect a real-world software development environment: one member is assigned the role of Project Manager, responsible for coordination and oversight; another serves as the Lead Developer, tasked with guiding technical decisions and system architecture; while the remaining three developers contribute to coding, testing, documentation, and system improvement. This structure ensures efficiency, quality control, and professional standards throughout the development process.</p>
        </div>
      </div>
    </section>

    <!-- What Is This System Section -->
    <section>
      <div class="bg-white border border-gray-200 rounded-3xl p-8 md:p-12 shadow-lg hover:shadow-xl transition-shadow duration-300">
        <h2 class="text-4xl font-bold text-center mb-6 text-gray-800">What Is This System?</h2>
        <div class="space-y-4 text-gray-700 leading-relaxed">
          <p>The Chambeshi Complaints System is a digital and automated complaint management platform designed specifically to improve the way hostel-related issues are reported, communicated, and resolved within Chambeshi Hostel. The system replaces informal, manual, and time-consuming complaint reporting methods with a structured and transparent digital process.</p>
          <p>During their tenure as hostel representatives for the 2025/2026 academic year, Chambeshi 1 Representative Jabulani and Chambeshi Top Representative Derrick identified recurring challenges in handling complaints. These included delayed reporting, lack of proper documentation, missed follow-ups, and difficulty in receiving complaints when representatives were not physically present in the hostel. To address these challenges, they proposed an automated solution that would allow residents to submit complaints anytime and from anywhere.</p>
          <p>To bring this vision to life, the representatives engaged the services of bootCamp Tech to design and develop a professional system capable of receiving, organizing, and forwarding complaints efficiently. The system ensures that all complaints are formally recorded, categorized by type, and routed through the correct administrative channels. This improves response time, enhances accountability among responsible offices, and promotes better communication between students and hostel management. Ultimately, the system aims to create a more organized, responsive, and student-friendly hostel environment.</p>
        </div>
      </div>
    </section>

    <!-- What To Know Before Complaining Section -->
    <section>
      <div class="bg-white border border-gray-200 rounded-3xl p-8 md:p-12 shadow-lg hover:shadow-xl transition-shadow duration-300">
        <h2 class="text-4xl font-bold text-center mb-6 text-gray-800">What To Know Before Complaining</h2>
        <p class="text-gray-700 leading-relaxed mb-8 text-center max-w-4xl mx-auto">To ensure fairness, efficiency, and responsible use of the system, all residents are expected to understand and observe the following important guidelines before submitting a complaint:</p>
        
        <ul class="space-y-6">
          <li class="bg-gray-50 p-6 rounded-xl">
            <strong class="text-xl text-gray-800 block mb-2">1. Wear and Tear-Related Faults</strong>
            <p class="text-gray-700 leading-relaxed">Any faults or failures resulting from normal wear and tear must be reported on the same day the fault occurs or immediately once the item fails to function as intended. Early reporting allows maintenance teams to address issues promptly and prevents further damage or complications.</p>
          </li>
          
          <li class="bg-gray-50 p-6 rounded-xl">
            <strong class="text-xl text-gray-800 block mb-2">2. Self-Induced or Negligent Damage</strong>
            <p class="text-gray-700 leading-relaxed">Issues that arise as a result of self-destruction, misuse, negligence, or careless handling of hostel property must be reported with utmost urgency. Prompt reporting helps hostel authorities assess responsibility accurately and may prevent additional charges or future billing complications associated with delayed reporting.</p>
          </li>
          
          <li class="bg-gray-50 p-6 rounded-xl">
            <strong class="text-xl text-gray-800 block mb-2">3. Recently Replaced Items</strong>
            <p class="text-gray-700 leading-relaxed">Items that were replaced at the beginning of the semester or during the course of the semester and later damaged or destroyed cannot be replaced again until one full academic year has passed, regardless of circumstances. Residents are therefore advised to take extra care of all newly installed or replaced items.</p>
          </li>
        </ul>
        
        <div class="mt-8 p-4">
          <p class="text-gray-800 font-semibold text-center">⚠️ Residents must carefully consider these three key highlights before submitting a complaint. Failure to adhere to these guidelines may result in delayed resolution or rejection of the complaint.</p>
        </div>
      </div>
    </section>

    <!-- What to Know After You Complain Section -->
    <section>
      <div class="bg-white border border-gray-200 rounded-3xl p-8 md:p-12 shadow-lg hover:shadow-xl transition-shadow duration-300">
        <h2 class="text-4xl font-bold text-center mb-6 text-gray-800">What to Know After You Complain</h2>
        
        <div class="space-y-4 text-gray-700 leading-relaxed">
          <p>After a complaint has been successfully submitted through the Chambeshi Complaints System, it is formally recorded within the platform and assigned a reference for tracking purposes. This ensures that every complaint is properly documented and handled through the correct administrative channels. Residents are advised to avoid submitting duplicate complaints for the same issue, as this may lead to confusion and unnecessary delays in processing.</p>
          
          <div>
            <h3 class="font-bold text-lg text-gray-800 mb-2">📋 Processing Flow</h3>
            <p>Once submitted, the complaint is first reviewed by the Hostel Representative, who verifies the information provided and ensures that the complaint meets the reporting guidelines. If approved, the complaint is forwarded through the official communication channel, beginning with the Local Government Minister's Office, followed by the Hall Attendant, and then to the Resident Engineer.</p>
          </div>
          
          <p>The Resident Engineer evaluates the technical nature of the issue and assigns the task to the appropriate maintenance personnel—such as a Plumber, Electrician, or Carpenter—depending on the type of fault reported. The time required to resolve an issue may vary based on its complexity, availability of materials, and the workload of the assigned personnel. Residents are therefore encouraged to remain patient during this process.</p>
          
          <div>
            <h3 class="font-bold text-lg text-gray-800 mb-2">🔍 Real-Time Tracking</h3>
            <p>Throughout this process, residents can visit the <a href="/tracking.php" class="text-blue-600 hover:text-blue-800 transition-colors">Issue Tracking </a> to view the current status of their submitted complaint in real time. The tracking system clearly displays the complaint's lifecycle, showing each stage it has passed through—from submission and verification, to assignment, repair, and final resolution. This transparency allows residents to see how far their issue has progressed and which office or personnel is currently handling it, reducing the need for physical follow-ups.</p>
          </div>
          
          <p>Once the repair or corrective action has been completed, the complaint is marked as resolved or closed within the system. Residents are encouraged to confirm that the issue has been adequately addressed and to report any recurring or unresolved problems through the system rather than informal channels.</p>
          
          <div>
            <p class="text-gray-800 font-semibold text-center">✅ By following the proper procedures and making use of the tracking features provided, residents help ensure that the complaint handling process remains efficient, transparent, and beneficial to the benevolent gentlemen of Chambeshi Hotel.</p>
          </div>
        </div>
      </div>
    </section>

  </div>
</div>

<?php 
  include "footer.php";
?>