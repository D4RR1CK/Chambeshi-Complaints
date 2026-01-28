<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Use - Corelink Consulting Ltd.</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0066cc;
            --secondary-color: #004999;
            --text-dark: #333333;
            --text-light: #666666;
            --border-color: #e0e0e0;
            --bg-light: #f8f9fa;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            line-height: 1.7;
        }
        .navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s;
            font-size: 0.9rem;
        }
        .nav-link:hover { color: var(--primary-color) !important; }
        .terms-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 0 3rem;
            margin-bottom: 3rem;
        }
        .terms-hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .terms-hero p { font-size: 1.1rem; opacity: 0.95; }
        .toc-section {
            background: var(--bg-light);
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 3rem;
            position: sticky;
            top: 20px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .toc-section h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }
        .toc-list { list-style: none; padding: 0; }
        .toc-list li { margin-bottom: 0.8rem; }
        .toc-list a {
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s;
            display: block;
            padding: 0.5rem;
            border-radius: 5px;
            font-size: 0.85rem;
        }
        .toc-list a:hover, .toc-list a.active {
            background: white;
            color: var(--primary-color);
            padding-left: 1rem;
        }
        .terms-content { padding: 0 0 4rem; }
        .terms-section {
            margin-bottom: 3rem;
            scroll-margin-top: 100px;
        }
        .terms-section h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid var(--primary-color);
        }
        .terms-section h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .terms-section p { margin-bottom: 1rem; color: var(--text-light); }
        .terms-section ul { margin-bottom: 1.5rem; padding-left: 2rem; }
        .terms-section li { margin-bottom: 0.5rem; color: var(--text-light); }
        .terms-section strong { color: var(--text-dark); }
        .highlight-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 5px;
        }
        .info-box {
            background: #d1ecf1;
            border-left: 4px solid #0dcaf0;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 5px;
        }
        .important-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 5px;
        }
        .contact-card {
            background: var(--bg-light);
            border-radius: 10px;
            padding: 2rem;
            margin: 3rem 0;
            border: 2px solid var(--border-color);
        }
        .contact-card h3 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .contact-info {
            display: flex;
            align-items: start;
            margin-bottom: 1rem;
        }
        .contact-info i {
            color: var(--primary-color);
            margin-right: 1rem;
            font-size: 1.2rem;
            width: 25px;
            margin-top: 3px;
        }
        .contact-info a {
            color: var(--primary-color);
            text-decoration: none;
        }
        .contact-info a:hover { text-decoration: underline; }
        .footer {
            background: #2c3e50;
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }
        .footer h5 { font-weight: 600; margin-bottom: 1.5rem; }
        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer a:hover { color: white; }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 2rem;
            padding-top: 1.5rem;
            text-align: center;
            color: rgba(255,255,255,0.6);
        }
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--primary-color);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: all 0.3s;
            z-index: 1000;
        }
        .scroll-top:hover {
            background: var(--secondary-color);
            transform: translateY(-5px);
        }
        .scroll-top.show { display: flex; }
        .intro-text {
            background: #e3f2fd;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }
        @media (max-width: 991px) {
            .toc-section { position: static; margin-bottom: 2rem; }
            .terms-hero h1 { font-size: 2rem; }
        }
        @media (max-width: 576px) {
            .terms-hero { padding: 2rem 0; }
            .terms-hero h1 { font-size: 1.6rem; }
            .terms-section h2 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-link me-2"></i>Corelink</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Solutions</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Portal</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="terms-hero">
        <div class="container">
            <h1><i class="fas fa-file-contract me-3"></i>Terms of Use</h1>
            <p class="mb-0">Last Updated: January 14, 2026</p>
        </div>
    </section>

    <div class="container terms-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="toc-section">
                    <h3><i class="fas fa-list me-2"></i>Navigation</h3>
                    <ul class="toc-list">
                        <li><a href="#section-1">1. Acceptance</a></li>
                        <li><a href="#section-2">2. Services</a></li>
                        <li><a href="#section-3">3. Use</a></li>
                        <li><a href="#section-4">4. Portal</a></li>
                        <li><a href="#section-5">5. IP Rights</a></li>
                        <li><a href="#section-6">6. Software</a></li>
                        <li><a href="#section-9">9. Privacy</a></li>
                        <li><a href="#section-10">10. Disclaimer</a></li>
                        <li><a href="#section-11">11. Liability</a></li>
                        <li><a href="#section-20">20. Contact</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="intro-text mb-5">
                    <p>Welcome to Corelink Consulting Ltd. These Terms of Use govern your access to and use of the Corelink website at corelink.co.zm.</p>
                    <p class="mb-0"><strong>By accessing or using this Website, you agree to be bound by these Terms.</strong></p>
                </div>

                <div class="terms-section" id="section-1">
                    <h2>1. Acceptance of Terms</h2>
                    <p>Your use of this Website constitutes acceptance of these Terms of Use and any updates or modifications we may apply periodically.</p>
                </div>

                <div class="terms-section" id="section-2">
                    <h2>2. Description of Services</h2>
                    <p>Corelink Consulting Ltd. is a full-service ICT consulting firm based in Lusaka, Zambia, providing:</p>
                    
                    <h3>Software & Business Solutions</h3>
                    <ul>
                        <li>Custom software development (web, mobile, enterprise)</li>
                        <li>Systems integration</li>
                        <li>Cybersecurity services</li>
                        <li>EduRole Education Management System</li>
                    </ul>

                    <h3>Government Services</h3>
                    <ul>
                        <li>GIS systems</li>
                        <li>Radar and flight control integration</li>
                        <li>Water quality management</li>
                        <li>AI integration</li>
                    </ul>

                    <h3>Hardware Solutions</h3>
                    <ul>
                        <li>HP/Dell servers</li>
                        <li>Cisco/Huawei networking</li>
                        <li>ID card printers and access control</li>
                    </ul>
                </div>

                <div class="terms-section" id="section-3">
                    <h2>3. Use of the Website</h2>
                    <p>You agree to use the Website only for lawful purposes. You may not:</p>
                    <ul>
                        <li>Harm or impair the Website</li>
                        <li>Attempt unauthorized access</li>
                        <li>Use automated tools without permission</li>
                        <li>Transmit malware or harmful code</li>
                    </ul>
                </div>

                <div class="terms-section" id="section-4">
                    <h2>4. Client Portal Access</h2>
                    <div class="info-box">
                        <p><strong><i class="fas fa-info-circle me-2"></i>For Authorized Clients Only</strong></p>
                        <p class="mb-0">Access to the Client Portal is restricted to authorized clients.</p>
                    </div>
                    <p>You are responsible for maintaining credential confidentiality and all account activities.</p>
                </div>

                <div class="terms-section" id="section-5">
                    <h2>5. Intellectual Property</h2>
                    <div class="important-box">
                        <p><strong><i class="fas fa-exclamation-triangle me-2"></i>Important Notice</strong></p>
                        <p class="mb-0">All website content is protected by intellectual property laws. You may not copy, reproduce, or distribute without written permission.</p>
                    </div>
                </div>

                <div class="terms-section" id="section-6">
                    <h2>6. Custom Software Solutions</h2>
                    <p>For custom development clients:</p>
                    <ul>
                        <li>IP rights governed by service agreements</li>
                        <li>Corelink retains ownership of pre-existing frameworks</li>
                        <li>Client data remains client property</li>
                    </ul>
                </div>

                <div class="terms-section" id="section-9">
                    <h2>9. Privacy and Data Protection</h2>
                    <div class="highlight-box">
                        <p><strong><i class="fas fa-shield-alt me-2"></i>Security Measures</strong></p>
                        <p class="mb-0">Our systems are hosted at top-tier Zambian data centers (Infratel, NetOne, ZAMREN) ensuring reliable and secure operations.</p>
                    </div>
                </div>

                <div class="terms-section" id="section-10">
                    <h2>10. Disclaimer of Warranties</h2>
                    <div class="important-box">
                        <p class="mb-0"><strong>THE WEBSITE IS PROVIDED "AS IS" WITHOUT WARRANTIES OF ANY KIND.</strong> This includes merchantability, fitness for purpose, and non-infringement.</p>
                    </div>
                </div>

                <div class="terms-section" id="section-11">
                    <h2>11. Limitation of Liability</h2>
                    <p>Corelink shall not be liable for indirect, incidental, or consequential damages arising from website use.</p>
                    <div class="info-box">
                        <p class="mb-0"><strong><i class="fas fa-info-circle me-2"></i>Note:</strong> Professional services under separate contracts have their own liability terms.</p>
                    </div>
                </div>

                <div class="terms-section" id="section-20">
                    <h2>20. Contact Information</h2>
                    <div class="contact-card">
                        <h3><i class="fas fa-envelope me-2"></i>Get in Touch</h3>
                        
                        <div class="contact-info">
                            <i class="fas fa-building"></i>
                            <div>
                                <strong>Corelink Consulting Ltd.</strong><br>
                                Ngwerema Road 5, Olympia Park<br>
                                Lusaka, Zambia
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>Phone:</strong> <a href="tel:+260963493849">+260 96 349 38 49</a><br>
                                <strong>Hours:</strong> Mon-Fri: 8:00 AM - 5:00 PM
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email:</strong> <a href="mailto:info@corelink.co.zm">info@corelink.co.zm</a>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <i class="fas fa-file-alt"></i>
                            <div>
                                <strong>TPIN:</strong> 2564304921<br>
                                <strong>BRN:</strong> 120220026085
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5 mb-4">
                    <hr>
                    <p class="text-muted mb-0"><em>Last Updated: January 14, 2026</em></p>
                    <p class="mt-3"><small>© 2025 Corelink Consulting Ltd. All rights reserved.</small></p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>Corelink Consulting Ltd.</h5>
                    <p>Building Systems for Development.</p>
                    <p>Ngwerema Road 5, Olympia Park<br>Lusaka, Zambia</p>
                    <p><i class="fas fa-envelope me-2"></i><a href="mailto:info@corelink.co.zm">info@corelink.co.zm</a></p>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Services</a></li>
                        <li class="mb-2"><a href="#">Solutions</a></li>
                        <li class="mb-2"><a href="#">About Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>Legal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>Connect</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#"><i class="fab fa-linkedin me-2"></i>LinkedIn</a></li>
                        <li class="mb-2"><a href="#"><i class="fab fa-twitter me-2"></i>Twitter</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">© 2025 Corelink Consulting Ltd. All rights reserved.</p>
                <p class="mb-0"><small>TPIN: 2564304921 | BRN: 120220026085</small></p>
            </div>
        </div>
    </footer>

    <div class="scroll-top" id="scrollTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        const scrollTop = document.getElementById('scrollTop');
        window.addEventListener('scroll', function() {
            scrollTop.classList.toggle('show', window.pageYOffset > 300);
        });
        scrollTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = this.getAttribute('href');
                if (target !== '#' && document.querySelector(target)) {
                    e.preventDefault();
                    document.querySelector(target).scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
        const sections = document.querySelectorAll('.terms-section');
        const tocLinks = document.querySelectorAll('.toc-list a');
        window.addEventListener('scroll', function() {
            let current = '';
            sections.forEach(section => {
                if (pageYOffset >= (section.offsetTop - 150)) {
                    current = section.getAttribute('id');
                }
            });
            tocLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>