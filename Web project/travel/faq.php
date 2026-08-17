<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FAQs — travel.</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root{
      --ink:#0f0f0f; --bg:#1a1410; --bg2:#211c16; --bg3:#2a2218;
      --accent:#c8974a; --accent2:#8b5e2a;
      --text:#ccc; --muted:#888; --border:#2e2820;
      --white:#fff;
    }
    *{margin:0;padding:0;box-sizing:border-box}
    html{font-size:62.5%}
    body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh}

    /* NAV */
    nav{
      position:fixed;top:0;left:0;right:0;z-index:100;
      background:rgba(26,20,16,0.95);backdrop-filter:blur(10px);
      border-bottom:1px solid var(--border);
      display:flex;align-items:center;justify-content:space-between;
      padding:0 6rem;height:7rem;
    }
    .nav-logo{font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:700;color:#fff;text-decoration:none}
    .nav-logo span{color:var(--accent)}
    .nav-links{display:flex;gap:3rem;list-style:none}
    .nav-links a{color:var(--muted);text-decoration:none;font-size:1.4rem;font-weight:500;transition:.2s}
    .nav-links a:hover{color:var(--accent)}
    .nav-links a.active{color:#fff}
    .nav-btn{
      background:var(--accent);color:#fff;
      padding:1rem 2.4rem;border-radius:5rem;
      font-size:1.3rem;font-weight:600;
      text-decoration:none;transition:.2s;
    }
    .nav-btn:hover{background:var(--accent2)}

    /* HERO */
    .page-hero{
      padding:14rem 6rem 7rem;
      background:linear-gradient(160deg,#211c16 0%,#1a1410 60%,#0f0f0f 100%);
      border-bottom:1px solid var(--border);
      position:relative;overflow:hidden;
    }
    .page-hero::before{
      content:'';position:absolute;
      width:60rem;height:60rem;
      background:radial-gradient(circle,rgba(200,151,74,0.08) 0%,transparent 70%);
      border-radius:50%;top:-10rem;right:-5rem;pointer-events:none;
    }
    .hero-label{
      display:inline-block;
      background:rgba(200,151,74,0.12);
      border:1px solid rgba(200,151,74,0.25);
      color:var(--accent);
      font-size:1.2rem;font-weight:600;letter-spacing:2px;text-transform:uppercase;
      padding:0.6rem 1.6rem;border-radius:5rem;margin-bottom:2rem;
    }
    .page-hero h1{
      font-family:'Playfair Display',serif;
      font-size:5.5rem;font-weight:700;color:#fff;
      line-height:1.15;margin-bottom:1.6rem;
    }
    .page-hero h1 em{color:var(--accent);font-style:italic}
    .page-hero p{font-size:1.7rem;color:var(--muted);font-weight:300;max-width:50rem}

    /* FAQ */
    .faq-section{max-width:820px;margin:7rem auto;padding:0 3rem 10rem}

    .faq-item{
      border-bottom:1px solid var(--border);
    }
    .faq-question{
      display:flex;justify-content:space-between;align-items:center;
      padding:2.4rem 0;cursor:pointer;
      color:#fff;font-size:1.6rem;font-weight:600;
      font-family:'Playfair Display',serif;
      transition:color .2s;gap:2rem;
    }
    .faq-question:hover{color:var(--accent)}
    .faq-icon{
      width:3.2rem;height:3.2rem;border-radius:50%;
      background:rgba(200,151,74,0.1);border:1px solid rgba(200,151,74,0.2);
      display:flex;align-items:center;justify-content:center;
      flex-shrink:0;transition:.3s;
    }
    .faq-icon i{color:var(--accent);font-size:1.1rem;transition:transform .3s}
    .faq-item.open .faq-icon{background:rgba(200,151,74,0.2)}
    .faq-item.open .faq-icon i{transform:rotate(180deg)}
    .faq-answer{
      max-height:0;overflow:hidden;
      transition:max-height .4s ease,padding .3s;
      color:var(--muted);font-size:1.5rem;line-height:1.9;
    }
    .faq-item.open .faq-answer{max-height:200px;padding-bottom:2.4rem}

    /* FOOTER */
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

    @media(max-width:768px){
      nav{padding:0 2rem}
      .nav-links{display:none}
      .page-hero{padding:12rem 2rem 5rem}
      .page-hero h1{font-size:3.5rem}
      .faq-section{padding:0 2rem 6rem}
      .footer-grid{grid-template-columns:1fr}
      .site-footer{padding:4rem 2rem 0}
    }
  </style>
</head>
<body>

<nav>
  <a href="home.php" class="nav-logo">travel<span>.</span></a>
  <ul class="nav-links">
    <li><a href="home.php">Home</a></li>
    <li><a href="about.php">About</a></li>
    <li><a href="package.php">Packages</a></li>
    <li><a href="contact.php" class="active">Support</a></li>
  </ul>
  <?php if(isset($_SESSION['user_id'])): ?>
    <a href="my_bookings.php" class="nav-btn">My Bookings</a>
  <?php else: ?>
    <a href="login.php" class="nav-btn">Login</a>
  <?php endif; ?>
</nav>

<div class="page-hero">
  <div class="hero-label">Support</div>
  <h1>Frequently Asked<br><em>Questions</em></h1>
  <p>Everything you need to know before your next adventure.</p>
</div>

<section class="faq-section">

  <div class="faq-item open">
    <div class="faq-question">
      How do I book a trip?
      <div class="faq-icon"><i class="fas fa-chevron-down"></i></div>
    </div>
    <div class="faq-answer">
      Browse our Packages page, choose a destination, and click "Book a Trip". Fill in your details and our team will confirm your reservation within 24 hours.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">
      What payment methods do you accept?
      <div class="faq-icon"><i class="fas fa-chevron-down"></i></div>
    </div>
    <div class="faq-answer">
      We accept all major credit/debit cards, bank transfers, and select digital wallets. All payments are processed securely and you receive an email confirmation immediately.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">
      Can I cancel or modify my booking?
      <div class="faq-icon"><i class="fas fa-chevron-down"></i></div>
    </div>
    <div class="faq-answer">
      Yes. Cancellations made 14+ days before departure receive a full refund. Within 14 days a cancellation fee may apply. To modify, contact us at hello@travel.com as soon as possible.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">
      Are flights included in the packages?
      <div class="faq-icon"><i class="fas fa-chevron-down"></i></div>
    </div>
    <div class="faq-answer">
      Package inclusions vary. Some packages include round-trip flights, others cover accommodation and tours only. Each package page clearly lists what is and isn't included before you book.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">
      Is travel insurance provided?
      <div class="faq-icon"><i class="fas fa-chevron-down"></i></div>
    </div>
    <div class="faq-answer">
      Travel insurance is not included by default but we strongly recommend it. We can connect you with trusted insurance partners during the booking process for comprehensive coverage.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">
      How do I contact support during my trip?
      <div class="faq-icon"><i class="fas fa-chevron-down"></i></div>
    </div>
    <div class="faq-answer">
      Our support line +123-456-7890 is available 24/7. You can also email hello@travel.com or use the Contact page. We're always here for you, wherever you are.
    </div>
  </div>

</section>

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

<script>
  document.querySelectorAll('.faq-question').forEach(q => {
    q.addEventListener('click', () => {
      const item = q.parentElement;
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
      if(!isOpen) item.classList.add('open');
    });
  });
</script>
</body>
</html>