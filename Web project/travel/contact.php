<?php
session_start();
$success = '';
$error   = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name    = trim($_POST['name'] ?? '');
  $email   = trim($_POST['email'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if($name && $email && $subject && $message){
    $success = "Thank you, $name! Your message has been received. We'll reply within 24 hours.";
  } else {
    $error = "Please fill in all fields before submitting.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us — travel.</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root{
      --ink:#0f0f0f;--bg:#1a1410;--bg2:#211c16;
      --accent:#c8974a;--accent2:#8b5e2a;
      --text:#ccc;--muted:#888;--border:#2e2820;
    }
    *{margin:0;padding:0;box-sizing:border-box}
    html{font-size:62.5%}
    body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh}

    nav{position:fixed;top:0;left:0;right:0;z-index:100;background:rgba(26,20,16,0.95);backdrop-filter:blur(10px);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 6rem;height:7rem}
    .nav-logo{font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:700;color:#fff;text-decoration:none}
    .nav-logo span{color:var(--accent)}
    .nav-links{display:flex;gap:3rem;list-style:none}
    .nav-links a{color:var(--muted);text-decoration:none;font-size:1.4rem;font-weight:500;transition:.2s}
    .nav-links a:hover,.nav-links a.active{color:var(--accent)}
    .nav-btn{background:var(--accent);color:#fff;padding:1rem 2.4rem;border-radius:5rem;font-size:1.3rem;font-weight:600;text-decoration:none;transition:.2s}
    .nav-btn:hover{background:var(--accent2)}

    .page-hero{padding:14rem 6rem 7rem;background:linear-gradient(160deg,#211c16 0%,#1a1410 60%,#0f0f0f 100%);border-bottom:1px solid var(--border);position:relative;overflow:hidden}
    .page-hero::before{content:'';position:absolute;width:60rem;height:60rem;background:radial-gradient(circle,rgba(200,151,74,0.08) 0%,transparent 70%);border-radius:50%;top:-10rem;right:-5rem;pointer-events:none}
    .hero-label{display:inline-block;background:rgba(200,151,74,0.12);border:1px solid rgba(200,151,74,0.25);color:var(--accent);font-size:1.2rem;font-weight:600;letter-spacing:2px;text-transform:uppercase;padding:0.6rem 1.6rem;border-radius:5rem;margin-bottom:2rem}
    .page-hero h1{font-family:'Playfair Display',serif;font-size:5.5rem;font-weight:700;color:#fff;line-height:1.15;margin-bottom:1.6rem}
    .page-hero h1 em{color:var(--accent);font-style:italic}
    .page-hero p{font-size:1.7rem;color:var(--muted);font-weight:300;max-width:50rem}

    /* CONTACT LAYOUT */
    .contact-wrapper{
      max-width:1100px;margin:7rem auto;
      padding:0 3rem 10rem;
      display:grid;grid-template-columns:1fr 1.7fr;gap:6rem;align-items:start;
    }

    /* INFO SIDE */
    .contact-info h2{font-family:'Playfair Display',serif;color:#fff;font-size:2.8rem;margin-bottom:1rem}
    .contact-info h2 em{color:var(--accent);font-style:italic}
    .contact-info > p{color:var(--muted);font-size:1.5rem;line-height:1.8;margin-bottom:3.5rem}

    .info-card{display:flex;align-items:flex-start;gap:1.8rem;margin-bottom:2.8rem}
    .info-icon{
      width:5rem;height:5rem;border-radius:1.2rem;
      background:rgba(200,151,74,0.1);border:1px solid rgba(200,151,74,0.2);
      display:flex;align-items:center;justify-content:center;
      flex-shrink:0;
    }
    .info-icon i{color:var(--accent);font-size:1.8rem}
    .info-text strong{display:block;color:#fff;font-size:1.5rem;font-weight:600;margin-bottom:0.4rem}
    .info-text span{color:var(--muted);font-size:1.4rem;line-height:1.7}

    /* FORM SIDE */
    .contact-form{
      background:#211c16;
      border:1px solid var(--border);
      border-radius:1.6rem;
      padding:4rem;
    }
    .contact-form h3{font-family:'Playfair Display',serif;color:#fff;font-size:2.2rem;margin-bottom:2.8rem}

    .alert-success{background:rgba(46,204,113,0.08);border:1px solid rgba(46,204,113,0.25);color:#2ecc71;padding:1.4rem 1.8rem;border-radius:0.8rem;font-size:1.4rem;margin-bottom:2.4rem;display:flex;align-items:center;gap:1rem}
    .alert-error{background:rgba(231,76,60,0.08);border:1px solid rgba(231,76,60,0.25);color:#e74c3c;padding:1.4rem 1.8rem;border-radius:0.8rem;font-size:1.4rem;margin-bottom:2.4rem;display:flex;align-items:center;gap:1rem}

    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1.6rem}
    .field-group{margin-bottom:2rem}
    .field-group label{display:block;color:#fff;font-size:1.3rem;font-weight:600;letter-spacing:0.5px;margin-bottom:0.9rem}
    .field-group input,
    .field-group textarea{
      width:100%;
      background:#1a1410;
      border:1.5px solid var(--border);
      color:#fff;
      padding:1.4rem 1.6rem;
      font-size:1.5rem;
      font-family:'DM Sans',sans-serif;
      border-radius:0.8rem;
      outline:none;
      transition:.2s;
    }
    .field-group input::placeholder,
    .field-group textarea::placeholder{color:#555}
    .field-group input:focus,
    .field-group textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(200,151,74,0.1)}
    .field-group textarea{resize:vertical;min-height:13rem}

    .btn-send{
      width:100%;
      background:var(--accent);color:#fff;
      border:none;padding:1.6rem;
      font-size:1.5rem;font-weight:700;
      font-family:'DM Sans',sans-serif;
      letter-spacing:1px;text-transform:uppercase;
      border-radius:0.8rem;cursor:pointer;
      transition:.2s;display:flex;align-items:center;justify-content:center;gap:1rem;
    }
    .btn-send:hover{background:var(--accent2);transform:translateY(-2px);box-shadow:0 8px 25px rgba(200,151,74,0.25)}

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

    @media(max-width:900px){
      .contact-wrapper{grid-template-columns:1fr}
      .form-row{grid-template-columns:1fr}
    }
    @media(max-width:768px){
      nav{padding:0 2rem}.nav-links{display:none}
      .page-hero{padding:12rem 2rem 5rem}.page-hero h1{font-size:3.5rem}
      .contact-wrapper{padding:0 2rem 6rem;margin:5rem auto}
      .contact-form{padding:2.5rem 2rem}
      .footer-grid{grid-template-columns:1fr}.site-footer{padding:4rem 2rem 0}
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
  <h1>Get in<br><em>Touch</em></h1>
  <p>We're here for you — before, during, and after your journey.</p>
</div>

<div class="contact-wrapper">

  <!-- LEFT INFO -->
  <div class="contact-info">
    <h2>We'd Love to<br><em>Hear From You</em></h2>
    <p>Whether you have a question about a package, need help with your booking, or just want to plan your next adventure — reach out.</p>

    <div class="info-card">
      <div class="info-icon"><i class="fas fa-phone"></i></div>
      <div class="info-text">
        <strong>Phone</strong>
        <span>+123-456-7890<br>Mon–Sun, 9am – 10pm GST</span>
      </div>
    </div>

    <div class="info-card">
      <div class="info-icon"><i class="fas fa-envelope"></i></div>
      <div class="info-text">
        <strong>Email</strong>
        <span>hello@travel.com<br>We reply within 24 hours</span>
      </div>
    </div>

    <div class="info-card">
      <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
      <div class="info-text">
        <strong>Office</strong>
        <span>Downtown Dubai,<br>Dubai, UAE</span>
      </div>
    </div>

    <div class="info-card">
      <div class="info-icon"><i class="fas fa-clock"></i></div>
      <div class="info-text">
        <strong>Working Hours</strong>
        <span>Monday – Sunday<br>9:00 AM – 10:00 PM</span>
      </div>
    </div>
  </div>

  <!-- RIGHT FORM -->
  <div class="contact-form">
    <h3>Send a Message</h3>

    <?php if($success): ?>
      <div class="alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if($error): ?>
      <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="contact.php">
      <div class="form-row">
        <div class="field-group">
          <label>Your Name</label>
          <input type="text" name="name" placeholder="John Doe" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="field-group">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="you@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
      </div>
      <div class="field-group">
        <label>Subject</label>
        <input type="text" name="subject" placeholder="e.g. Question about a package" required value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
      </div>
      <div class="field-group">
        <label>Message</label>
        <textarea name="message" placeholder="Write your message here..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
      </div>
      <button type="submit" class="btn-send"><i class="fas fa-paper-plane"></i> Send Message</button>
    </form>
  </div>

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