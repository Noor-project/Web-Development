<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Privacy Policy — travel.</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root{
      --ink:#0f0f0f;--bg:#1a1410;--bg2:#211c16;--bg3:#2a2218;
      --accent:#c8974a;--accent2:#8b5e2a;
      --text:#ccc;--muted:#888;--border:#2e2820;--white:#fff;
    }
    *{margin:0;padding:0;box-sizing:border-box}
    html{font-size:62.5%}
    body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh}

    nav{position:fixed;top:0;left:0;right:0;z-index:100;background:rgba(26,20,16,0.95);backdrop-filter:blur(10px);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 6rem;height:7rem}
    .nav-logo{font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:700;color:#fff;text-decoration:none}
    .nav-logo span{color:var(--accent)}
    .nav-links{display:flex;gap:3rem;list-style:none}
    .nav-links a{color:var(--muted);text-decoration:none;font-size:1.4rem;font-weight:500;transition:.2s}
    .nav-links a:hover{color:var(--accent)}
    .nav-btn{background:var(--accent);color:#fff;padding:1rem 2.4rem;border-radius:5rem;font-size:1.3rem;font-weight:600;text-decoration:none;transition:.2s}
    .nav-btn:hover{background:var(--accent2)}

    .page-hero{padding:14rem 6rem 7rem;background:linear-gradient(160deg,#211c16 0%,#1a1410 60%,#0f0f0f 100%);border-bottom:1px solid var(--border);position:relative;overflow:hidden}
    .page-hero::before{content:'';position:absolute;width:60rem;height:60rem;background:radial-gradient(circle,rgba(200,151,74,0.08) 0%,transparent 70%);border-radius:50%;top:-10rem;right:-5rem;pointer-events:none}
    .hero-label{display:inline-block;background:rgba(200,151,74,0.12);border:1px solid rgba(200,151,74,0.25);color:var(--accent);font-size:1.2rem;font-weight:600;letter-spacing:2px;text-transform:uppercase;padding:0.6rem 1.6rem;border-radius:5rem;margin-bottom:2rem}
    .page-hero h1{font-family:'Playfair Display',serif;font-size:5.5rem;font-weight:700;color:#fff;line-height:1.15;margin-bottom:1.6rem}
    .page-hero h1 em{color:var(--accent);font-style:italic}
    .page-hero p{font-size:1.6rem;color:var(--muted);font-weight:300}

    .policy-wrap{max-width:820px;margin:7rem auto;padding:0 3rem 10rem}
    .effective-badge{display:inline-flex;align-items:center;gap:1rem;background:rgba(200,151,74,0.08);border:1px solid rgba(200,151,74,0.2);color:var(--muted);font-size:1.3rem;padding:0.8rem 1.8rem;border-radius:5rem;margin-bottom:4rem}
    .effective-badge i{color:var(--accent)}

    .policy-wrap p{color:var(--muted);font-size:1.5rem;line-height:1.9;margin-bottom:1.2rem}
    .policy-wrap h2{font-family:'Playfair Display',serif;color:#fff;font-size:2rem;margin:4rem 0 1.4rem;padding-left:1.6rem;border-left:3px solid var(--accent)}
    .policy-wrap ul{padding-left:2rem;color:var(--muted);font-size:1.5rem;line-height:2.2;margin-bottom:1.2rem}
    .policy-wrap a{color:var(--accent);text-decoration:none}
    .policy-wrap a:hover{text-decoration:underline}

    .site-footer{background:#0a0906;padding:6rem 6rem 0;border-top:1px solid var(--border)}
    .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:4rem;padding-bottom:5rem}
    .f-brand .logo{font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:700;text-decoration:none;color:#fff}
    .f-brand .logo span{color:var(--accent)}
    .f-brand p{margin-top:1.6rem;color:var(--muted);font-size:1.4rem;line-height:1.8;max-width:26rem}
    .f-col h4{color:#fff;font-size:1.1rem;letter-spacing:3px;text-transform:uppercase;margin-bottom:2rem;font-weight:600}
    .f-col a{display:block;color:var(--muted);text-decoration:none;font-size:1.4rem;margin-bottom:1.2rem;transition:color .2s}
    .f-col a:hover{color:var(--accent)}
    .f-col a i{color:var(--accent);margin-right:0.8rem;font-size:1.1rem}
    .footer-bottom{border-top:1px solid var(--border);padding:2rem 0;display:flex;justify-content:space-between;font-size:1.3rem;color:#555}
    .footer-bottom span{color:var(--accent)}

    @media(max-width:768px){nav{padding:0 2rem}.nav-links{display:none}.page-hero{padding:12rem 2rem 5rem}.page-hero h1{font-size:3.5rem}.policy-wrap{padding:0 2rem 6rem}.footer-grid{grid-template-columns:1fr}.site-footer{padding:4rem 2rem 0}}
  </style>
</head>
<body>

<nav>
  <a href="home.php" class="nav-logo">travel<span>.</span></a>
  <ul class="nav-links">
    <li><a href="home.php">Home</a></li>
    <li><a href="about.php">About</a></li>
    <li><a href="package.php">Packages</a></li>
    <li><a href="contact.php">Support</a></li>
  </ul>
  <?php if(isset($_SESSION['user_id'])): ?>
    <a href="my_bookings.php" class="nav-btn">My Bookings</a>
  <?php else: ?>
    <a href="login.php" class="nav-btn">Login</a>
  <?php endif; ?>
</nav>

<div class="page-hero">
  <div class="hero-label">Legal</div>
  <h1>Privacy<br><em>Policy</em></h1>
  <p>Effective Date: January 1, 2025</p>
</div>

<div class="policy-wrap">

  <div class="effective-badge"><i class="fas fa-shield-alt"></i> Last updated: January 1, 2025</div>

  <p>Welcome to <strong style="color:#fff">travel.</strong> We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, and safeguard your data when you use our website or services.</p>

  <h2>1. Information We Collect</h2>
  <p>We collect information you voluntarily provide when you register, book a trip, or contact us. This may include:</p>
  <ul>
    <li>Full name, email address, phone number</li>
    <li>Passport or travel document details (for bookings)</li>
    <li>Payment information (processed securely — we do not store card numbers)</li>
    <li>Travel preferences and special requests</li>
  </ul>

  <h2>2. How We Use Your Information</h2>
  <ul>
    <li>Process and confirm your travel bookings</li>
    <li>Send booking confirmations and trip updates</li>
    <li>Respond to your inquiries and support requests</li>
    <li>Improve our website and services</li>
    <li>Send promotional offers (only if you opt in)</li>
  </ul>

  <h2>3. Sharing Your Information</h2>
  <p>We do not sell, trade, or rent your personal information to third parties. We may share your data only with trusted service providers strictly necessary to fulfill your booking, and only with your consent.</p>

  <h2>4. Data Security</h2>
  <p>We implement industry-standard security measures to protect your personal data. All sensitive data is encrypted in transit and at rest. However, no method of internet transmission is 100% secure.</p>

  <h2>5. Cookies</h2>
  <p>Our website uses cookies to enhance your browsing experience and analyze site traffic. You can instruct your browser to refuse cookies, though some parts of the site may not function properly.</p>

  <h2>6. Your Rights</h2>
  <p>You have the right to access, correct, or delete your personal data at any time. Contact us at <a href="mailto:hello@travel.com">hello@travel.com</a> to exercise these rights.</p>

  <h2>7. Contact Us</h2>
  <p><a href="mailto:hello@travel.com">hello@travel.com</a> &nbsp;·&nbsp; +123-456-7890 &nbsp;·&nbsp; Dubai, UAE</p>

</div>

<footer class="site-footer">
  <div class="footer-grid">
    <div class="f-brand">
      <a href="home.php" class="logo">travel<span>.</span></a>
      <p>We craft extraordinary journeys for curious souls. Your adventure is our passion and your satisfaction is our promise.</p>
    </div>
    <div class="f-col">
      <h4>Navigate</h4>
      <a href="home.php"><i class="fas fa-angle-right"></i> Home</a>
      <a href="about.php"><i class="fas fa-angle-right"></i> About</a>
      <a href="package.php"><i class="fas fa-angle-right"></i> Packages</a>
      <a href="book.php"><i class="fas fa-angle-right"></i> Book a Trip</a>
      <?php if(isset($_SESSION['user_id'])): ?>
      <a href="my_bookings.php"><i class="fas fa-angle-right"></i> My Bookings</a>
      <?php endif; ?>
    </div>
    <div class="f-col">
      <h4>Support</h4>
      <a href="faq.php"><i class="fas fa-angle-right"></i> FAQs</a>
      <a href="privacy.php"><i class="fas fa-angle-right"></i> Privacy Policy</a>
      <a href="terms.php"><i class="fas fa-angle-right"></i> Terms of Use</a>
      <a href="contact.php"><i class="fas fa-angle-right"></i> Contact Us</a>
    </div>
    <div class="f-col">
      <h4>Contact</h4>
      <a href="tel:+1234567890"><i class="fas fa-phone"></i> +123-456-7890</a>
      <a href="mailto:hello@travel.com"><i class="fas fa-envelope"></i> hello@travel.com</a>
      <a href="#"><i class="fas fa-map-marker-alt"></i> Dubai, UAE</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 <span>travel.</span> — All rights reserved</p>
    <p>Made with <span>♥</span> for explorers worldwide</p>
  </div>
</footer>

</body>
</html>