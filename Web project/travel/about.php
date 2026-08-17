<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us — travel.</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
<style>
:root {
  --ink:    #0f0f0f;
  --white:  #ffffff;
  --cream:  #faf8f5;
  --stone:  #f2ede8;
  --muted:  #888880;
  --accent: #c8974a;
  --accent2:#8b5e2a;
  --green:  #2d6a4f;
  --green-l:#d8f3dc;
  --border: #e8e2da;
  --shadow: 0 4px 32px rgba(0,0,0,0.07);
}
*{box-sizing:border-box;margin:0;padding:0}
html{font-size:62.5%;scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);font-size:1.5rem;line-height:1.6;overflow-x:hidden}

/* ── HEADER ── */
.site-header{
  position:fixed;top:0;left:0;right:0;z-index:900;
  display:flex;align-items:center;justify-content:space-between;
  padding:1.8rem 6rem;
  background:rgba(250,248,245,0.92);
  backdrop-filter:blur(12px);
  border-bottom:1px solid var(--border);
}
.logo{font-family:'Playfair Display',serif;font-size:2.6rem;font-weight:700;color:var(--ink);text-decoration:none;letter-spacing:-0.5px}
.logo span{color:var(--accent)}
.nav-links{display:flex;gap:3.2rem}
.nav-links a{font-size:1.4rem;font-weight:500;color:var(--muted);text-decoration:none;letter-spacing:0.3px;transition:color .2s}
.nav-links a:hover,.nav-links a.active{color:var(--ink)}
.header-right{display:flex;align-items:center;gap:1.6rem}
.user-pill{display:flex;align-items:center;gap:1rem;background:var(--stone);border:1px solid var(--border);border-radius:5rem;padding:0.8rem 1.8rem;font-size:1.3rem;font-weight:500}
.user-pill .avatar{width:2.8rem;height:2.8rem;border-radius:50%;background:var(--accent);display:grid;place-items:center;color:#fff;font-size:1.2rem;font-weight:700}
.btn-signin{font-size:1.3rem;color:var(--muted);text-decoration:none;padding:0.8rem 1.8rem;border:1px solid var(--border);border-radius:5rem;transition:.2s}
.btn-signin:hover{background:var(--ink);color:#fff;border-color:var(--ink)}

/* ── HERO / PAGE BANNER ── */
.page-hero{
  min-height:54rem;
  display:flex;align-items:flex-end;
  padding:0 6rem 7rem;
  position:relative;overflow:hidden;
  background:var(--ink);
  margin-top:0;
}
.hero-bg{position:absolute;inset:0;background:url('images/header-bg-1.png') center/cover no-repeat;opacity:0.16}
.hero-grad{position:absolute;inset:0;background:linear-gradient(160deg,rgba(15,15,15,0.5) 0%,rgba(139,94,42,0.35) 60%,rgba(15,15,15,0.9) 100%)}
.hero-text{position:relative;z-index:2;max-width:70rem}
.hero-tag{display:inline-flex;align-items:center;gap:0.8rem;background:rgba(200,151,74,0.15);border:1px solid rgba(200,151,74,0.4);border-radius:5rem;padding:0.7rem 1.8rem;font-size:1.2rem;color:var(--accent);letter-spacing:1px;text-transform:uppercase;font-weight:600;margin-bottom:2rem}
.page-hero h1{font-family:'Playfair Display',serif;font-size:clamp(4.8rem,8vw,8rem);color:#fff;font-weight:700;line-height:1.05;letter-spacing:-1.5px}
.page-hero h1 em{font-style:italic;color:var(--accent)}
.page-hero p{font-size:1.7rem;color:rgba(255,255,255,0.55);margin-top:1.6rem;max-width:52rem;font-weight:300;line-height:1.75}

/* ── SECTION WRAPPER ── */
.section{padding:9rem 6rem}
.section-alt{background:#fff}
.section-dark{background:var(--ink)}

/* ── WHY CHOOSE US ── */
.about-grid{
  display:grid;grid-template-columns:1fr 1fr;gap:8rem;
  align-items:center;max-width:130rem;margin:0 auto;
}
.about-img-wrap{position:relative}
.about-img-wrap img{
  width:100%;border-radius:2rem;
  object-fit:cover;height:58rem;
  display:block;
  box-shadow:0 20px 60px rgba(0,0,0,0.15);
}
.img-badge{
  position:absolute;bottom:-2.4rem;right:-2.4rem;
  background:var(--accent);color:#fff;
  border-radius:1.6rem;padding:2.4rem 3rem;
  box-shadow:0 8px 32px rgba(200,151,74,0.4);
  text-align:center;
}
.img-badge .num{font-family:'Playfair Display',serif;font-size:4.8rem;font-weight:700;line-height:1}
.img-badge .lbl{font-size:1.2rem;font-weight:600;opacity:0.85;letter-spacing:0.5px;margin-top:0.4rem}
.about-content{}
.eyebrow{font-size:1.2rem;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:2px;margin-bottom:1.6rem}
.about-content h2{font-family:'Playfair Display',serif;font-size:clamp(3.2rem,5vw,4.8rem);color:var(--ink);line-height:1.15;letter-spacing:-0.5px;margin-bottom:2.4rem}
.about-content h2 em{font-style:italic;color:var(--accent)}
.about-content p{font-size:1.6rem;color:var(--muted);line-height:1.85;margin-bottom:1.6rem;font-weight:300}

/* ICON PILLS */
.icon-pills{display:flex;flex-direction:column;gap:1.6rem;margin-top:3.6rem}
.icon-pill{
  display:flex;align-items:center;gap:2rem;
  padding:2rem 2.4rem;
  background:var(--stone);border:1px solid var(--border);
  border-radius:1.2rem;transition:.25s;cursor:default;
}
.icon-pill:hover{background:#fff;box-shadow:var(--shadow);transform:translateX(6px)}
.pill-icon{
  width:5rem;height:5rem;border-radius:1rem;
  background:var(--accent);display:flex;align-items:center;justify-content:center;
  font-size:2rem;color:#fff;flex-shrink:0;
}
.pill-txt strong{display:block;font-size:1.5rem;font-weight:700;color:var(--ink);margin-bottom:0.3rem}
.pill-txt span{font-size:1.35rem;color:var(--muted)}

/* ── STATS ROW ── */
.stats-section{
  background:var(--ink);padding:6rem;
  display:flex;justify-content:center;gap:0;
  flex-wrap:wrap;
}
.stat-item{
  flex:1;min-width:18rem;max-width:28rem;
  text-align:center;padding:3rem 2rem;
  border-right:1px solid rgba(255,255,255,0.08);
  position:relative;
}
.stat-item:last-child{border-right:none}
.stat-item .num{font-family:'Playfair Display',serif;font-size:5.2rem;color:var(--accent);font-weight:700;line-height:1}
.stat-item .lbl{font-size:1.4rem;color:rgba(255,255,255,0.45);margin-top:0.8rem;font-weight:400;letter-spacing:0.3px}

/* ── TEAM ── */
.team-section{max-width:130rem;margin:0 auto}
.sec-header{text-align:center;margin-bottom:6rem}
.sec-header .eyebrow{display:block;margin-bottom:1.2rem}
.sec-header h2{font-family:'Playfair Display',serif;font-size:clamp(3rem,5vw,4.4rem);color:var(--ink);line-height:1.2;letter-spacing:-0.5px}
.sec-header h2 em{font-style:italic;color:var(--accent)}
.sec-header p{font-size:1.6rem;color:var(--muted);margin-top:1.2rem;max-width:50rem;margin-left:auto;margin-right:auto;font-weight:300}
.team-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:2.4rem}
.team-card{
  background:#fff;border-radius:1.6rem;border:1px solid var(--border);
  overflow:hidden;transition:.25s;
}
.team-card:hover{transform:translateY(-6px);box-shadow:0 16px 48px rgba(0,0,0,0.1)}
.team-img{height:24rem;overflow:hidden;background:var(--stone);display:flex;align-items:center;justify-content:center;font-size:6rem;color:var(--accent)}
.team-img img{width:100%;height:100%;object-fit:cover;display:block}
.team-info{padding:2.4rem 2rem}
.team-info h4{font-family:'Playfair Display',serif;font-size:2rem;color:var(--ink);margin-bottom:0.4rem}
.team-info .role{font-size:1.3rem;color:var(--accent);font-weight:600;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:1.2rem}
.team-info p{font-size:1.35rem;color:var(--muted);line-height:1.7;font-weight:300}
.team-socials{display:flex;gap:1rem;margin-top:1.6rem}
.team-socials a{width:3.4rem;height:3.4rem;border-radius:50%;border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:var(--muted);text-decoration:none;transition:.2s}
.team-socials a:hover{background:var(--accent);border-color:var(--accent);color:#fff}

/* ── REVIEWS ── */
.reviews-section{background:var(--stone);padding:9rem 6rem}
.reviews-inner{max-width:130rem;margin:0 auto}
.swiper.reviews-slider{padding:2rem 0 5rem!important}
.swiper-slide.r-card{
  background:#fff;border-radius:2rem;
  padding:3.6rem;border:1px solid var(--border);
  position:relative;
}
.r-card .quote-mark{
  font-family:'Playfair Display',serif;font-size:8rem;
  color:var(--accent);opacity:0.15;line-height:0.5;
  position:absolute;top:2.4rem;left:3rem;
}
.r-card .stars{display:flex;gap:0.4rem;margin-bottom:1.6rem}
.r-card .stars i{color:var(--accent);font-size:1.4rem}
.r-card .stars i.empty{color:var(--border)}
.r-card p{font-size:1.5rem;color:#444;line-height:1.85;font-weight:300;margin-bottom:2.4rem;font-style:italic}
.r-card .reviewer{display:flex;align-items:center;gap:1.4rem}
.r-card .reviewer img{width:5rem;height:5rem;border-radius:50%;object-fit:cover;border:2px solid var(--border)}
.r-card .reviewer .avatar-placeholder{width:5rem;height:5rem;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;font-family:'Playfair Display',serif;font-weight:700}
.r-card .reviewer-info h4{font-size:1.5rem;font-weight:700;color:var(--ink)}
.r-card .reviewer-info span{font-size:1.25rem;color:var(--muted)}
.swiper-pagination-bullet{background:var(--accent)!important;opacity:0.3}
.swiper-pagination-bullet-active{opacity:1!important}

/* ── VALUES ── */
.values-section{padding:9rem 6rem;background:#fff}
.values-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:2.4rem;max-width:130rem;margin:0 auto}
.val-card{
  padding:3.6rem 3rem;border-radius:1.6rem;border:1px solid var(--border);
  position:relative;overflow:hidden;transition:.25s;
}
.val-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--accent);transform:scaleX(0);transform-origin:left;transition:.3s}
.val-card:hover::before{transform:scaleX(1)}
.val-card:hover{box-shadow:var(--shadow);transform:translateY(-4px)}
.val-icon{width:5.6rem;height:5.6rem;border-radius:1.2rem;background:rgba(200,151,74,0.1);border:1px solid rgba(200,151,74,0.2);display:flex;align-items:center;justify-content:center;font-size:2.2rem;color:var(--accent);margin-bottom:2rem}
.val-card h3{font-family:'Playfair Display',serif;font-size:2rem;color:var(--ink);margin-bottom:1.2rem}
.val-card p{font-size:1.4rem;color:var(--muted);line-height:1.8;font-weight:300}

/* ── CTA ── */
.cta-section{
  background:var(--ink);padding:10rem 6rem;text-align:center;
  position:relative;overflow:hidden;
}
.cta-section::before{
  content:'';position:absolute;top:-50%;left:-20%;
  width:80rem;height:80rem;border-radius:50%;
  background:radial-gradient(circle,rgba(200,151,74,0.08) 0%,transparent 70%);
  pointer-events:none;
}
.cta-section h2{font-family:'Playfair Display',serif;font-size:clamp(3.2rem,5vw,5.2rem);color:#fff;margin-bottom:1.6rem;letter-spacing:-0.5px}
.cta-section h2 em{font-style:italic;color:var(--accent)}
.cta-section p{font-size:1.7rem;color:rgba(255,255,255,0.45);max-width:54rem;margin:0 auto 4rem;font-weight:300;line-height:1.75}
.btn-cta{
  display:inline-flex;align-items:center;gap:1.2rem;
  padding:1.8rem 5rem;font-size:1.7rem;font-weight:600;
  background:var(--accent);color:#fff;
  border:none;border-radius:1rem;cursor:pointer;
  font-family:'DM Sans',sans-serif;
  text-decoration:none;transition:all .25s;
}
.btn-cta:hover{background:var(--accent2);transform:translateY(-2px);box-shadow:0 8px 24px rgba(200,151,74,0.4)}

/* ── FOOTER ── */
.site-footer{background:#070707;padding:6rem;border-top:1px solid rgba(255,255,255,0.06)}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:5rem;max-width:130rem;margin:0 auto 5rem}
.f-brand .logo{font-size:3rem;display:block;margin-bottom:1.6rem}
.f-brand p{font-size:1.4rem;color:rgba(255,255,255,0.35);line-height:1.8;font-weight:300;max-width:28rem}
.f-col h4{font-size:1.3rem;font-weight:700;color:rgba(255,255,255,0.9);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:2rem}
.f-col a{display:flex;align-items:center;gap:0.8rem;font-size:1.4rem;color:rgba(255,255,255,0.4);text-decoration:none;margin-bottom:1.2rem;transition:.2s;font-weight:300}
.f-col a:hover{color:var(--accent);padding-left:4px}
.f-col a i{font-size:1.2rem;color:var(--accent);opacity:0.7;width:1.4rem}
.footer-bottom{max-width:130rem;margin:0 auto;padding-top:3rem;border-top:1px solid rgba(255,255,255,0.05);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.footer-bottom p{font-size:1.3rem;color:rgba(255,255,255,0.2);font-weight:300}
.footer-bottom span{color:var(--accent)}

/* ── ANIMATIONS ── */
.reveal{opacity:0;transform:translateY(30px);transition:opacity .6s ease,transform .6s ease}
.reveal.visible{opacity:1;transform:translateY(0)}
.reveal-left{opacity:0;transform:translateX(-40px);transition:opacity .7s ease,transform .7s ease}
.reveal-left.visible{opacity:1;transform:translateX(0)}
.reveal-right{opacity:0;transform:translateX(40px);transition:opacity .7s ease,transform .7s ease}
.reveal-right.visible{opacity:1;transform:translateX(0)}

/* ── RESPONSIVE ── */
@media(max-width:1024px){
  .about-grid{grid-template-columns:1fr;gap:5rem}
  .about-img-wrap img{height:40rem}
  .img-badge{right:2rem;bottom:-2rem}
  .team-grid{grid-template-columns:repeat(2,1fr)}
  .footer-grid{grid-template-columns:1fr 1fr;gap:4rem}
}
@media(max-width:768px){
  .site-header{padding:1.6rem 2rem}
  .nav-links{display:none}
  .page-hero{padding:0 2rem 5rem;min-height:44rem}
  .section{padding:6rem 2rem}
  .stats-section{padding:4rem 2rem}
  .reviews-section{padding:6rem 2rem}
  .values-section{padding:6rem 2rem}
  .cta-section{padding:6rem 2rem}
  .site-footer{padding:5rem 2rem}
  .values-grid{grid-template-columns:1fr}
  .team-grid{grid-template-columns:1fr 1fr}
  .stat-item{min-width:14rem}
  .footer-grid{grid-template-columns:1fr}
}
@media(max-width:480px){
  .team-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>

<!-- ══ HEADER ══ -->
<header class="site-header">
  <a href="home.php" class="logo">travel<span>.</span></a>
  <nav class="nav-links">
    <a href="home.php">Home</a>
    <a href="about.php" class="active">About</a>
    <a href="package.php">Packages</a>
    <a href="book.php">Book</a>
    <?php if(isset($_SESSION['user_id'])): ?>
    <a href="my_bookings.php">My Bookings</a>
    <?php endif; ?>
</nav>
  <div class="header-right">
    <?php if(isset($_SESSION['user_id'])): ?>
      <div class="user-pill">
        <div class="avatar"><?= strtoupper(substr($_SESSION['user_name'],0,1)) ?></div>
        <?= htmlspecialchars($_SESSION['user_name']) ?>
      </div>
      <a href="logout.php" class="btn-signin">Sign Out</a>
    <?php else: ?>
      <a href="login.php" class="btn-signin">Sign In</a>
    <?php endif; ?>
  </div>
</header>

<!-- ══ PAGE HERO ══ -->
<section class="page-hero">
  <div class="hero-bg"></div>
  <div class="hero-grad"></div>
  <div class="hero-text reveal">
    <div class="hero-tag"><i class="fas fa-compass"></i> Our Story</div>
    <h1>We Make<br><em>Travel</em><br>Unforgettable.</h1>
    <p>From weekend escapes to world tours — we've been crafting extraordinary journeys since 2010 with passion, precision, and a deep love for exploration.</p>
  </div>
</section>

<!-- ══ STATS ══ -->
<div class="stats-section">
  <div class="stat-item reveal"><div class="num">8,400<span style="color:var(--accent)">+</span></div><div class="lbl">Happy Travelers</div></div>
  <div class="stat-item reveal" style="transition-delay:.1s"><div class="num">120<span style="color:var(--accent)">+</span></div><div class="lbl">Destinations</div></div>
  <div class="stat-item reveal" style="transition-delay:.2s"><div class="num">14</div><div class="lbl">Years of Experience</div></div>
  <div class="stat-item reveal" style="transition-delay:.3s"><div class="num">4.9<span style="color:var(--accent)">★</span></div><div class="lbl">Average Rating</div></div>
</div>

<!-- ══ WHY CHOOSE US ══ -->
<section class="section section-alt">
  <div class="about-grid">
    <div class="about-img-wrap reveal-left">
      <img src="images/about-img.jpg" alt="About travel.">
      <div class="img-badge">
        <div class="num">14+</div>
        <div class="lbl">Years of<br>Experience</div>
      </div>
    </div>
    <div class="about-content reveal-right">
      <p class="eyebrow">Why Choose Us</p>
      <h2>Travel with People<br>Who <em>Truly Care</em></h2>
      <p>We're not just a booking agency — we're your travel companions. Every itinerary is crafted with careful attention to your preferences, budget, and bucket list dreams.</p>
      <p>From the moment you reach out to the day you return home, our dedicated team ensures every detail is handled so you can focus on making memories.</p>
      <div class="icon-pills">
        <div class="icon-pill">
          <div class="pill-icon"><i class="fas fa-map-marked-alt"></i></div>
          <div class="pill-txt">
            <strong>Handpicked Destinations</strong>
            <span>Curated experiences across 120+ countries and regions</span>
          </div>
        </div>
        <div class="icon-pill">
          <div class="pill-icon"><i class="fas fa-hand-holding-usd"></i></div>
          <div class="pill-txt">
            <strong>Transparent Pricing</strong>
            <span>No hidden fees — you see exactly what you pay for</span>
          </div>
        </div>
        <div class="icon-pill">
          <div class="pill-icon"><i class="fas fa-headset"></i></div>
          <div class="pill-txt">
            <strong>24/7 Dedicated Support</strong>
            <span>Real humans available whenever you need us, anywhere</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ CORE VALUES ══ -->
<section class="values-section">
  <div style="max-width:130rem;margin:0 auto">
    <div class="sec-header reveal">
      <span class="eyebrow">What Drives Us</span>
      <h2>Our Core <em>Values</em></h2>
      <p>These principles guide every trip we plan and every relationship we build</p>
    </div>
    <div class="values-grid">
      <div class="val-card reveal">
        <div class="val-icon"><i class="fas fa-heart"></i></div>
        <h3>Passion for Travel</h3>
        <p>Every team member is a traveler at heart. We put personal experience and genuine enthusiasm into every itinerary we create.</p>
      </div>
      <div class="val-card reveal" style="transition-delay:.1s">
        <div class="val-icon"><i class="fas fa-shield-alt"></i></div>
        <h3>Trust & Safety</h3>
        <p>Your security is our priority — from verified accommodations to 24/7 emergency support, we never cut corners on your safety.</p>
      </div>
      <div class="val-card reveal" style="transition-delay:.2s">
        <div class="val-icon"><i class="fas fa-leaf"></i></div>
        <h3>Sustainable Tourism</h3>
        <p>We partner with eco-conscious providers and encourage responsible travel practices that protect destinations for future generations.</p>
      </div>
      <div class="val-card reveal" style="transition-delay:.3s">
        <div class="val-icon"><i class="fas fa-users"></i></div>
        <h3>People First</h3>
        <p>We build real relationships — not just bookings. Our clients become part of the travel. family, returning trip after trip.</p>
      </div>
      <div class="val-card reveal" style="transition-delay:.4s">
        <div class="val-icon"><i class="fas fa-star"></i></div>
        <h3>Excellence Always</h3>
        <p>From the first inquiry to post-trip follow-up, we set the bar high and hold ourselves accountable to delivering the extraordinary.</p>
      </div>
      <div class="val-card reveal" style="transition-delay:.5s">
        <div class="val-icon"><i class="fas fa-lightbulb"></i></div>
        <h3>Innovation</h3>
        <p>We continuously evolve — embracing new tools and ideas to make your planning experience seamless, smart, and enjoyable.</p>
      </div>
    </div>
  </div>
</section>

<!-- ══ TEAM ══ -->
<section class="section section-alt">
  <div class="team-section">
    <div class="sec-header reveal">
      <span class="eyebrow">The People Behind travel.</span>
      <h2>Meet Our <em>Team</em></h2>
      <p>A passionate group of explorers, planners, and dreamers dedicated to making your journey exceptional</p>
    </div>
    <div class="team-grid">
      <div class="team-card reveal">
        <div class="card-img">
          <img src="images/CR-5.jpg" alt="ABC Company">
        </div>
        <div class="team-info">
          <h4>ABC Company</h4>
          <div class="role">Founder & CEO</div>
          <p>15 years shaping unforgettable travel experiences. Visited 80+ countries and counting.</p>
          <div class="team-socials">
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
      </div>
      <div class="team-card reveal" style="transition-delay:.1s">
        <div class="card-img">
          <img src="images/pic-4.png" alt="Sara Ahmed">
        </div>
        <div class="team-info">
          <h4>Sara Ahmed</h4>
          <div class="role">Head of Destinations</div>
          <p>Expert in curating hidden gems. Sara's routes have won three travel industry awards.</p>
          <div class="team-socials">
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
      </div>
      <div class="team-card reveal" style="transition-delay:.2s">
        <div class="card-img">
          <img src="images/pic-5.png" alt="Omar Khalid">
        </div>
        <div class="team-info">
          <h4>Omar Khalid</h4>
          <div class="role">Customer Experience</div>
          <p>Ensuring every traveler feels heard, supported, and cared for — before, during, and after.</p>
          <div class="team-socials">
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
      </div>
      <div class="team-card reveal" style="transition-delay:.3s">
        <div class="card-img">
          <img src="images/pic-6.png" alt="Nadia Rauf">
        </div>
        <div class="team-info">
          <h4>Nadia Rauf</h4>
          <div class="role">Luxury Travel Specialist</div>
          <p>Crafting bespoke, high-end experiences for clients who demand the very best in travel.</p>
          <div class="team-socials">
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ REVIEWS ══ -->
<section class="reviews-section">
  <div class="reviews-inner">
    <div class="sec-header reveal">
      <span class="eyebrow">What Clients Say</span>
      <h2>Real Stories, <em>Real Journeys</em></h2>
      <p>Thousands of travelers trust us every year — here's what they say</p>
    </div>
    <div class="swiper reviews-slider">
      <div class="swiper-wrapper">

        <div class="swiper-slide r-card">
          <div class="quote-mark">"</div>
          <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
          <p>Absolutely flawless experience from start to finish. The team handled everything — our Bali honeymoon was more magical than we could have imagined. Every detail was perfect.</p>
          <div class="reviewer">
            <div class="card-img">J</div>
            <img src="images/CR-6.jpg" alt="John & Priya Deo">
            <div class="reviewer-info"><h4>John & Priya Deo</h4><span>Honeymooners · Bali Trip</span></div>
          </div>
        </div>

        <div class="swiper-slide r-card">
          <div class="quote-mark">"</div>
          <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star empty"></i></div>
          <p>We've booked three family trips through travel. and every single one has been outstanding. The kids still talk about the safari. The customer support is genuinely incredible.</p>
          <div class="reviewer">
            <div class="card-img">M</div>
            <img src="images/CR-1.jpg" alt="Maria Gonzalez">
            <div class="reviewer-info"><h4>Maria Gonzalez</h4><span>Family Traveler · Kenya Safari</span></div>
          </div>
        </div>

        <div class="swiper-slide r-card">
          <div class="quote-mark">"</div>
          <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
          <p>As a solo traveler, I was nervous about the logistics. The team made it so easy — hotels, transfers, local guides — everything was lined up. I just had to show up and enjoy.</p>
          <div class="reviewer">
            <div class="card-img">A</div>
            <img src="images/pic-3.png" alt="Ahmad Raza">
            <div class="reviewer-info"><h4>Ahmad Raza</h4><span>Solo Traveler · Europe Tour</span></div>
          </div>
        </div>

        <div class="swiper-slide r-card">
          <div class="quote-mark">"</div>
          <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
          <p>The booking process was smooth, the prices were fair, and the trip exceeded every expectation. I've recommended travel. to everyone in my office. Truly world-class service.</p>
          <div class="reviewer">
            <div class="card-img">S</div>
             <img src="images/CR-2.jpg" alt="Sophie Laurent">
            <div class="reviewer-info"><h4>Sophie Laurent</h4><span>Business Traveler · Tokyo & Dubai</span></div>
          </div>
        </div>

        <div class="swiper-slide r-card">
          <div class="quote-mark">"</div>
          <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star empty"></i></div>
          <p>Fantastic value and genuine care from the team. When our flight got delayed, they had an alternate plan ready within the hour. That kind of reliability is priceless when you're abroad.</p>
          <div class="reviewer">
            <div class="card-img">K</div>
              <img src="images/CR-4.jpg" alt="Kamran Ali">
            <div class="reviewer-info"><h4>Kamran Ali</h4><span>Adventure Traveler · Nepal Trek</span></div>
          </div>
        </div>

        <div class="swiper-slide r-card">
          <div class="quote-mark">"</div>
          <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
          <p>We were a group of 12 friends and I was dreading the coordination nightmare. travel. made it look effortless. Everyone had the time of their lives on our Thailand trip.</p>
          <div class="reviewer">
            <div class="card-img">R</div>
            <img src="images/CR-3.jpg" alt="Rina Chaudhry">
            <div class="reviewer-info"><h4>Rina Chaudhry</h4><span>Group Travel · Thailand</span></div>
          </div>
        </div>

      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>

<!-- ══ CTA ══ -->
<section class="cta-section">
  <div class="reveal">
    <h2>Ready to Start Your<br><em>Next Adventure?</em></h2>
    <p>Join thousands of happy travelers. Let us handle every detail while you focus on the memories.</p>
    <a href="book.php" class="btn-cta">Plan My Trip <i class="fas fa-arrow-right"></i></a>
  </div>
</section>

<!-- ══ FOOTER ══ -->
<footer class="site-footer">
  <div class="footer-grid">
    <div class="f-brand">
      <a href="home.php" class="logo" style="color:#fff">travel<span>.</span></a>
      <p>We craft extraordinary journeys for curious souls. Your adventure is our passion — and your satisfaction is our promise.</p>
    </div>
    <div class="f-col">
      <h4>Navigate</h4>
      <a href="home.php"><i class="fas fa-angle-right"></i> Home</a>
      <a href="about.php"><i class="fas fa-angle-right"></i> About</a>
      <a href="package.php"><i class="fas fa-angle-right"></i> Packages</a>
      <a href="book.php"><i class="fas fa-angle-right"></i> Book a Trip</a>
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
      <a href="#"><i class="fas fa-phone"></i> +123-456-7890</a>
      <a href="#"><i class="fas fa-envelope"></i> hello@travel.com</a>
      <a href="#"><i class="fas fa-map-marker-alt"></i> Dubai, UAE</a>
      <div style="display:flex;gap:1.2rem;margin-top:1.6rem">
        <a href="#" style="width:3.6rem;height:3.6rem;border-radius:50%;border:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.4);font-size:1.4rem;text-decoration:none;transition:.2s" onmouseover="this.style.background='var(--accent)';this.style.color='#fff'" onmouseout="this.style.background='';this.style.color='rgba(255,255,255,0.4)'"><i class="fab fa-instagram"></i></a>
        <a href="#" style="width:3.6rem;height:3.6rem;border-radius:50%;border:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.4);font-size:1.4rem;text-decoration:none;transition:.2s" onmouseover="this.style.background='var(--accent)';this.style.color='#fff'" onmouseout="this.style.background='';this.style.color='rgba(255,255,255,0.4)'"><i class="fab fa-facebook-f"></i></a>
        <a href="#" style="width:3.6rem;height:3.6rem;border-radius:50%;border:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.4);font-size:1.4rem;text-decoration:none;transition:.2s" onmouseover="this.style.background='var(--accent)';this.style.color='#fff'" onmouseout="this.style.background='';this.style.color='rgba(255,255,255,0.4)'"><i class="fab fa-twitter"></i></a>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 <span>travel.</span> — All rights reserved</p>
    <p>Made with <span>♥</span> for explorers worldwide</p>
  </div>
</footer>

<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<script>
// ── SWIPER ──
new Swiper('.reviews-slider', {
  slidesPerView: 1,
  spaceBetween: 24,
  pagination: { el: '.swiper-pagination', clickable: true },
  breakpoints: {
    640:  { slidesPerView: 1.2 },
    900:  { slidesPerView: 2 },
    1200: { slidesPerView: 3 }
  },
  loop: true,
  autoplay: { delay: 4500, disableOnInteraction: false }
});

// ── SCROLL REVEAL ──
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if(e.isIntersecting) {
      e.target.classList.add('visible');
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => observer.observe(el));
</script>
</body>
</html>