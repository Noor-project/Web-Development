<?php
session_start();
$connection = mysqli_connect('localhost','root','','booking_db');
$packages_db = mysqli_query($connection, "SELECT * FROM packages WHERE status='active' ORDER BY id ASC");
$total_packages = mysqli_num_rows($packages_db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Packages — travel.</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{
  --ink:#0f0f0f;--cream:#faf8f5;--stone:#f2ede8;--muted:#888880;
  --accent:#c8974a;--accent2:#8b5e2a;--green:#2d6a4f;--border:#e8e2da;
  --shadow:0 4px 32px rgba(0,0,0,0.07);
}
*{box-sizing:border-box;margin:0;padding:0}
html{font-size:62.5%;scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);font-size:1.5rem;line-height:1.6;overflow-x:hidden}
.site-header{position:fixed;top:0;left:0;right:0;z-index:900;display:flex;align-items:center;justify-content:space-between;padding:1.8rem 6rem;background:rgba(250,248,245,0.92);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)}
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
.page-hero{min-height:52rem;display:flex;align-items:flex-end;padding:0 6rem 7rem;position:relative;overflow:hidden;background:var(--ink)}
.hero-bg{position:absolute;inset:0;background:url('images/header-bg-2.png') center/cover no-repeat;opacity:0.18}
.hero-grad{position:absolute;inset:0;background:linear-gradient(160deg,rgba(15,15,15,0.5) 0%,rgba(139,94,42,0.35) 60%,rgba(15,15,15,0.9) 100%)}
.hero-text{position:relative;z-index:2;max-width:70rem}
.hero-tag{display:inline-flex;align-items:center;gap:0.8rem;background:rgba(200,151,74,0.15);border:1px solid rgba(200,151,74,0.4);border-radius:5rem;padding:0.7rem 1.8rem;font-size:1.2rem;color:var(--accent);letter-spacing:1px;text-transform:uppercase;font-weight:600;margin-bottom:2rem}
.page-hero h1{font-family:'Playfair Display',serif;font-size:clamp(4.8rem,8vw,8rem);color:#fff;font-weight:700;line-height:1.05;letter-spacing:-1.5px}
.page-hero h1 em{font-style:italic;color:var(--accent)}
.page-hero p{font-size:1.7rem;color:rgba(255,255,255,0.5);margin-top:1.6rem;max-width:52rem;font-weight:300;line-height:1.75}
.filter-bar{background:#fff;border-bottom:1px solid var(--border);padding:2rem 6rem;display:flex;align-items:center;gap:1.2rem;flex-wrap:wrap;position:sticky;top:7rem;z-index:800}
.filter-label{font-size:1.3rem;font-weight:700;color:var(--ink);margin-right:0.8rem;white-space:nowrap}
.f-btn{padding:0.9rem 2rem;font-size:1.35rem;font-family:'DM Sans',sans-serif;border:1.5px solid var(--border);border-radius:5rem;background:#fff;color:var(--muted);cursor:pointer;transition:.2s;font-weight:500}
.f-btn:hover{border-color:var(--accent);color:var(--accent)}
.f-btn.on{background:var(--accent);border-color:var(--accent);color:#fff;font-weight:600}
.filter-count{margin-left:auto;font-size:1.3rem;color:var(--muted)}
.filter-count span{color:var(--ink);font-weight:700}
.packages-section{padding:6rem;max-width:160rem;margin:0 auto}
.pkg-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(34rem,1fr));gap:2.8rem}
.pkg-card{background:#fff;border-radius:2rem;border:1px solid var(--border);overflow:hidden;transition:.3s;position:relative}
.pkg-card:hover{transform:translateY(-8px);box-shadow:0 20px 56px rgba(0,0,0,0.12)}
.pkg-card.hidden{display:none}
.card-img{position:relative;height:26rem;overflow:hidden;background:var(--stone)}
.card-img img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s ease}
.pkg-card:hover .card-img img{transform:scale(1.06)}
.card-badge{position:absolute;top:1.6rem;left:1.6rem;color:#fff;font-size:1.15rem;font-weight:700;padding:0.5rem 1.4rem;border-radius:5rem;background:var(--accent)}
.card-badge.adventure{background:#e74c3c}
.card-badge.honeymoon{background:var(--green)}
.card-badge.luxury{background:var(--accent2)}
.card-badge.family{background:#3498db}
.card-badge.beach{background:#1abc9c}
.card-img-overlay{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(15,15,15,0.6));padding:2rem 2rem 1.6rem;display:flex;align-items:center;gap:0.6rem}
.card-rating{display:flex;align-items:center;gap:0.5rem;font-size:1.3rem;color:#fff;font-weight:600}
.card-rating i{color:#f1c40f;font-size:1.2rem}
.card-body{padding:2.4rem 2.4rem 0}
.card-meta{display:flex;gap:1.6rem;margin-bottom:1.2rem;flex-wrap:wrap}
.meta-tag{display:flex;align-items:center;gap:0.5rem;font-size:1.25rem;color:var(--muted)}
.meta-tag i{color:var(--accent);font-size:1.2rem}
.card-body h3{font-family:'Playfair Display',serif;font-size:2.2rem;color:var(--ink);margin-bottom:0.8rem;line-height:1.25}
.card-body p{font-size:1.4rem;color:var(--muted);line-height:1.75;font-weight:300;margin-bottom:2rem}
.card-footer{padding:1.8rem 2.4rem;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.card-price .from{font-size:1.2rem;color:var(--muted);display:block;margin-bottom:0.1rem}
.card-price .amount{font-family:'Playfair Display',serif;font-size:2.8rem;color:var(--ink);font-weight:700;line-height:1}
.card-price .amount span{color:var(--accent)}
.card-price .per{font-size:1.2rem;color:var(--muted)}
.btn-book{display:flex;align-items:center;gap:0.8rem;padding:1.1rem 2.4rem;font-size:1.4rem;font-weight:600;background:var(--ink);color:#fff;border:none;border-radius:0.8rem;cursor:pointer;text-decoration:none;transition:.2s;font-family:'DM Sans',sans-serif}
.btn-book:hover{background:var(--accent)}
.empty-state{text-align:center;padding:8rem 2rem;grid-column:1/-1}
.empty-state i{font-size:5rem;color:var(--border);display:block;margin-bottom:2rem}
.empty-state h3{font-family:'Playfair Display',serif;font-size:2.4rem;color:var(--ink);margin-bottom:1rem}
.empty-state p{font-size:1.5rem;color:var(--muted)}
.cta-section{background:var(--ink);padding:10rem 6rem;text-align:center;position:relative;overflow:hidden}
.cta-section::before{content:'';position:absolute;top:-50%;left:-20%;width:80rem;height:80rem;border-radius:50%;background:radial-gradient(circle,rgba(200,151,74,0.08) 0%,transparent 70%);pointer-events:none}
.cta-section h2{font-family:'Playfair Display',serif;font-size:clamp(3.2rem,5vw,5.2rem);color:#fff;margin-bottom:1.6rem;letter-spacing:-0.5px}
.cta-section h2 em{font-style:italic;color:var(--accent)}
.cta-section p{font-size:1.7rem;color:rgba(255,255,255,0.45);max-width:54rem;margin:0 auto 4rem;font-weight:300;line-height:1.75}
.btn-cta{display:inline-flex;align-items:center;gap:1.2rem;padding:1.8rem 5rem;font-size:1.7rem;font-weight:600;background:var(--accent);color:#fff;border:none;border-radius:1rem;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;transition:.25s}
.btn-cta:hover{background:var(--accent2);transform:translateY(-2px);box-shadow:0 8px 24px rgba(200,151,74,0.4)}
.site-footer{background:#070707;padding:6rem;border-top:1px solid rgba(255,255,255,0.06)}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:5rem;max-width:130rem;margin:0 auto 5rem}
.f-brand p{font-size:1.4rem;color:rgba(255,255,255,0.35);line-height:1.8;font-weight:300;max-width:28rem;margin-top:1.6rem}
.f-col h4{font-size:1.3rem;font-weight:700;color:rgba(255,255,255,0.9);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:2rem}
.f-col a{display:flex;align-items:center;gap:0.8rem;font-size:1.4rem;color:rgba(255,255,255,0.4);text-decoration:none;margin-bottom:1.2rem;transition:.2s;font-weight:300}
.f-col a:hover{color:var(--accent);padding-left:4px}
.f-col a i{font-size:1.2rem;color:var(--accent);opacity:0.7;width:1.4rem}
.footer-bottom{max-width:130rem;margin:0 auto;padding-top:3rem;border-top:1px solid rgba(255,255,255,0.05);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.footer-bottom p{font-size:1.3rem;color:rgba(255,255,255,0.2);font-weight:300}
.footer-bottom span{color:var(--accent)}
.reveal{opacity:0;transform:translateY(24px);transition:opacity .55s ease,transform .55s ease}
.reveal.visible{opacity:1;transform:translateY(0)}
@media(max-width:1024px){.footer-grid{grid-template-columns:1fr 1fr;gap:4rem}}
@media(max-width:768px){
  .site-header{padding:1.6rem 2rem}.nav-links{display:none}
  .page-hero{padding:0 2rem 5rem;min-height:44rem}
  .filter-bar{padding:1.6rem 2rem;top:6.5rem}
  .packages-section{padding:4rem 2rem}
  .pkg-grid{grid-template-columns:1fr}
  .cta-section,.site-footer{padding:6rem 2rem}
  .footer-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>

<header class="site-header">
  <a href="home.php" class="logo">travel<span>.</span></a>
  <nav class="nav-links">
    <a href="home.php">Home</a>
    <a href="about.php">About</a>
    <a href="package.php" class="active">Packages</a>
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

<section class="page-hero">
  <div class="hero-bg"></div>
  <div class="hero-grad"></div>
  <div class="hero-text reveal">
    <div class="hero-tag"><i class="fas fa-globe"></i> Top Destinations</div>
    <h1>Find Your<br><em>Perfect</em><br>Package.</h1>
    <p>Handpicked experiences across the world's most breathtaking destinations — for every traveler, every budget.</p>
  </div>
</section>

<div class="filter-bar">
  <span class="filter-label">Filter:</span>
  <button class="f-btn on" onclick="filter(this,'all')">All</button>
  <button class="f-btn" onclick="filter(this,'adventure')">Adventure</button>
  <button class="f-btn" onclick="filter(this,'honeymoon')">Honeymoon</button>
  <button class="f-btn" onclick="filter(this,'family')">Family</button>
  <button class="f-btn" onclick="filter(this,'luxury')">Luxury</button>
  <button class="f-btn" onclick="filter(this,'beach')">Beach</button>
  <span class="filter-count">Showing <span id="shown-count"><?= $total_packages ?></span> packages</span>
</div>

<section class="packages-section">
  <div class="pkg-grid" id="pkgGrid">

    <?php if($total_packages > 0): ?>
      <?php while($pkg = mysqli_fetch_assoc($packages_db)): ?>
      <?php
        // Set badge based on category
        $badge_labels = [
          'adventure' => '🔥 Adventure',
          'honeymoon' => '💕 Honeymoon',
          'family'    => '👨‍👩‍👧 Family',
          'luxury'    => '👑 Luxury',
          'beach'     => '🏖️ Beach'
        ];
        $badge = $badge_labels[$pkg['category']] ?? ucfirst($pkg['category']);

        // Set category icon
        $cat_icons = [
          'adventure' => 'fa-mountain',
          'honeymoon' => 'fa-heart',
          'family'    => 'fa-child',
          'luxury'    => 'fa-gem',
          'beach'     => 'fa-umbrella-beach'
        ];
        $cat_icon = $cat_icons[$pkg['category']] ?? 'fa-globe';
      ?>
      <div class="pkg-card reveal" data-cat="<?= htmlspecialchars($pkg['category']) ?>">
        <div class="card-img">
          <img src="<?= htmlspecialchars($pkg['image']) ?>"
               alt="<?= htmlspecialchars($pkg['title']) ?>"
               onerror="this.src='images/img-1.jpg'">
          <span class="card-badge <?= htmlspecialchars($pkg['category']) ?>"><?= $badge ?></span>
          <div class="card-img-overlay">
            <div class="card-rating"><i class="fas fa-star"></i> 4.8</div>
          </div>
        </div>
        <div class="card-body">
          <div class="card-meta">
            <span class="meta-tag"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($pkg['location']) ?></span>
            <span class="meta-tag"><i class="fas fa-clock"></i> <?= htmlspecialchars($pkg['duration']) ?></span>
            <span class="meta-tag"><i class="fas <?= $cat_icon ?>"></i> <?= ucfirst($pkg['category']) ?></span>
          </div>
          <h3><?= htmlspecialchars($pkg['title']) ?></h3>
          <p><?= htmlspecialchars($pkg['description']) ?></p>
        </div>
        <div class="card-footer">
          <div class="card-price">
            <span class="from">From</span>
            <div class="amount">$<span><?= number_format($pkg['price'], 0) ?></span></div>
            <span class="per">per person</span>
          </div>
          <a href="book.php" class="btn-book">Book Now <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
      <?php endwhile; ?>

    <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>No Packages Available</h3>
        <p>Check back soon — we're adding new destinations!</p>
      </div>
    <?php endif; ?>

  </div>
</section>

<section class="cta-section">
  <h2>Can't Find What<br><em>You're Looking For?</em></h2>
  <p>Tell us your dream destination and we'll build a custom itinerary just for you — any budget, any style.</p>
  <a href="book.php" class="btn-cta">Plan a Custom Trip <i class="fas fa-arrow-right"></i></a>
</section>

<footer class="site-footer">
  <div class="footer-grid">
    <div class="f-brand">
      <a href="home.php" class="logo" style="color:#fff">travel<span>.</span></a>
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
      <a href="#"><i class="fas fa-phone"></i> +123-456-7890</a>
      <a href="#"><i class="fas fa-envelope"></i> hello@travel.com</a>
      <a href="#"><i class="fas fa-map-marker-alt"></i> Dubai, UAE</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 <span>travel.</span> — All rights reserved</p>
    <p>Made with <span>♥</span> for explorers worldwide</p>
  </div>
</footer>

<script>
function filter(btn, cat) {
  document.querySelectorAll('.f-btn').forEach(b => b.classList.remove('on'));
  btn.classList.add('on');
  let count = 0;
  document.querySelectorAll('.pkg-card').forEach(c => {
    const match = cat === 'all' || c.dataset.cat === cat;
    c.classList.toggle('hidden', !match);
    if(match) count++;
  });
  document.getElementById('shown-count').textContent = count;
}

function wish(btn) {
  btn.classList.toggle('liked');
  btn.querySelector('i').style.transform = 'scale(1.4)';
  setTimeout(() => btn.querySelector('i').style.transform = 'scale(1)', 200);
}

const observer = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if(e.isIntersecting){ e.target.classList.add('visible'); observer.unobserve(e.target); }
  });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
</body>
</html>