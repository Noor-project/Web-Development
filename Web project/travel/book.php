<?php
session_start();
// Mock: comment/uncomment to test logged-in state
// $_SESSION['user_id'] = 1;
// $_SESSION['user_name'] = 'Ahmad';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Your Trip — travel.</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
  --red:    #c0392b;
  --border: #e8e2da;
  --shadow: 0 4px 32px rgba(0,0,0,0.08);
  --r:      1.2rem;
}
*{box-sizing:border-box;margin:0;padding:0}
html{font-size:62.5%;scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);font-size:1.5rem;line-height:1.6}

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
.btn-logout{font-size:1.3rem;color:var(--muted);text-decoration:none;padding:0.8rem 1.8rem;border:1px solid var(--border);border-radius:5rem;transition:.2s}
.btn-logout:hover{background:var(--ink);color:#fff;border-color:var(--ink)}

/* ── HERO ── */
.hero{
  min-height:100vh;
  display:flex;align-items:center;justify-content:center;
  position:relative;overflow:hidden;
  background:var(--ink);
  padding:12rem 6rem 8rem;
}
.hero-bg{
  position:absolute;inset:0;
  background:url('images/header-bg-3.png') center/cover no-repeat;
  opacity:0.18;
}
.hero-overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(15,15,15,0.85) 0%,rgba(139,94,42,0.4) 100%)}
.hero-content{position:relative;z-index:2;text-align:center;max-width:80rem}
.hero-tag{display:inline-flex;align-items:center;gap:0.8rem;background:rgba(200,151,74,0.15);border:1px solid rgba(200,151,74,0.4);border-radius:5rem;padding:0.8rem 2rem;font-size:1.3rem;color:var(--accent);letter-spacing:1px;text-transform:uppercase;font-weight:500;margin-bottom:2.4rem}
.hero h1{font-family:'Playfair Display',serif;font-size:clamp(4rem,8vw,7.2rem);color:#fff;font-weight:700;line-height:1.1;margin-bottom:2rem;letter-spacing:-1px}
.hero h1 em{font-style:italic;color:var(--accent)}
.hero p{font-size:1.8rem;color:rgba(255,255,255,0.65);max-width:56rem;margin:0 auto 4rem;font-weight:300;line-height:1.7}
.btn-book-now{
  display:inline-flex;align-items:center;gap:1.2rem;
  padding:1.8rem 5rem;font-size:1.7rem;font-weight:600;
  background:var(--accent);color:#fff;
  border:none;border-radius:var(--r);cursor:pointer;
  font-family:'DM Sans',sans-serif;
  transition:all .25s;
  letter-spacing:0.3px;
}
.btn-book-now:hover{background:var(--accent2);transform:translateY(-2px);box-shadow:0 8px 24px rgba(200,151,74,0.4)}
.btn-book-now i{transition:transform .2s}
.btn-book-now:hover i{transform:translateX(4px)}
.hero-stats{display:flex;gap:4rem;justify-content:center;margin-top:5rem;padding-top:4rem;border-top:1px solid rgba(255,255,255,0.1)}
.h-stat span{display:block;font-family:'Playfair Display',serif;font-size:3.2rem;color:#fff;font-weight:700}
.h-stat p{font-size:1.3rem;color:rgba(255,255,255,0.5);letter-spacing:0.5px}

/* ── AUTH GATE ── */
#auth-gate{
  display:none;position:fixed;inset:0;z-index:1000;
  align-items:center;justify-content:center;
  background:rgba(15,15,15,0.7);
  backdrop-filter:blur(8px);
  animation:fadeIn .25s ease;
}
#auth-gate.show{display:flex}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
.auth-modal{
  background:#fff;border-radius:2rem;
  width:90%;max-width:44rem;
  overflow:hidden;
  animation:slideUp .3s cubic-bezier(.34,1.56,.64,1);
  box-shadow:0 24px 80px rgba(0,0,0,0.25);
}
@keyframes slideUp{from{transform:translateY(40px);opacity:0}to{transform:translateY(0);opacity:1}}
.auth-top{
  background:linear-gradient(135deg,var(--ink),#2c2218);
  padding:3.6rem 3.2rem 3rem;text-align:center;
}
.auth-top .icon{font-size:3.6rem;margin-bottom:1.2rem;display:block}
.auth-top h2{font-family:'Playfair Display',serif;font-size:2.4rem;color:#fff;margin-bottom:0.8rem}
.auth-top p{font-size:1.4rem;color:rgba(255,255,255,0.55);line-height:1.6}
.auth-body{padding:3.2rem}
.auth-tabs{display:grid;grid-template-columns:1fr 1fr;background:var(--stone);border-radius:0.8rem;padding:0.4rem;margin-bottom:2.8rem}
.auth-tab{padding:1rem;text-align:center;font-size:1.4rem;font-weight:600;cursor:pointer;border-radius:0.6rem;transition:.2s;color:var(--muted)}
.auth-tab.active{background:#fff;color:var(--ink);box-shadow:0 2px 8px rgba(0,0,0,0.08)}
.auth-form{display:none}
.auth-form.active{display:block}
.a-field{margin-bottom:2rem}
.a-field label{display:block;font-size:1.3rem;font-weight:600;color:var(--ink);margin-bottom:0.8rem}
.a-field input{
  width:100%;padding:1.4rem 1.6rem;
  font-size:1.5rem;font-family:'DM Sans',sans-serif;
  border:1.5px solid var(--border);border-radius:0.8rem;
  background:#fafafa;color:var(--ink);outline:none;transition:.2s;
}
.a-field input:focus{border-color:var(--accent);background:#fff;box-shadow:0 0 0 3px rgba(200,151,74,0.1)}
.btn-auth{
  width:100%;padding:1.5rem;font-size:1.5rem;font-weight:600;
  background:var(--ink);color:#fff;border:none;
  border-radius:0.8rem;cursor:pointer;font-family:'DM Sans',sans-serif;
  transition:.2s;margin-top:0.8rem;
}
.btn-auth:hover{background:var(--accent)}
.auth-divider{text-align:center;font-size:1.3rem;color:var(--muted);margin:1.6rem 0}
.auth-close{position:absolute;top:1.6rem;right:2rem;background:none;border:none;font-size:2rem;cursor:pointer;color:rgba(255,255,255,0.6);transition:.2s}
.auth-close:hover{color:#fff}
.auth-top{position:relative}

/* ── BOOKING WRAPPER ── */
#booking-app{display:none;padding:10rem 2rem 6rem}
#booking-app.show{display:block}

/* ── PROGRESS STEPS ── */
.progress-outer{max-width:88rem;margin:0 auto 3.6rem}
.steps-bar{display:flex;align-items:flex-start;justify-content:space-between;position:relative}
.steps-bar::before{
  content:'';position:absolute;top:2rem;left:5%;right:5%;height:2px;
  background:var(--border);z-index:0;
}
.fill-bar{
  position:absolute;top:2rem;left:5%;height:2px;
  background:var(--accent);z-index:1;
  width:0%;transition:width .5s ease;
}
.step-item{display:flex;flex-direction:column;align-items:center;gap:1rem;z-index:2;cursor:default}
.step-dot{
  width:4rem;height:4rem;border-radius:50%;
  background:#fff;border:2px solid var(--border);
  display:flex;align-items:center;justify-content:center;
  font-size:1.3rem;color:var(--muted);transition:.3s;
}
.step-lbl{font-size:1.15rem;color:var(--muted);font-weight:500;white-space:nowrap}
.step-item.is-active .step-dot{background:var(--accent);border-color:var(--accent);color:#fff;box-shadow:0 0 0 4px rgba(200,151,74,0.15)}
.step-item.is-active .step-lbl{color:var(--accent);font-weight:600}
.step-item.is-done .step-dot{background:var(--green);border-color:var(--green);color:#fff}
.step-item.is-done .step-lbl{color:var(--green)}

/* ── FORM CARD ── */
.form-card{
  max-width:88rem;margin:0 auto;
  background:#fff;border-radius:2rem;
  box-shadow:var(--shadow);border:1px solid var(--border);
  overflow:hidden;
}
.card-top{
  padding:3rem 4rem;
  background:var(--ink);
  display:flex;align-items:center;gap:2rem;
  border-bottom:1px solid rgba(255,255,255,0.06);
}
.card-top-icon{
  width:5.2rem;height:5.2rem;border-radius:1.2rem;
  background:rgba(200,151,74,0.15);border:1px solid rgba(200,151,74,0.3);
  display:flex;align-items:center;justify-content:center;
  font-size:2.2rem;color:var(--accent);flex-shrink:0;
}
.card-top h3{font-family:'Playfair Display',serif;font-size:2.2rem;color:#fff;margin-bottom:0.3rem}
.card-top p{font-size:1.4rem;color:rgba(255,255,255,0.45)}

/* ── SECTIONS ── */
.form-sec{display:none}
.form-sec.show{display:block}
.sec-body{padding:3.6rem 4rem}

/* ── FIELDS ── */
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:2.4rem}
.grid-1{display:grid;grid-template-columns:1fr;gap:2.4rem}
.fld{display:flex;flex-direction:column;gap:0.8rem}
.fld label{font-size:1.3rem;font-weight:600;color:var(--ink)}
.fld label .req{color:var(--red)}
.inp-wrap{position:relative}
.inp-wrap .ic{position:absolute;left:1.4rem;top:50%;transform:translateY(-50%);color:var(--accent);font-size:1.4rem;pointer-events:none}
.inp-wrap input,.inp-wrap select,.inp-wrap textarea{
  width:100%;padding:1.3rem 1.4rem 1.3rem 4rem;
  font-size:1.45rem;font-family:'DM Sans',sans-serif;
  border:1.5px solid var(--border);border-radius:0.8rem;
  background:#fafafa;color:var(--ink);outline:none;transition:.2s;
}
.inp-wrap textarea{padding-top:1.3rem;min-height:10rem;resize:vertical}
.inp-wrap input:focus,.inp-wrap select:focus,.inp-wrap textarea:focus{
  border-color:var(--accent);background:#fff;
  box-shadow:0 0 0 3px rgba(200,151,74,0.1);
}
.inp-ck{position:absolute;right:1.2rem;top:50%;transform:translateY(-50%);font-size:1.6rem;display:none}
.inp-ck.ok{color:var(--green);display:block}
.inp-ck.err{color:var(--red);display:block}
.f-err{font-size:1.2rem;color:var(--red);display:none;align-items:center;gap:0.4rem}
.f-err.show{display:flex}

/* ── PHONE ROW ── */
.phone-row{display:flex;gap:1rem}
.phone-row select{
  padding:1.3rem 1rem;font-size:1.4rem;border:1.5px solid var(--border);
  border-radius:0.8rem;font-family:'DM Sans',sans-serif;
  background:#fafafa;width:9.5rem;outline:none;cursor:pointer;transition:.2s;
}
.phone-row select:focus{border-color:var(--accent)}
.phone-row .inp-wrap{flex:1}

/* ── SECTION DIVIDER ── */
.sec-divider{
  margin:3rem 0 2rem;padding:1.6rem 2rem;
  background:var(--stone);border-radius:0.8rem;
  border-left:3px solid var(--accent);
  display:flex;align-items:center;gap:1rem;
  font-size:1.3rem;font-weight:600;color:var(--accent);
}

/* ── CHOICE GRID ── */
.choice-grid{display:grid;gap:1.2rem}
.choice-grid.col-4{grid-template-columns:repeat(4,1fr)}
.choice-grid.col-3{grid-template-columns:repeat(3,1fr)}
.choice-grid.col-6{grid-template-columns:repeat(3,1fr)}
.c-card{
  border:2px solid var(--border);border-radius:1.2rem;
  padding:2rem 1.2rem;text-align:center;cursor:pointer;
  transition:.2s;background:#fff;
}
.c-card:hover{border-color:var(--accent);background:rgba(200,151,74,0.04)}
.c-card.chosen{border-color:var(--accent);background:rgba(200,151,74,0.07)}
.c-card i{font-size:2.4rem;color:var(--accent);display:block;margin-bottom:1rem}
.c-card .c-name{font-size:1.35rem;font-weight:600;color:var(--ink)}
.c-card .c-sub{font-size:1.15rem;color:var(--muted);margin-top:0.3rem}

/* ── BUDGET ── */
.budget-big{text-align:center;font-family:'Playfair Display',serif;font-size:4.8rem;color:var(--ink);margin:2rem 0 0.5rem}
.budget-big span{color:var(--accent)}
.budget-note{text-align:center;font-size:1.3rem;color:var(--muted);margin-bottom:2rem}
input[type=range].slider{
  width:100%;height:4px;border-radius:2px;-webkit-appearance:none;appearance:none;
  background:linear-gradient(90deg,var(--accent) 5%,var(--border) 5%);
  outline:none;cursor:pointer;margin:1rem 0;
}
input[type=range].slider::-webkit-slider-thumb{-webkit-appearance:none;width:20px;height:20px;border-radius:50%;background:var(--accent);cursor:pointer;box-shadow:0 2px 8px rgba(200,151,74,0.4)}
.slider-labels{display:flex;justify-content:space-between;font-size:1.2rem;color:var(--muted)}
.budget-pills{display:flex;gap:1rem;flex-wrap:wrap;margin-top:1.8rem}
.b-pill{
  padding:0.8rem 1.8rem;border:1.5px solid var(--border);
  border-radius:5rem;font-size:1.3rem;cursor:pointer;color:var(--muted);
  transition:.2s;font-weight:500;
}
.b-pill:hover,.b-pill.on{border-color:var(--accent);color:var(--accent);background:rgba(200,151,74,0.06);font-weight:600}

/* ── NIGHTS BADGE ── */
.nights-pill{
  display:none;align-items:center;gap:0.8rem;
  background:var(--green-l);border:1.5px solid #a8d5b5;
  border-radius:5rem;padding:0.8rem 2rem;font-size:1.4rem;
  color:var(--green);font-weight:600;width:fit-content;margin-top:1.6rem;
}
.nights-pill.show{display:flex}

/* ── SUMMARY ── */
.sum-price{
  background:var(--ink);border-radius:1.4rem;
  padding:3rem;text-align:center;margin-bottom:2.4rem;
}
.sum-price .label{font-size:1.4rem;color:rgba(255,255,255,0.5);margin-bottom:0.6rem}
.sum-price .amount{font-family:'Playfair Display',serif;font-size:5rem;color:#fff;font-weight:700;letter-spacing:-1px}
.sum-price .amount span{color:var(--accent)}
.sum-price .breakdown{font-size:1.3rem;color:rgba(255,255,255,0.4);margin-top:0.6rem}
.sum-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.6rem;margin-bottom:2.4rem}
.sum-box{background:var(--stone);border-radius:1.2rem;padding:2.4rem;border:1px solid var(--border)}
.sum-box h4{font-size:1.25rem;font-weight:700;color:var(--ink);text-transform:uppercase;letter-spacing:1px;margin-bottom:1.6rem;padding-bottom:1.2rem;border-bottom:1px solid var(--border)}
.s-row{display:flex;justify-content:space-between;padding:0.7rem 0;font-size:1.3rem;border-bottom:1px solid rgba(0,0,0,0.04)}
.s-row:last-child{border-bottom:none}
.s-row .lbl{color:var(--muted)}
.s-row .val{font-weight:600;color:var(--ink);text-align:right;max-width:60%;word-break:break-word}
.trust-row{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-bottom:2rem}
.trust-chip{display:flex;align-items:center;gap:0.6rem;background:var(--green-l);border:1px solid #a8d5b5;border-radius:5rem;padding:0.7rem 1.5rem;font-size:1.2rem;color:var(--green);font-weight:500}

/* ── NAV BUTTONS ── */
.form-nav{
  display:flex;justify-content:space-between;align-items:center;
  padding:2.4rem 4rem;border-top:1px solid var(--border);background:var(--stone);
}
.btn-back{
  display:flex;align-items:center;gap:0.8rem;
  padding:1.2rem 2.4rem;font-size:1.4rem;font-family:'DM Sans',sans-serif;
  border:1.5px solid var(--border);border-radius:0.8rem;
  cursor:pointer;background:#fff;color:var(--muted);transition:.2s;font-weight:500;
}
.btn-back:hover{background:var(--ink);color:#fff;border-color:var(--ink)}
.btn-back.ghost{visibility:hidden}
.btn-next{
  display:flex;align-items:center;gap:0.8rem;
  padding:1.2rem 3rem;font-size:1.5rem;font-family:'DM Sans',sans-serif;
  background:var(--ink);color:#fff;border:none;border-radius:0.8rem;
  cursor:pointer;transition:.2s;font-weight:600;
}
.btn-next:hover{background:var(--accent)}
.btn-next.hide{display:none}
.btn-confirm{
  display:none;align-items:center;gap:0.8rem;
  padding:1.4rem 4rem;font-size:1.5rem;font-family:'DM Sans',sans-serif;
  background:var(--green);color:#fff;border:none;border-radius:0.8rem;
  cursor:pointer;transition:.2s;font-weight:600;
}
.btn-confirm.show{display:flex}
.btn-confirm:hover{background:#1b4332;transform:translateY(-1px)}

/* ── SUCCESS SCREEN ── */
#success-screen{
  display:none;position:fixed;inset:0;z-index:2000;
  background:#fff;align-items:center;justify-content:center;
  flex-direction:column;
  animation:fadeIn .4s ease;
}
#success-screen.show{display:flex}
.success-inner{max-width:56rem;width:90%;text-align:center}
.success-check{
  width:9rem;height:9rem;border-radius:50%;
  background:var(--green);display:flex;align-items:center;justify-content:center;
  margin:0 auto 3rem;
  animation:scaleIn .5s cubic-bezier(.34,1.56,.64,1) .1s both;
}
@keyframes scaleIn{from{transform:scale(0)}to{transform:scale(1)}}
.success-check i{font-size:4rem;color:#fff}
.success-inner h2{
  font-family:'Playfair Display',serif;font-size:3.8rem;
  color:var(--ink);margin-bottom:1.2rem;letter-spacing:-0.5px;
  animation:fadeUp .5s .3s both;
}
.success-inner .sub{
  font-size:1.6rem;color:var(--muted);line-height:1.8;margin-bottom:3.6rem;
  animation:fadeUp .5s .4s both;
}
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.success-ref{
  background:var(--stone);border-radius:1.2rem;border:1px solid var(--border);
  padding:2rem 3rem;margin-bottom:3.2rem;
  animation:fadeUp .5s .5s both;
}
.success-ref .ref-label{font-size:1.2rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.6rem;font-weight:600}
.success-ref .ref-num{font-family:'Playfair Display',serif;font-size:3rem;color:var(--accent);font-weight:700;letter-spacing:2px}
.success-actions{display:flex;gap:1.4rem;justify-content:center;animation:fadeUp .5s .6s both}
.btn-home{
  padding:1.2rem 3rem;font-size:1.5rem;font-family:'DM Sans',sans-serif;
  background:var(--ink);color:#fff;border:none;border-radius:0.8rem;
  cursor:pointer;transition:.2s;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:0.8rem;
}
.btn-home:hover{background:var(--accent)}
.btn-new{
  padding:1.2rem 3rem;font-size:1.5rem;font-family:'DM Sans',sans-serif;
  background:#fff;color:var(--ink);border:1.5px solid var(--border);border-radius:0.8rem;
  cursor:pointer;transition:.2s;font-weight:600;
}
.btn-new:hover{background:var(--stone)}

/* ── FEATURES STRIP ── */
.features{background:#fff;border-top:1px solid var(--border);padding:4rem 6rem;display:flex;justify-content:center;gap:6rem;flex-wrap:wrap}
.feat{display:flex;align-items:center;gap:1.4rem}
.feat i{font-size:2.2rem;color:var(--accent)}
.feat-txt strong{display:block;font-size:1.4rem;font-weight:600;color:var(--ink)}
.feat-txt span{font-size:1.2rem;color:var(--muted)}

@media(max-width:768px){
  .site-header{padding:1.6rem 2rem}
  .nav-links{display:none}
  .hero{padding:10rem 2rem 6rem}
  .hero-stats{flex-wrap:wrap;gap:2rem}
  .grid-2{grid-template-columns:1fr}
  .choice-grid.col-4{grid-template-columns:repeat(2,1fr)}
  .choice-grid.col-3{grid-template-columns:repeat(2,1fr)}
  .choice-grid.col-6{grid-template-columns:repeat(2,1fr)}
  .sum-grid{grid-template-columns:1fr}
  .sec-body{padding:2.4rem 2rem}
  .form-nav{padding:1.8rem 2rem}
  .card-top{padding:2.4rem 2rem}
  .features{padding:3rem 2rem;gap:3rem}
  .step-lbl{font-size:0;width:0;overflow:hidden}
  .success-inner h2{font-size:2.8rem}
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
      <a href="logout.php" class="btn-logout">Sign Out</a>
    <?php else: ?>
      <a href="login.php" class="btn-logout">Sign In</a>
    <?php endif; ?>
  </div>
</header>

<!-- ══ HERO ══ -->
<section class="hero" id="heroSection">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="hero-tag"><i class="fas fa-compass"></i> Curated Travel Experiences</div>
    <h1>Your Next<br><em>Adventure</em><br>Starts Here</h1>
    <p>Tell us where you want to go. We'll handle everything — flights, hotels, transfers, and unforgettable moments.</p>
    <button class="btn-book-now" id="startBookingBtn" onclick="handleBookNow()">
      Start Planning <i class="fas fa-arrow-right"></i>
    </button>
    <div class="hero-stats">
      <div class="h-stat"><span>8,400+</span><p>Trips Booked</p></div>
      <div class="h-stat"><span>120+</span><p>Destinations</p></div>
      <div class="h-stat"><span>4.9★</span><p>Average Rating</p></div>
    </div>
  </div>
</section>

<!-- ══ FEATURES ══ -->
<div class="features">
  <div class="feat"><i class="fas fa-shield-alt"></i><div class="feat-txt"><strong>Secure Payments</strong><span>256-bit SSL encrypted</span></div></div>
  <div class="feat"><i class="fas fa-headset"></i><div class="feat-txt"><strong>24/7 Support</strong><span>Always here for you</span></div></div>
  <div class="feat"><i class="fas fa-undo"></i><div class="feat-txt"><strong>Free Cancellation</strong><span>Up to 48hrs before trip</span></div></div>
  <div class="feat"><i class="fas fa-star"></i><div class="feat-txt"><strong>Best Price Guarantee</strong><span>We match any price</span></div></div>
</div>

<!-- ══ AUTH GATE ══ -->
<div id="auth-gate">
  <div class="auth-modal">
    <div class="auth-top">
      <button class="auth-close" onclick="closeAuth()"><i class="fas fa-times"></i></button>
      <span class="icon">✈️</span>
      <h2>Welcome to travel.</h2>
      <p>Sign in or create a free account to start booking your dream trip</p>
    </div>
    <div class="auth-body">
      <div class="auth-tabs">
        <div class="auth-tab active" onclick="switchTab('login')">Sign In</div>
        <div class="auth-tab" onclick="switchTab('register')">Create Account</div>
      </div>
      <!-- Login -->
      <div class="auth-form active" id="tab-login">
        <div class="a-field"><label>Email Address</label><input type="email" placeholder="your@email.com"></div>
        <div class="a-field"><label>Password</label><input type="password" placeholder="••••••••"></div>
        <button class="btn-auth" onclick="proceedAfterAuth()">Sign In & Continue →</button>
        <div class="auth-divider">or <a href="login.php" style="color:var(--accent);text-decoration:none;font-weight:600">go to full login page</a></div>
      </div>
      <!-- Register -->
      <div class="auth-form" id="tab-register">
        <div class="a-field"><label>Full Name</label><input type="text" placeholder="Your full name"></div>
        <div class="a-field"><label>Email Address</label><input type="email" placeholder="your@email.com"></div>
        <div class="a-field"><label>Password</label><input type="password" placeholder="Create a password"></div>
        <button class="btn-auth" onclick="proceedAfterAuth()">Create Account & Continue →</button>
        <div class="auth-divider">or <a href="register.php" style="color:var(--accent);text-decoration:none;font-weight:600">go to full register page</a></div>
      </div>
    </div>
  </div>
</div>

<!-- ══ BOOKING APP ══ -->
<div id="booking-app">
  <!-- Progress -->
  <div class="progress-outer">
    <div class="steps-bar">
      <div class="fill-bar" id="fillBar"></div>
      <div class="step-item is-active" id="si-1"><div class="step-dot"><i class="fas fa-user"></i></div><span class="step-lbl">Personal</span></div>
      <div class="step-item" id="si-2"><div class="step-dot"><i class="fas fa-globe"></i></div><span class="step-lbl">Destination</span></div>
      <div class="step-item" id="si-3"><div class="step-dot"><i class="fas fa-suitcase"></i></div><span class="step-lbl">Trip</span></div>
      <div class="step-item" id="si-4"><div class="step-dot"><i class="fas fa-calendar"></i></div><span class="step-lbl">Dates</span></div>
      <div class="step-item" id="si-5"><div class="step-dot"><i class="fas fa-utensils"></i></div><span class="step-lbl">Preferences</span></div>
      <div class="step-item" id="si-6"><div class="step-dot"><i class="fas fa-check"></i></div><span class="step-lbl">Review</span></div>
    </div>
  </div>

  <div class="form-card">
    <form action="book_form.php" method="post" id="bookingForm">
      <input type="hidden" name="triptype"      id="f-triptype">
      <input type="hidden" name="accommodation" id="f-accom">
      <input type="hidden" name="meal"          id="f-meal">
      <input type="hidden" name="transport"     id="f-transport">
      <input type="hidden" name="budget"        id="f-budget" value="500">
      <input type="hidden" name="country_code"  id="f-cc" value="+92">

      <!-- ─ STEP 1 ─ -->
      <div class="form-sec show" id="fs-1">
        <div class="card-top">
          <div class="card-top-icon"><i class="fas fa-user-circle"></i></div>
          <div><h3>Personal Information</h3><p>Tell us a bit about yourself</p></div>
        </div>
        <div class="sec-body">
          <div class="grid-2">
            <div class="fld">
              <label>Full Name <span class="req">*</span></label>
              <div class="inp-wrap"><i class="fas fa-user ic"></i><input type="text" name="name" id="name" placeholder="Your full name"><i class="fas fa-check-circle inp-ck" id="ck-name"></i></div>
              <span class="f-err" id="er-name"><i class="fas fa-exclamation-triangle"></i> Name is required</span>
            </div>
            <div class="fld">
              <label>Email Address <span class="req">*</span></label>
              <div class="inp-wrap"><i class="fas fa-envelope ic"></i><input type="email" name="email" id="email" placeholder="your@email.com"><i class="fas fa-check-circle inp-ck" id="ck-email"></i></div>
              <span class="f-err" id="er-email"><i class="fas fa-exclamation-triangle"></i> Valid email required</span>
            </div>
          </div>
          <div class="sec-divider"><i class="fas fa-map-marker-alt"></i> Residence Information</div>
          <div class="grid-2" style="margin-bottom:2rem">
            <div class="fld">
              <label>Country <span class="req">*</span></label>
              <div class="inp-wrap"><i class="fas fa-flag ic"></i>
                <select id="res-country" name="res_country" onchange="onCountryChange()">
                  <option value="">— Select Country —</option>
                  <option value="Pakistan" data-code="+92" data-len="10">🇵🇰 Pakistan</option>
                  <option value="India" data-code="+91" data-len="10">🇮🇳 India</option>
                  <option value="USA" data-code="+1" data-len="10">🇺🇸 USA</option>
                  <option value="UK" data-code="+44" data-len="10">🇬🇧 UK</option>
                  <option value="UAE" data-code="+971" data-len="9">🇦🇪 UAE</option>
                  <option value="Saudi Arabia" data-code="+966" data-len="9">🇸🇦 Saudi Arabia</option>
                  <option value="Qatar" data-code="+974" data-len="8">🇶🇦 Qatar</option>
                  <option value="Kuwait" data-code="+965" data-len="8">🇰🇼 Kuwait</option>
                  <option value="Oman" data-code="+968" data-len="8">🇴🇲 Oman</option>
                  <option value="Bahrain" data-code="+973" data-len="8">🇧🇭 Bahrain</option>
                  <option value="Bangladesh" data-code="+880" data-len="10">🇧🇩 Bangladesh</option>
                  <option value="Turkey" data-code="+90" data-len="10">🇹🇷 Turkey</option>
                  <option value="Germany" data-code="+49" data-len="11">🇩🇪 Germany</option>
                  <option value="France" data-code="+33" data-len="9">🇫🇷 France</option>
                  <option value="China" data-code="+86" data-len="11">🇨🇳 China</option>
                  <option value="Australia" data-code="+61" data-len="9">🇦🇺 Australia</option>
                  <option value="Canada" data-code="+1" data-len="10">🇨🇦 Canada</option>
                  <option value="Other" data-code="" data-len="10">🌍 Other</option>
                </select>
              </div>
              <span class="f-err" id="er-country"><i class="fas fa-exclamation-triangle"></i> Please select your country</span>
            </div>
            <div class="fld">
              <label>City <span class="req">*</span></label>
              <div class="inp-wrap"><i class="fas fa-city ic"></i>
                <select id="res-city" name="res_city" onchange="onCityChange()" disabled>
                  <option value="">— Select Country First —</option>
                </select>
              </div>
              <span class="f-err" id="er-city"><i class="fas fa-exclamation-triangle"></i> Please select your city</span>
            </div>
          </div>
          <div class="grid-2">
            <div class="fld">
              <label>Phone Number <span class="req">*</span></label>
              <div class="phone-row">
                <select id="phone-code" disabled><option value="">Code</option></select>
                <div class="inp-wrap"><i class="fas fa-phone ic"></i><input type="number" name="phone" id="phone" placeholder="Phone number"><i class="fas fa-check-circle inp-ck" id="ck-phone"></i></div>
              </div>
              <span class="f-err" id="er-phone"><i class="fas fa-exclamation-triangle"></i> Invalid number</span>
            </div>
            <div class="fld" id="addr-group" style="display:none">
              <label>Street / Address <span class="req">*</span></label>
              <div class="inp-wrap"><i class="fas fa-home ic"></i><input type="text" name="address" id="address" placeholder="House, street, area"><i class="fas fa-check-circle inp-ck" id="ck-address"></i></div>
              <span class="f-err" id="er-address"><i class="fas fa-exclamation-triangle"></i> Address is required</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ─ STEP 2 ─ -->
      <div class="form-sec" id="fs-2">
        <div class="card-top">
          <div class="card-top-icon"><i class="fas fa-map-marked-alt"></i></div>
          <div><h3>Destination & Group</h3><p>Where are you headed?</p></div>
        </div>
        <div class="sec-body">
          <div class="grid-2">
            <div class="fld">
              <label>Destination <span class="req">*</span></label>
              <div class="inp-wrap"><i class="fas fa-map-marker-alt ic"></i><input type="text" name="location" id="location" placeholder="City, country or region"><i class="fas fa-check-circle inp-ck" id="ck-location"></i></div>
              <span class="f-err" id="er-location"><i class="fas fa-exclamation-triangle"></i> Destination is required</span>
            </div>
            <div class="fld">
              <label>Number of Travelers <span class="req">*</span></label>
              <div class="inp-wrap"><i class="fas fa-users ic"></i><input type="number" name="guests" id="guests" placeholder="How many people?" min="1" max="50"><i class="fas fa-check-circle inp-ck" id="ck-guests"></i></div>
              <span class="f-err" id="er-guests"><i class="fas fa-exclamation-triangle"></i> Please enter number of guests</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ─ STEP 3 ─ -->
      <div class="form-sec" id="fs-3">
        <div class="card-top">
          <div class="card-top-icon"><i class="fas fa-suitcase-rolling"></i></div>
          <div><h3>Trip Type & Accommodation</h3><p>What kind of trip is this?</p></div>
        </div>
        <div class="sec-body">
          <div class="fld" style="margin-bottom:3rem">
            <label style="font-size:1.4rem;margin-bottom:1.6rem">Trip Purpose <span class="req">*</span></label>
            <div class="choice-grid col-4">
              <div class="c-card" onclick="pick(this,'trip','Adventure')"><i class="fas fa-mountain"></i><div class="c-name">Adventure</div><div class="c-sub">Hiking & thrills</div></div>
              <div class="c-card" onclick="pick(this,'trip','Honeymoon')"><i class="fas fa-heart"></i><div class="c-name">Honeymoon</div><div class="c-sub">Romantic getaway</div></div>
              <div class="c-card" onclick="pick(this,'trip','Family')"><i class="fas fa-child"></i><div class="c-name">Family</div><div class="c-sub">Fun for all ages</div></div>
              <div class="c-card" onclick="pick(this,'trip','Business')"><i class="fas fa-briefcase"></i><div class="c-name">Business</div><div class="c-sub">Work & meetings</div></div>
            </div>
            <span class="f-err" id="er-trip" style="margin-top:1rem"><i class="fas fa-exclamation-triangle"></i> Please select trip type</span>
          </div>
          <div class="fld">
            <label style="font-size:1.4rem;margin-bottom:1.6rem">Accommodation <span class="req">*</span></label>
            <div class="choice-grid col-3">
              <div class="c-card" onclick="pick(this,'accom','Hotel')"><i class="fas fa-hotel"></i><div class="c-name">Hotel</div><div class="c-sub">Premium comfort</div></div>
              <div class="c-card" onclick="pick(this,'accom','Resort')"><i class="fas fa-umbrella-beach"></i><div class="c-name">Resort</div><div class="c-sub">Luxury & pool</div></div>
              <div class="c-card" onclick="pick(this,'accom','Hostel')"><i class="fas fa-bed"></i><div class="c-name">Hostel</div><div class="c-sub">Budget friendly</div></div>
            </div>
            <span class="f-err" id="er-accom" style="margin-top:1rem"><i class="fas fa-exclamation-triangle"></i> Please select accommodation type</span>
          </div>
        </div>
      </div>

      <!-- ─ STEP 4 ─ -->
      <div class="form-sec" id="fs-4">
        <div class="card-top">
          <div class="card-top-icon"><i class="fas fa-calendar-alt"></i></div>
          <div><h3>Travel Dates & Budget</h3><p>When are you traveling?</p></div>
        </div>
        <div class="sec-body">
          <div class="grid-2" style="margin-bottom:1.6rem">
            <div class="fld">
              <label>Arrival Date <span class="req">*</span></label>
              <div class="inp-wrap"><i class="fas fa-plane-arrival ic"></i><input type="date" name="arrivals" id="arrivals"></div>
              <span class="f-err" id="er-arrival"><i class="fas fa-exclamation-triangle"></i> Select today or a future date</span>
            </div>
            <div class="fld">
              <label>Departure Date <span class="req">*</span></label>
              <div class="inp-wrap"><i class="fas fa-plane-departure ic"></i><input type="date" name="leaving" id="leaving"></div>
              <span class="f-err" id="er-leaving"><i class="fas fa-exclamation-triangle"></i> Must be after arrival date</span>
            </div>
          </div>
          <div class="nights-pill" id="nights-pill"><i class="fas fa-moon"></i> <strong id="nights-num">0</strong>&nbsp;Nights Trip</div>
          <div class="fld" style="margin-top:3.2rem">
            <label style="font-size:1.4rem;margin-bottom:1.6rem">Budget Per Person</label>
            <div class="budget-big">$<span id="budget-val">500</span></div>
            <div class="budget-note">per person · per night</div>
            <input type="range" class="slider" id="budgetSlider" min="100" max="10000" step="100" value="500" oninput="updateBudget(this.value)">
            <div class="slider-labels"><span>$100</span><span>$10,000</span></div>
            <div class="budget-pills">
              <span class="b-pill" onclick="setBudget(500,this)">Economy · $500</span>
              <span class="b-pill" onclick="setBudget(1500,this)">Standard · $1,500</span>
              <span class="b-pill" onclick="setBudget(3000,this)">Comfort · $3,000</span>
              <span class="b-pill" onclick="setBudget(6000,this)">Luxury · $6,000</span>
              <span class="b-pill" onclick="setBudget(10000,this)">Ultra · $10,000</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ─ STEP 5 ─ -->
      <div class="form-sec" id="fs-5">
        <div class="card-top">
          <div class="card-top-icon"><i class="fas fa-concierge-bell"></i></div>
          <div><h3>Meal & Transport</h3><p>Customize your experience</p></div>
        </div>
        <div class="sec-body">
          <div class="fld" style="margin-bottom:3rem">
            <label style="font-size:1.4rem;margin-bottom:1.6rem">Meal Preference</label>
            <div class="choice-grid col-6">
              <div class="c-card" onclick="pick(this,'meal','All Inclusive')"><i class="fas fa-utensils"></i><div class="c-name">All Inclusive</div></div>
              <div class="c-card" onclick="pick(this,'meal','Breakfast Only')"><i class="fas fa-coffee"></i><div class="c-name">Breakfast Only</div></div>
              <div class="c-card" onclick="pick(this,'meal','Half Board')"><i class="fas fa-sun"></i><div class="c-name">Half Board</div></div>
              <div class="c-card" onclick="pick(this,'meal','Full Board')"><i class="fas fa-drumstick-bite"></i><div class="c-name">Full Board</div></div>
              <div class="c-card" onclick="pick(this,'meal','Self Catering')"><i class="fas fa-shopping-basket"></i><div class="c-name">Self Catering</div></div>
              <div class="c-card" onclick="pick(this,'meal','No Preference')"><i class="fas fa-times-circle"></i><div class="c-name">No Preference</div></div>
            </div>
          </div>
          <div class="fld" style="margin-bottom:3rem">
            <label style="font-size:1.4rem;margin-bottom:1.6rem">Transport Preference</label>
            <div class="choice-grid col-6">
              <div class="c-card" onclick="pick(this,'transport','Flight')"><i class="fas fa-plane"></i><div class="c-name">Flight</div></div>
              <div class="c-card" onclick="pick(this,'transport','Train')"><i class="fas fa-train"></i><div class="c-name">Train</div></div>
              <div class="c-card" onclick="pick(this,'transport','Bus')"><i class="fas fa-bus"></i><div class="c-name">Bus</div></div>
              <div class="c-card" onclick="pick(this,'transport','Car Rental')"><i class="fas fa-car"></i><div class="c-name">Car Rental</div></div>
              <div class="c-card" onclick="pick(this,'transport','Private Transfer')"><i class="fas fa-shuttle-van"></i><div class="c-name">Private Transfer</div></div>
              <div class="c-card" onclick="pick(this,'transport','No Preference')"><i class="fas fa-times-circle"></i><div class="c-name">No Preference</div></div>
            </div>
          </div>
          <div class="fld">
            <label>Special Requests</label>
            <div class="inp-wrap"><i class="fas fa-comment-alt ic"></i><textarea name="special_requests" id="special_requests" placeholder="Wheelchair access, vegetarian meals, early check-in, anniversary decoration..."></textarea></div>
          </div>
        </div>
      </div>

      <!-- ─ STEP 6 ─ -->
      <div class="form-sec" id="fs-6">
        <div class="card-top">
          <div class="card-top-icon"><i class="fas fa-clipboard-check"></i></div>
          <div><h3>Review & Confirm</h3><p>Everything look good?</p></div>
        </div>
        <div class="sec-body">
          <div class="sum-price">
            <div class="label">Estimated Total Cost</div>
            <div class="amount">$<span id="s-total">0</span></div>
            <div class="breakdown"><span id="s-g2">0</span> guests × <span id="s-n2">0</span> nights × $<span id="s-b2">0</span>/person/night</div>
          </div>
          <div class="trust-row">
            <div class="trust-chip"><i class="fas fa-shield-alt"></i> Secure Booking</div>
            <div class="trust-chip"><i class="fas fa-lock"></i> Data Protected</div>
            <div class="trust-chip"><i class="fas fa-check-circle"></i> Instant Confirmation</div>
          </div>
          <div class="sum-grid">
            <div class="sum-box">
              <h4><i class="fas fa-user" style="color:var(--accent);margin-right:0.8rem"></i>Personal</h4>
              <div class="s-row"><span class="lbl">Name</span><span class="val" id="s-name">—</span></div>
              <div class="s-row"><span class="lbl">Email</span><span class="val" id="s-email">—</span></div>
              <div class="s-row"><span class="lbl">Phone</span><span class="val" id="s-phone">—</span></div>
              <div class="s-row"><span class="lbl">Country</span><span class="val" id="s-country">—</span></div>
              <div class="s-row"><span class="lbl">City</span><span class="val" id="s-city">—</span></div>
              <div class="s-row"><span class="lbl">Address</span><span class="val" id="s-address">—</span></div>
            </div>
            <div class="sum-box">
              <h4><i class="fas fa-globe" style="color:var(--accent);margin-right:0.8rem"></i>Trip</h4>
              <div class="s-row"><span class="lbl">Destination</span><span class="val" id="s-location">—</span></div>
              <div class="s-row"><span class="lbl">Guests</span><span class="val" id="s-guests">—</span></div>
              <div class="s-row"><span class="lbl">Trip Type</span><span class="val" id="s-trip">—</span></div>
              <div class="s-row"><span class="lbl">Accommodation</span><span class="val" id="s-accom">—</span></div>
            </div>
            <div class="sum-box">
              <h4><i class="fas fa-calendar" style="color:var(--accent);margin-right:0.8rem"></i>Dates</h4>
              <div class="s-row"><span class="lbl">Arrival</span><span class="val" id="s-arrival">—</span></div>
              <div class="s-row"><span class="lbl">Departure</span><span class="val" id="s-leaving">—</span></div>
              <div class="s-row"><span class="lbl">Duration</span><span class="val" id="s-nights">—</span></div>
              <div class="s-row"><span class="lbl">Budget</span><span class="val" id="s-budget">—</span></div>
            </div>
            <div class="sum-box">
              <h4><i class="fas fa-concierge-bell" style="color:var(--accent);margin-right:0.8rem"></i>Preferences</h4>
              <div class="s-row"><span class="lbl">Meal</span><span class="val" id="s-meal">—</span></div>
              <div class="s-row"><span class="lbl">Transport</span><span class="val" id="s-transport">—</span></div>
              <div class="s-row"><span class="lbl">Requests</span><span class="val" id="s-requests">—</span></div>
            </div>
          </div>
        </div>
      </div>

      <!-- NAV -->
      <div class="form-nav">
        <button type="button" class="btn-back ghost" id="backBtn" onclick="goStep(-1)"><i class="fas fa-arrow-left"></i> Back</button>
        <button type="button" class="btn-next" id="nextBtn" onclick="goStep(1)">Continue <i class="fas fa-arrow-right"></i></button>
        <button type="submit" class="btn-confirm" id="submitBtn" name="send"><i class="fas fa-check"></i> Confirm Booking</button>
      </div>
    </form>
  </div>
</div>

<!-- ══ SUCCESS SCREEN ══ -->
<div id="success-screen">
  <div class="success-inner">
    <div class="success-check"><i class="fas fa-check"></i></div>
    <h2>Booking Confirmed</h2>
    <p class="sub">Your trip has been successfully booked. A confirmation email has been sent to your inbox with all the details.</p>
    <div class="success-ref">
      <div class="ref-label">Booking Reference</div>
      <div class="ref-num" id="ref-num">TRV-000000</div>
    </div>
    <div class="success-actions">
      <a href="home.php" class="btn-home"><i class="fas fa-home"></i> Back to Home</a>
      <button class="btn-new" onclick="location.reload()">Book Another Trip</button>
    </div>
  </div>
</div>

<script>
// ── STATE ──
let cur = 1;
const TOTAL = 6;
const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
const today = new Date().toISOString().split('T')[0];
document.getElementById('arrivals').setAttribute('min', today);
document.getElementById('leaving').setAttribute('min', today);

// ── AUTH GATE ──
function handleBookNow() {
  if(isLoggedIn) {
    showBookingForm();
  } else {
    document.getElementById('auth-gate').classList.add('show');
    document.body.style.overflow = 'hidden';
  }
}
function closeAuth() {
  document.getElementById('auth-gate').classList.remove('show');
  document.body.style.overflow = '';
}
function switchTab(tab) {
  document.querySelectorAll('.auth-tab').forEach((t,i) => t.classList.toggle('active', (i===0&&tab==='login')||(i===1&&tab==='register')));
  document.getElementById('tab-login').classList.toggle('active', tab==='login');
  document.getElementById('tab-register').classList.toggle('active', tab==='register');
}
function proceedAfterAuth() {
  closeAuth();
  showBookingForm();
}
function showBookingForm() {
  document.getElementById('heroSection').style.display = 'none';
  document.querySelector('.features').style.display = 'none';
  document.getElementById('booking-app').classList.add('show');
  window.scrollTo({top:0, behavior:'smooth'});
}
document.getElementById('auth-gate').addEventListener('click', function(e){
  if(e.target === this) closeAuth();
});

// ── COUNTRY DATA ──
const countryData = {
  'Pakistan':     {code:'+92', len:10, cities:['Karachi','Lahore','Islamabad','Faisalabad','Rawalpindi','Gujranwala','Multan','Peshawar','Quetta','Hyderabad','Sialkot','Bahawalpur','Sargodha','Sukkur','Larkana','Sahiwal','Abbottabad','Mardan','Gujrat','Other']},
  'India':        {code:'+91', len:10, cities:['Mumbai','Delhi','Bengaluru','Hyderabad','Ahmedabad','Chennai','Kolkata','Surat','Pune','Jaipur','Lucknow','Nagpur','Indore','Other']},
  'USA':          {code:'+1',  len:10, cities:['New York','Los Angeles','Chicago','Houston','Phoenix','Philadelphia','San Antonio','San Diego','Dallas','San Jose','Austin','Other']},
  'UK':           {code:'+44', len:10, cities:['London','Birmingham','Manchester','Glasgow','Liverpool','Leeds','Sheffield','Edinburgh','Bristol','Other']},
  'UAE':          {code:'+971',len:9,  cities:['Dubai','Abu Dhabi','Sharjah','Ajman','Ras Al Khaimah','Fujairah','Al Ain','Other']},
  'Saudi Arabia': {code:'+966',len:9,  cities:['Riyadh','Jeddah','Mecca','Medina','Dammam','Taif','Tabuk','Other']},
  'Qatar':        {code:'+974',len:8,  cities:['Doha','Al Rayyan','Al Wakrah','Al Khor','Other']},
  'Kuwait':       {code:'+965',len:8,  cities:['Kuwait City','Hawalli','Salmiya','Farwaniya','Other']},
  'Oman':         {code:'+968',len:8,  cities:['Muscat','Salalah','Sohar','Nizwa','Other']},
  'Bahrain':      {code:'+973',len:8,  cities:['Manama','Muharraq','Riffa','Other']},
  'Bangladesh':   {code:'+880',len:10, cities:['Dhaka','Chittagong','Sylhet','Rajshahi','Khulna','Other']},
  'Turkey':       {code:'+90', len:10, cities:['Istanbul','Ankara','Izmir','Bursa','Adana','Gaziantep','Other']},
  'Germany':      {code:'+49', len:11, cities:['Berlin','Hamburg','Munich','Cologne','Frankfurt','Stuttgart','Other']},
  'France':       {code:'+33', len:9,  cities:['Paris','Marseille','Lyon','Toulouse','Nice','Nantes','Other']},
  'China':        {code:'+86', len:11, cities:['Beijing','Shanghai','Guangzhou','Shenzhen','Chengdu','Wuhan','Other']},
  'Australia':    {code:'+61', len:9,  cities:['Sydney','Melbourne','Brisbane','Perth','Adelaide','Canberra','Other']},
  'Canada':       {code:'+1',  len:10, cities:['Toronto','Montreal','Vancouver','Calgary','Edmonton','Ottawa','Other']},
  'Other':        {code:'',    len:10, cities:['Other']}
};

function onCountryChange() {
  const sel = document.getElementById('res-country');
  const country = sel.value;
  const cityEl = document.getElementById('res-city');
  const codeEl = document.getElementById('phone-code');
  if(!country) { cityEl.innerHTML='<option value="">— Select Country First —</option>'; cityEl.disabled=true; codeEl.innerHTML='<option>Code</option>'; codeEl.disabled=true; return; }
  const d = countryData[country];
  codeEl.innerHTML = `<option value="${d.code}">${d.code}</option>`;
  document.getElementById('f-cc').value = d.code;
  codeEl.disabled = false;
  cityEl.innerHTML = '<option value="">— Select City —</option>';
  d.cities.forEach(c => cityEl.innerHTML += `<option value="${c}">${c}</option>`);
  cityEl.disabled = false;
  setErr('er-country', false);
}

function onCityChange() {
  const city = document.getElementById('res-city').value;
  const ag = document.getElementById('addr-group');
  if(city) {
    ag.style.display = 'flex'; ag.style.flexDirection = 'column'; ag.style.gap = '0.8rem';
    document.getElementById('address').placeholder = `Street, area in ${city}...`;
    setErr('er-city', false);
  } else { ag.style.display = 'none'; }
}

document.getElementById('phone').addEventListener('input', function(){
  const country = document.getElementById('res-country').value;
  if(!country) return;
  const reqLen = countryData[country].len;
  const ok = this.value.length === reqLen;
  setOk('ck-phone', ok);
  if(this.value.length > 0 && !ok) {
    document.getElementById('er-phone').innerHTML = `<i class="fas fa-exclamation-triangle"></i> Must be ${reqLen} digits for ${country}`;
    setErr('er-phone', true);
  } else { setErr('er-phone', false); }
});

// ── STEPS ──
function goStep(dir) {
  if(dir === 1 && !validateStep(cur)) return;
  document.getElementById('fs-'+cur).classList.remove('show');
  const si = document.getElementById('si-'+cur);
  si.classList.remove('is-active');
  if(dir === 1) si.classList.add('is-done');
  else si.classList.remove('is-done');
  cur += dir;
  document.getElementById('fs-'+cur).classList.add('show');
  document.getElementById('si-'+cur).classList.add('is-active');
  document.getElementById('backBtn').classList.toggle('ghost', cur === 1);
  document.getElementById('nextBtn').classList.toggle('hide', cur === TOTAL);
  document.getElementById('submitBtn').classList.toggle('show', cur === TOTAL);
  const pct = ((cur-1)/(TOTAL-1))*100;
  document.getElementById('fillBar').style.width = pct+'%';
  if(cur === TOTAL) fillSummary();
  document.getElementById('booking-app').scrollIntoView({behavior:'smooth', block:'start'});
}

function validateStep(s) {
  let ok = true;
  if(s === 1) {
    const n = document.getElementById('name').value.trim();
    const e = document.getElementById('email').value.trim();
    const country = document.getElementById('res-country').value;
    const city = document.getElementById('res-city').value;
    const p = document.getElementById('phone').value.trim();
    const addr = document.getElementById('address').value.trim();
    const reqLen = country ? countryData[country].len : 10;
    setErr('er-name', !n); setOk('ck-name', !!n);
    setErr('er-email', !e||!e.includes('@')); setOk('ck-email', !!e&&e.includes('@'));
    setErr('er-country', !country);
    setErr('er-city', !city);
    const phoneOk = p.length === reqLen;
    setErr('er-phone', !phoneOk); setOk('ck-phone', phoneOk);
    setErr('er-address', !addr); setOk('ck-address', !!addr);
    ok = n && e && e.includes('@') && country && city && phoneOk && addr;
  }
  if(s === 2) {
    const l = document.getElementById('location').value.trim();
    const g = parseInt(document.getElementById('guests').value);
    setErr('er-location', !l); setOk('ck-location', !!l);
    setErr('er-guests', !g||g<1); setOk('ck-guests', g>=1);
    ok = l && g >= 1;
  }
  if(s === 3) {
    const tt = document.getElementById('f-triptype').value;
    const ac = document.getElementById('f-accom').value;
    setErr('er-trip', !tt); setErr('er-accom', !ac);
    ok = tt && ac;
  }
  if(s === 4) {
    const arr = document.getElementById('arrivals').value;
    const lev = document.getElementById('leaving').value;
    const arrOk = arr && arr >= today;
    const levOk = lev && lev > arr;
    setErr('er-arrival', !arrOk); setErr('er-leaving', !levOk);
    ok = arrOk && levOk;
  }
  return !!ok;
}

function setErr(id, show) { const el=document.getElementById(id); if(el) el.classList.toggle('show',show); }
function setOk(id, valid) { const el=document.getElementById(id); if(!el)return; el.className='fas fa-check-circle inp-ck'+(valid?' ok':''); }

// ── SELECTIONS ──
const pickGroups = { trip:'f-triptype', accom:'f-accom', meal:'f-meal', transport:'f-transport' };
const pickErrIds = { trip:'er-trip', accom:'er-accom' };
function pick(el, group, val) {
  el.closest('.choice-grid').querySelectorAll('.c-card').forEach(c=>c.classList.remove('chosen'));
  el.classList.add('chosen');
  document.getElementById(pickGroups[group]).value = val;
  if(pickErrIds[group]) setErr(pickErrIds[group], false);
}

// ── BUDGET ──
function updateBudget(val) {
  document.getElementById('budget-val').textContent = parseInt(val).toLocaleString();
  document.getElementById('f-budget').value = val;
  const pct = ((val-100)/(10000-100))*100;
  document.getElementById('budgetSlider').style.background = `linear-gradient(90deg,var(--accent) ${pct}%,var(--border) ${pct}%)`;
  document.querySelectorAll('.b-pill').forEach(t=>t.classList.remove('on'));
}
function setBudget(val, el) {
  document.getElementById('budgetSlider').value = val;
  updateBudget(val);
  document.querySelectorAll('.b-pill').forEach(t=>t.classList.remove('on'));
  el.classList.add('on');
}

// ── DATES ──
document.getElementById('arrivals').addEventListener('change', function(){ document.getElementById('leaving').setAttribute('min',this.value); calcNights(); });
document.getElementById('leaving').addEventListener('change', calcNights);
function calcNights() {
  const arr=document.getElementById('arrivals').value, lev=document.getElementById('leaving').value;
  if(arr&&lev&&lev>arr){ const n=Math.round((new Date(lev)-new Date(arr))/86400000); document.getElementById('nights-num').textContent=n; document.getElementById('nights-pill').classList.add('show'); }
  else { document.getElementById('nights-pill').classList.remove('show'); }
}

// ── SUMMARY ──
function fillSummary() {
  const arr=document.getElementById('arrivals').value, lev=document.getElementById('leaving').value;
  const nights=arr&&lev?Math.round((new Date(lev)-new Date(arr))/86400000):0;
  const guests=parseInt(document.getElementById('guests').value)||0;
  const budget=parseInt(document.getElementById('f-budget').value)||0;
  const code=document.getElementById('f-cc').value;
  document.getElementById('s-name').textContent    = document.getElementById('name').value;
  document.getElementById('s-email').textContent   = document.getElementById('email').value;
  document.getElementById('s-phone').textContent   = code+' '+document.getElementById('phone').value;
  document.getElementById('s-country').textContent = document.getElementById('res-country').value;
  document.getElementById('s-city').textContent    = document.getElementById('res-city').value;
  document.getElementById('s-address').textContent = document.getElementById('address').value;
  document.getElementById('s-location').textContent= document.getElementById('location').value;
  document.getElementById('s-guests').textContent  = guests+' person(s)';
  document.getElementById('s-trip').textContent    = document.getElementById('f-triptype').value||'Not selected';
  document.getElementById('s-accom').textContent   = document.getElementById('f-accom').value||'Not selected';
  document.getElementById('s-arrival').textContent = arr;
  document.getElementById('s-leaving').textContent = lev;
  document.getElementById('s-nights').textContent  = nights+' night(s)';
  document.getElementById('s-budget').textContent  = '$'+budget.toLocaleString()+'/person';
  document.getElementById('s-meal').textContent    = document.getElementById('f-meal').value||'No preference';
  document.getElementById('s-transport').textContent = document.getElementById('f-transport').value||'No preference';
  document.getElementById('s-requests').textContent = document.getElementById('special_requests').value.trim()||'None';
  document.getElementById('s-g2').textContent = guests;
  document.getElementById('s-n2').textContent = nights;
  document.getElementById('s-b2').textContent = budget.toLocaleString();
  document.getElementById('s-total').textContent = (guests*nights*budget).toLocaleString();
}

// ── SUCCESS ──
const urlP = new URLSearchParams(window.location.search);
if(urlP.get('booked')==='true') {
  showSuccess();
  window.history.replaceState({},document.title,window.location.pathname);
}
function showSuccess() {
  const ref = 'TRV-'+Math.floor(100000+Math.random()*900000);
  document.getElementById('ref-num').textContent = ref;
  document.getElementById('success-screen').classList.add('show');
  document.body.style.overflow = 'hidden';
}

// If already logged in and user clicks book, go straight to form
<?php if(isset($_SESSION['user_id'])): ?>
// Logged in — button goes to form directly (already handled by handleBookNow)
<?php endif; ?>
</script>
</body>
</html>