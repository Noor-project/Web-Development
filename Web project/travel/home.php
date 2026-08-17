<?php
session_start();
$connection = mysqli_connect('localhost','root','','booking_db');
$total_users    = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as t FROM users"))['t'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as t FROM bookings"))['t'];
$total_packages = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as t FROM packages WHERE status='active'"))['t'];
$featured = mysqli_query($connection, "SELECT * FROM packages WHERE status='active' ORDER BY id ASC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home — travel.</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
<style>
:root{
  --ink:#0f0f0f;--cream:#faf8f5;--stone:#f2ede8;--muted:#888880;
  --accent:#c8974a;--accent2:#8b5e2a;--green:#2d6a4f;--green-l:#d8f3dc;
  --border:#e8e2da;--shadow:0 4px 32px rgba(0,0,0,0.07);
}
*{box-sizing:border-box;margin:0;padding:0}
html{font-size:62.5%;scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);font-size:1.5rem;line-height:1.6;overflow-x:hidden}
.site-header{position:fixed;top:0;left:0;right:0;z-index:900;display:flex;align-items:center;justify-content:space-between;padding:1.8rem 6rem;background:rgba(250,248,245,0.92);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);transition:.3s}
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
.hero-slider-wrap{position:relative;height:100vh;min-height:64rem}
.swiper.hero-swiper{height:100%}
.hero-slide{height:100%;display:flex;align-items:center;background-size:cover!important;background-position:center!important;position:relative}
.hero-slide::before{content:'';position:absolute;inset:0;background:linear-gradient(120deg,rgba(10,10,10,0.75) 0%,rgba(139,94,42,0.3) 60%,rgba(10,10,10,0.5) 100%)}
.slide-content{position:relative;z-index:2;padding:0 6rem;max-width:80rem}
.slide-tag{display:inline-flex;align-items:center;gap:0.8rem;background:rgba(200,151,74,0.15);border:1px solid rgba(200,151,74,0.4);border-radius:5rem;padding:0.7rem 1.8rem;font-size:1.2rem;color:var(--accent);letter-spacing:1.5px;text-transform:uppercase;font-weight:600;margin-bottom:2.4rem;animation:fadeUp .7s .2s both}
.slide-content h1{font-family:'Playfair Display',serif;font-size:clamp(5rem,9vw,9rem);color:#fff;font-weight:700;line-height:1.02;letter-spacing:-2px;margin-bottom:2rem;animation:fadeUp .7s .35s both}
.slide-content h1 em{font-style:italic;color:var(--accent)}
.slide-content p{font-size:1.8rem;color:rgba(255,255,255,0.6);max-width:52rem;font-weight:300;line-height:1.75;margin-bottom:3.6rem;animation:fadeUp .7s .5s both}
.slide-btns{display:flex;gap:1.6rem;flex-wrap:wrap;animation:fadeUp .7s .65s both}
.btn-primary{display:inline-flex;align-items:center;gap:1rem;padding:1.6rem 4rem;font-size:1.6rem;font-weight:600;background:var(--accent);color:#fff;border:none;border-radius:1rem;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;transition:.25s}
.btn-primary:hover{background:var(--accent2);transform:translateY(-2px);box-shadow:0 8px 24px rgba(200,151,74,0.4)}
.btn-outline{display:inline-flex;align-items:center;gap:1rem;padding:1.6rem 3.2rem;font-size:1.6rem;font-weight:600;background:rgba(255,255,255,0.08);color:#fff;border:1.5px solid rgba(255,255,255,0.3);border-radius:1rem;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;transition:.25s;backdrop-filter:blur(4px)}
.btn-outline:hover{background:rgba(255,255,255,0.18);border-color:rgba(255,255,255,0.6)}
.swiper-button-next,.swiper-button-prev{color:var(--accent)!important;width:5rem!important;height:5rem!important;background:rgba(255,255,255,0.08);border-radius:50%;backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,0.15);transition:.2s}
.swiper-button-next:hover,.swiper-button-prev:hover{background:var(--accent)}
.swiper-button-next::after,.swiper-button-prev::after{font-size:1.6rem!important;font-weight:700}
.swiper-pagination-bullet{background:#fff!important;opacity:0.4}
.swiper-pagination-bullet-active{opacity:1!important;background:var(--accent)!important;width:2.4rem!important;border-radius:5rem!important}
.hero-scroll{position:absolute;bottom:4rem;left:50%;transform:translateX(-50%);z-index:10;display:flex;flex-direction:column;align-items:center;gap:0.8rem;color:rgba(255,255,255,0.4);font-size:1.2rem;letter-spacing:1px;text-transform:uppercase;animation:bounce 2s infinite}
.hero-scroll i{font-size:2rem}
@keyframes bounce{0%,100%{transform:translateX(-50%) translateY(0)}50%{transform:translateX(-50%) translateY(8px)}}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
.stats-strip{background:var(--ink);display:flex;justify-content:center;flex-wrap:wrap}
.stat-item{flex:1;min-width:16rem;max-width:26rem;text-align:center;padding:3.2rem 2rem;border-right:1px solid rgba(255,255,255,0.07)}
.stat-item:last-child{border-right:none}
.stat-item .num{font-family:'Playfair Display',serif;font-size:4.4rem;color:var(--accent);font-weight:700;line-height:1}
.stat-item .lbl{font-size:1.3rem;color:rgba(255,255,255,0.4);margin-top:0.6rem}
.services-section{padding:9rem 6rem;background:#fff}
.sec-header{text-align:center;margin-bottom:6rem}
.eyebrow{font-size:1.2rem;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:2px;display:block;margin-bottom:1.2rem}
.sec-header h2{font-family:'Playfair Display',serif;font-size:clamp(3rem,5vw,4.4rem);color:var(--ink);line-height:1.2;letter-spacing:-0.5px}
.sec-header h2 em{font-style:italic;color:var(--accent)}
.sec-header p{font-size:1.6rem;color:var(--muted);margin-top:1.2rem;max-width:50rem;margin-left:auto;margin-right:auto;font-weight:300}
.services-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:2rem;max-width:130rem;margin:0 auto}
.srv-card{text-align:center;padding:3.6rem 2rem;border-radius:1.6rem;border:1px solid var(--border);background:var(--cream);transition:.25s;cursor:default}
.srv-card:hover{background:#fff;box-shadow:var(--shadow);transform:translateY(-6px);border-color:rgba(200,151,74,0.3)}
.srv-icon{width:7rem;height:7rem;border-radius:1.6rem;background:rgba(200,151,74,0.1);border:1px solid rgba(200,151,74,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 2rem;transition:.25s}
.srv-card:hover .srv-icon{background:var(--accent);border-color:var(--accent)}
.srv-icon i{font-size:2.8rem;color:var(--accent);transition:.25s}
.srv-card:hover .srv-icon i{color:#fff}
.srv-card h3{font-family:'Playfair Display',serif;font-size:1.7rem;color:var(--ink);margin-bottom:0.6rem}
.srv-card p{font-size:1.3rem;color:var(--muted);font-weight:300;line-height:1.6}
.how-it-works{padding:9rem 6rem;background:var(--ink);position:relative;overflow:hidden}
.how-it-works::before{content:'';position:absolute;top:-20rem;right:-20rem;width:60rem;height:60rem;border-radius:50%;background:radial-gradient(circle,rgba(200,151,74,0.08) 0%,transparent 70%)}
.hiw-inner{max-width:130rem;margin:0 auto}
.hiw-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:3rem;margin-top:6rem}
.hiw-card{text-align:center;padding:3rem 2rem;position:relative}
.hiw-card::after{content:'→';position:absolute;right:-1.5rem;top:3rem;font-size:2.4rem;color:rgba(200,151,74,0.3);font-weight:700}
.hiw-card:last-child::after{display:none}
.hiw-num{width:6rem;height:6rem;border-radius:50%;background:rgba(200,151,74,0.1);border:2px solid rgba(200,151,74,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 2rem;font-family:'Playfair Display',serif;font-size:2.4rem;color:var(--accent);font-weight:700}
.hiw-card h3{font-family:'Playfair Display',serif;font-size:2rem;color:#fff;margin-bottom:1rem}
.hiw-card p{font-size:1.4rem;color:rgba(255,255,255,0.4);font-weight:300;line-height:1.7}
.sec-header.light .eyebrow{color:var(--accent)}
.sec-header.light h2{color:#fff}
.sec-header.light p{color:rgba(255,255,255,0.4)}
.home-packages{padding:9rem 6rem;background:var(--stone)}
.pkg-inner{max-width:130rem;margin:0 auto}
.pkg-row{display:grid;grid-template-columns:repeat(3,1fr);gap:2.8rem;margin-bottom:5rem}
.pkg-card{background:#fff;border-radius:2rem;border:1px solid var(--border);overflow:hidden;transition:.3s}
.pkg-card:hover{transform:translateY(-8px);box-shadow:0 20px 56px rgba(0,0,0,0.1)}
.pkg-img{position:relative;height:26rem;overflow:hidden}
.pkg-img img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s}
.pkg-card:hover .pkg-img img{transform:scale(1.06)}
.pkg-badge{position:absolute;top:1.6rem;left:1.6rem;background:var(--accent);color:#fff;font-size:1.15rem;font-weight:700;padding:0.5rem 1.4rem;border-radius:5rem;letter-spacing:0.5px}
.pkg-badge.hot{background:#e74c3c}
.pkg-badge.new{background:var(--green)}
.pkg-img-ov{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(15,15,15,0.6));padding:1.8rem;display:flex;align-items:center;gap:0.6rem}
.pkg-rating{display:flex;align-items:center;gap:0.5rem;font-size:1.3rem;color:#fff;font-weight:600}
.pkg-rating i{color:#f1c40f;font-size:1.2rem}
.pkg-rev{font-size:1.2rem;color:rgba(255,255,255,0.6)}
.pkg-body{padding:2.4rem 2.4rem 0}
.pkg-meta{display:flex;gap:1.4rem;margin-bottom:1.2rem;flex-wrap:wrap}
.m-tag{display:flex;align-items:center;gap:0.5rem;font-size:1.25rem;color:var(--muted)}
.m-tag i{color:var(--accent);font-size:1.2rem}
.pkg-body h3{font-family:'Playfair Display',serif;font-size:2.2rem;color:var(--ink);margin-bottom:0.8rem;line-height:1.25}
.pkg-body p{font-size:1.4rem;color:var(--muted);line-height:1.75;font-weight:300;margin-bottom:1.8rem}
.pkg-foot{padding:1.8rem 2.4rem;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.pkg-price .from{font-size:1.2rem;color:var(--muted);display:block}
.pkg-price .amt{font-family:'Playfair Display',serif;font-size:2.8rem;color:var(--ink);font-weight:700;line-height:1}
.pkg-price .amt span{color:var(--accent)}
.pkg-price .per{font-size:1.2rem;color:var(--muted)}
.btn-book{display:flex;align-items:center;gap:0.8rem;padding:1.1rem 2.2rem;font-size:1.4rem;font-weight:600;background:var(--ink);color:#fff;border:none;border-radius:0.8rem;cursor:pointer;text-decoration:none;transition:.2s;font-family:'DM Sans',sans-serif}
.btn-book:hover{background:var(--accent)}
.load-more-wrap{text-align:center}
.btn-more{display:inline-flex;align-items:center;gap:1rem;padding:1.5rem 4rem;font-size:1.5rem;font-weight:600;background:#fff;color:var(--ink);border:2px solid var(--border);border-radius:0.8rem;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;transition:.2s}
.btn-more:hover{background:var(--ink);color:#fff;border-color:var(--ink)}
.destinations{padding:9rem 6rem;background:#fff}
.dest-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:2rem;max-width:130rem;margin:0 auto}
.dest-card{position:relative;border-radius:1.6rem;overflow:hidden;height:28rem;cursor:pointer;transition:.3s}
.dest-card:first-child{grid-column:span 2;height:32rem}
.dest-card:hover{transform:translateY(-4px);box-shadow:0 16px 48px rgba(0,0,0,0.15)}
.dest-card img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.dest-card:hover img{transform:scale(1.08)}
.dest-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,10,0.8) 0%,transparent 60%)}
.dest-info{position:absolute;bottom:2rem;left:2rem;right:2rem}
.dest-info h3{font-family:'Playfair Display',serif;font-size:2.2rem;color:#fff;font-weight:700;margin-bottom:0.4rem}
.dest-info span{font-size:1.3rem;color:rgba(255,255,255,0.65);display:flex;align-items:center;gap:0.5rem}
.dest-info span i{color:var(--accent)}
.dest-count{position:absolute;top:1.6rem;right:1.6rem;background:rgba(200,151,74,0.9);color:#fff;font-size:1.2rem;font-weight:600;padding:0.5rem 1.2rem;border-radius:5rem}

/* ── PACKAGES TEASER (same style as about teaser) ── */
.pkg-teaser{padding:9rem 6rem;background:var(--cream);position:relative;overflow:hidden;border-top:1px solid var(--border)}
.pkg-teaser::before{content:'';position:absolute;top:-20rem;right:-20rem;width:60rem;height:60rem;border-radius:50%;background:radial-gradient(circle,rgba(200,151,74,0.05) 0%,transparent 70%);pointer-events:none}
.pt-inner{max-width:130rem;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:8rem;align-items:center}
.pt-left h2{font-family:'Playfair Display',serif;font-size:clamp(3.2rem,5vw,5rem);color:var(--ink);line-height:1.15;letter-spacing:-0.5px;margin-bottom:2.4rem}
.pt-left h2 em{font-style:italic;color:var(--accent)}
.pt-left p{font-size:1.7rem;color:var(--muted);line-height:1.85;margin-bottom:3.6rem;font-weight:300}
.pt-btn{display:inline-flex;align-items:center;gap:1rem;padding:1.6rem 4rem;font-size:1.6rem;font-weight:600;background:var(--ink);color:#fff;border:none;border-radius:1rem;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;transition:.25s}
.pt-btn:hover{background:var(--accent);transform:translateY(-2px)}
.pt-right{display:flex;flex-direction:column;gap:1.6rem}
.pt-pkg-row{display:flex;align-items:center;gap:2rem;background:#fff;border:1px solid var(--border);border-radius:1.4rem;padding:1.8rem 2rem;transition:.25s;text-decoration:none}
.pt-pkg-row:hover{border-color:rgba(200,151,74,0.4);box-shadow:0 8px 28px rgba(0,0,0,0.07);transform:translateX(6px)}
.pt-pkg-img{width:7rem;height:7rem;border-radius:1rem;object-fit:cover;flex-shrink:0}
.pt-pkg-img-placeholder{width:7rem;height:7rem;border-radius:1rem;background:rgba(200,151,74,0.1);border:1px solid rgba(200,151,74,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.pt-pkg-img-placeholder i{color:var(--accent);font-size:2.4rem}
.pt-pkg-info{flex:1}
.pt-pkg-info h4{font-family:'Playfair Display',serif;font-size:1.7rem;color:var(--ink);font-weight:600;margin-bottom:0.4rem}
.pt-pkg-info span{font-size:1.3rem;color:var(--muted);display:flex;align-items:center;gap:0.5rem}
.pt-pkg-info span i{color:var(--accent);font-size:1.1rem}
.pt-pkg-price{font-family:'Playfair Display',serif;font-size:2rem;color:var(--accent);font-weight:700;white-space:nowrap}

.about-teaser{padding:9rem 6rem;background:var(--ink);position:relative;overflow:hidden}
.about-teaser::before{content:'';position:absolute;bottom:-20rem;left:-20rem;width:60rem;height:60rem;border-radius:50%;background:radial-gradient(circle,rgba(200,151,74,0.06) 0%,transparent 70%)}
.at-inner{max-width:130rem;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:8rem;align-items:center}
.at-left h2{font-family:'Playfair Display',serif;font-size:clamp(3.2rem,5vw,5rem);color:#fff;line-height:1.15;letter-spacing:-0.5px;margin-bottom:2.4rem}
.at-left h2 em{font-style:italic;color:var(--accent)}
.at-left p{font-size:1.7rem;color:rgba(255,255,255,0.45);line-height:1.85;margin-bottom:3.6rem;font-weight:300}
.at-btn{display:inline-flex;align-items:center;gap:1rem;padding:1.6rem 4rem;font-size:1.6rem;font-weight:600;background:var(--accent);color:#fff;border:none;border-radius:1rem;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;transition:.25s}
.at-btn:hover{background:var(--accent2);transform:translateY(-2px)}
.at-right{display:grid;grid-template-columns:1fr 1fr;gap:1.6rem}
.at-stat{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:1.4rem;padding:2.8rem 2.4rem;text-align:center;transition:.2s}
.at-stat:hover{background:rgba(200,151,74,0.08);border-color:rgba(200,151,74,0.2)}
.at-stat .n{font-family:'Playfair Display',serif;font-size:4rem;color:var(--accent);font-weight:700;line-height:1;margin-bottom:0.6rem}
.at-stat .l{font-size:1.3rem;color:rgba(255,255,255,0.4);font-weight:300}
.offer-banner{position:relative;overflow:hidden;padding:10rem 6rem;background:url('images/home-slide-2.jpg') center/cover no-repeat;display:flex;align-items:center}
.offer-banner::before{content:'';position:absolute;inset:0;background:linear-gradient(100deg,rgba(10,10,10,0.88) 0%,rgba(139,94,42,0.5) 60%,rgba(10,10,10,0.7) 100%)}
.offer-inner{position:relative;z-index:2;max-width:60rem}
.offer-tag{display:inline-flex;align-items:center;gap:0.8rem;background:rgba(200,151,74,0.2);border:1px solid rgba(200,151,74,0.5);border-radius:5rem;padding:0.7rem 1.8rem;font-size:1.2rem;color:var(--accent);letter-spacing:1px;text-transform:uppercase;font-weight:600;margin-bottom:2rem}
.offer-inner h2{font-family:'Playfair Display',serif;font-size:clamp(3.6rem,6vw,6.4rem);color:#fff;font-weight:700;line-height:1.08;letter-spacing:-1px;margin-bottom:1.6rem}
.offer-inner h2 em{font-style:italic;color:var(--accent)}
.offer-inner p{font-size:1.7rem;color:rgba(255,255,255,0.55);max-width:50rem;font-weight:300;line-height:1.75;margin-bottom:3.6rem}
.offer-cards{position:absolute;right:6rem;top:50%;transform:translateY(-50%);display:flex;gap:1.6rem;z-index:2}
.offer-chip{background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:1.4rem;padding:2.4rem 2rem;text-align:center;backdrop-filter:blur(8px);min-width:13rem}
.offer-chip .pct{font-family:'Playfair Display',serif;font-size:3.6rem;color:var(--accent);font-weight:700;line-height:1}
.offer-chip .desc{font-size:1.3rem;color:rgba(255,255,255,0.55);margin-top:0.6rem;font-weight:300}
.cta-section{background:var(--cream);padding:10rem 6rem;text-align:center;position:relative;overflow:hidden;border-top:1px solid var(--border)}
.cta-section h2{font-family:'Playfair Display',serif;font-size:clamp(3.2rem,5vw,5.2rem);color:var(--ink);margin-bottom:1.6rem;letter-spacing:-0.5px}
.cta-section h2 em{font-style:italic;color:var(--accent)}
.cta-section p{font-size:1.7rem;color:var(--muted);max-width:54rem;margin:0 auto 4rem;font-weight:300;line-height:1.75}
.btn-cta{display:inline-flex;align-items:center;gap:1.2rem;padding:1.8rem 5rem;font-size:1.7rem;font-weight:600;background:var(--ink);color:#fff;border:none;border-radius:1rem;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;transition:.25s}
.btn-cta:hover{background:var(--accent);transform:translateY(-2px);box-shadow:0 8px 24px rgba(200,151,74,0.4)}
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
.reveal{opacity:0;transform:translateY(24px);transition:opacity .6s ease,transform .6s ease}
.reveal.visible{opacity:1;transform:translateY(0)}
.reveal-l{opacity:0;transform:translateX(-32px);transition:opacity .7s ease,transform .7s ease}
.reveal-l.visible{opacity:1;transform:translateX(0)}
.reveal-r{opacity:0;transform:translateX(32px);transition:opacity .7s ease,transform .7s ease}
.reveal-r.visible{opacity:1;transform:translateX(0)}
.mob-menu-btn{display:none;font-size:2.4rem;cursor:pointer;color:var(--ink);background:none;border:none}
.mob-nav{display:none;position:fixed;inset:0;background:rgba(15,15,15,0.96);z-index:1000;flex-direction:column;align-items:center;justify-content:center;gap:3rem}
.mob-nav.open{display:flex}
.mob-nav a{font-family:'Playfair Display',serif;font-size:3.6rem;color:#fff;text-decoration:none;font-weight:700;transition:.2s}
.mob-nav a:hover{color:var(--accent)}
.mob-close{position:absolute;top:3rem;right:3rem;font-size:2.8rem;color:rgba(255,255,255,0.5);cursor:pointer;background:none;border:none;transition:.2s}
.mob-close:hover{color:#fff}
@media(max-width:1200px){.services-grid{grid-template-columns:repeat(3,1fr)}.hiw-grid{grid-template-columns:repeat(2,1fr)}.dest-grid{grid-template-columns:repeat(2,1fr)}.dest-card:first-child{grid-column:span 2}}
@media(max-width:1024px){.pkg-row{grid-template-columns:repeat(2,1fr)}.offer-cards{display:none}.footer-grid{grid-template-columns:1fr 1fr;gap:4rem}.at-inner{grid-template-columns:1fr;gap:5rem}.pt-inner{grid-template-columns:1fr;gap:5rem}}
@media(max-width:768px){
  .site-header{padding:1.6rem 2rem}.nav-links{display:none}.mob-menu-btn{display:block}
  .slide-content{padding:0 2rem}
  .services-section,.how-it-works,.home-packages,.destinations,.about-teaser,.offer-banner,.cta-section,.pkg-teaser{padding:6rem 2rem}
  .services-grid{grid-template-columns:repeat(2,1fr)}
  .hiw-grid{grid-template-columns:1fr 1fr}
  .pkg-row{grid-template-columns:1fr}
  .dest-grid{grid-template-columns:1fr 1fr}
  .dest-card:first-child{grid-column:span 2;height:28rem}
  .at-right{grid-template-columns:1fr 1fr}
  .stats-strip{gap:0}
  .footer-grid{grid-template-columns:1fr}
  .site-footer{padding:5rem 2rem}
}
</style>
</head>
<body>

<header class="site-header">
  <a href="home.php" class="logo">travel<span>.</span></a>
  <nav class="nav-links">
    <a href="home.php" class="active">Home</a>
    <a href="about.php">About</a>
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
    <button class="mob-menu-btn" onclick="document.getElementById('mobNav').classList.add('open')"><i class="fas fa-bars"></i></button>
  </div>
</header>

<div class="mob-nav" id="mobNav">
  <button class="mob-close" onclick="document.getElementById('mobNav').classList.remove('open')"><i class="fas fa-times"></i></button>
  <a href="home.php" onclick="document.getElementById('mobNav').classList.remove('open')">Home</a>
  <a href="about.php" onclick="document.getElementById('mobNav').classList.remove('open')">About</a>
  <a href="package.php" onclick="document.getElementById('mobNav').classList.remove('open')">Packages</a>
  <a href="book.php" onclick="document.getElementById('mobNav').classList.remove('open')">Book</a>
  <?php if(isset($_SESSION['user_id'])): ?>
  <a href="my_bookings.php" onclick="document.getElementById('mobNav').classList.remove('open')">My Bookings</a>
  <?php endif; ?>
</div>

<!-- HERO -->
<div class="hero-slider-wrap">
  <div class="swiper hero-swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide hero-slide" style="background:url('images/home-slide-1.jpg') center/cover no-repeat">
        <div class="slide-content">
          <div class="slide-tag"><i class="fas fa-compass"></i> Explore · Discover · Travel</div>
          <h1>Travel Around<br><em>the World</em></h1>
          <p>Step beyond borders and into extraordinary places. Every journey begins with a single booking.</p>
          <div class="slide-btns">
            <a href="package.php" class="btn-primary">Discover Packages <i class="fas fa-arrow-right"></i></a>
            <a href="about.php" class="btn-outline">Our Story <i class="fas fa-play"></i></a>
          </div>
        </div>
      </div>
      <div class="swiper-slide hero-slide" style="background:url('images/home-slide-2.jpg') center/cover no-repeat">
        <div class="slide-content">
          <div class="slide-tag"><i class="fas fa-map-marker-alt"></i> New Destinations</div>
          <h1>Discover <em>New</em><br>Places Daily</h1>
          <p>From hidden beaches to ancient cities — we unlock the world's most captivating destinations for you.</p>
          <div class="slide-btns">
            <a href="package.php" class="btn-primary">View Packages <i class="fas fa-arrow-right"></i></a>
            <a href="book.php" class="btn-outline">Book a Trip <i class="fas fa-suitcase"></i></a>
          </div>
        </div>
      </div>
      <div class="swiper-slide hero-slide" style="background:url('images/home-slide-3.jpg') center/cover no-repeat">
        <div class="slide-content">
          <div class="slide-tag"><i class="fas fa-star"></i> Curated Experiences</div>
          <h1>Make Your Tour<br><em>Worthwhile</em></h1>
          <p>Premium packages, expert guides, and memories that last a lifetime — all in one place.</p>
          <div class="slide-btns">
            <a href="book.php" class="btn-primary">Start Planning <i class="fas fa-arrow-right"></i></a>
            <a href="about.php" class="btn-outline">Learn More <i class="fas fa-info-circle"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
  </div>
  <div class="hero-scroll"><i class="fas fa-chevron-down"></i><span>Scroll</span></div>
</div>

<!-- STATS -->
<div class="stats-strip">
  <div class="stat-item reveal"><div class="num"><?= $total_bookings ?>+</div><div class="lbl">Happy Travelers</div></div>
  <div class="stat-item reveal" style="transition-delay:.1s"><div class="num"><?= $total_packages ?>+</div><div class="lbl">Active Packages</div></div>
  <div class="stat-item reveal" style="transition-delay:.2s"><div class="num">14</div><div class="lbl">Years Experience</div></div>
  <div class="stat-item reveal" style="transition-delay:.3s"><div class="num"><?= $total_users ?>+</div><div class="lbl">Registered Users</div></div>
</div>

<!-- SERVICES -->
<section class="services-section">
  <div class="sec-header reveal">
    <span class="eyebrow">What We Offer</span>
    <h2>Our <em>Services</em></h2>
    <p>Everything you need for an extraordinary adventure, all in one place</p>
  </div>
  <div class="services-grid">
    <div class="srv-card reveal"><div class="srv-icon"><i class="fas fa-mountain"></i></div><h3>Adventure</h3><p>Thrilling experiences for the bold explorer</p></div>
    <div class="srv-card reveal" style="transition-delay:.07s"><div class="srv-icon"><i class="fas fa-map-marked-alt"></i></div><h3>Tour Guide</h3><p>Expert local guides at every destination</p></div>
    <div class="srv-card reveal" style="transition-delay:.14s"><div class="srv-icon"><i class="fas fa-hiking"></i></div><h3>Trekking</h3><p>Guided trails through nature's finest paths</p></div>
    <div class="srv-card reveal" style="transition-delay:.21s"><div class="srv-icon"><i class="fas fa-umbrella-beach"></i></div><h3>Beach Tours</h3><p>Stunning coastal escapes worldwide</p></div>
    <div class="srv-card reveal" style="transition-delay:.28s"><div class="srv-icon"><i class="fas fa-gem"></i></div><h3>Luxury</h3><p>Premium 5-star travel experiences</p></div>
    <div class="srv-card reveal" style="transition-delay:.35s"><div class="srv-icon"><i class="fas fa-heart"></i></div><h3>Honeymoon</h3><p>Romantic getaways for couples</p></div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-it-works">
  <div class="hiw-inner">
    <div class="sec-header light reveal">
      <span class="eyebrow">Simple Process</span>
      <h2>How It <em>Works</em></h2>
      <p>Book your dream trip in just 4 easy steps</p>
    </div>
    <div class="hiw-grid">
      <div class="hiw-card reveal"><div class="hiw-num">1</div><h3>Create Account</h3><p>Register for free and set up your traveler profile in minutes</p></div>
      <div class="hiw-card reveal" style="transition-delay:.1s"><div class="hiw-num">2</div><h3>Choose Package</h3><p>Browse our curated packages and find your perfect destination</p></div>
      <div class="hiw-card reveal" style="transition-delay:.2s"><div class="hiw-num">3</div><h3>Book Your Trip</h3><p>Fill in your details and submit your booking request securely</p></div>
      <div class="hiw-card reveal" style="transition-delay:.3s"><div class="hiw-num">4</div><h3>Start Exploring</h3><p>Get confirmed and enjoy your unforgettable travel experience</p></div>
    </div>
  </div>
</section>

<!-- PACKAGES TEASER -->
<?php $pkg_teaser = mysqli_query($connection, "SELECT * FROM packages WHERE status='active' ORDER BY id ASC LIMIT 3"); ?>
<section class="pkg-teaser">
  <div class="pt-inner">
    <div class="pt-left reveal-l">
      <span class="eyebrow">Explore Our Packages</span>
      <h2>Find Your<br><em>Perfect Package</em></h2>
      <p>Handpicked experiences across the world's most breathtaking destinations — for every traveler, every budget. Browse all <?= $total_packages ?>+ active packages and find your match.</p>
      <a href="package.php" class="pt-btn">Browse All Packages <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="pt-right reveal-r">
      <?php while($p = mysqli_fetch_assoc($pkg_teaser)): ?>
      <a href="book.php?package_id=<?= $p['id'] ?>" class="pt-pkg-row">
        <?php if(!empty($p['image'])): ?>
          <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" class="pt-pkg-img" onerror="this.style.display='none'">
        <?php else: ?>
          <div class="pt-pkg-img-placeholder"><i class="fas fa-map-marked-alt"></i></div>
        <?php endif; ?>
        <div class="pt-pkg-info">
          <h4><?= htmlspecialchars($p['title']) ?></h4>
          <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($p['location']) ?> &nbsp;·&nbsp; <i class="fas fa-clock"></i> <?= htmlspecialchars($p['duration']) ?></span>
        </div>
        <div class="pt-pkg-price">$<?= number_format($p['price'],0) ?></div>
      </a>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- ABOUT TEASER -->
<section class="about-teaser">
  <div class="at-inner">
    <div class="at-left reveal-l">
      <span class="eyebrow" style="color:var(--accent);font-size:1.2rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;display:block;margin-bottom:1.6rem">Who We Are</span>
      <h2>Travel with People<br>Who <em>Truly Care</em></h2>
      <p>We're not just a booking agency — we're your travel companions. 14 years of crafting extraordinary journeys with passion, precision, and expertise.</p>
      <a href="about.php" class="at-btn">Discover Our Story <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="at-right reveal-r">
      <div class="at-stat"><div class="n"><?= $total_bookings ?>+</div><div class="l">Trips Booked</div></div>
      <div class="at-stat"><div class="n"><?= $total_packages ?>+</div><div class="l">Packages</div></div>
      <div class="at-stat"><div class="n">4.9★</div><div class="l">Rating</div></div>
      <div class="at-stat"><div class="n"><?= $total_users ?>+</div><div class="l">Happy Users</div></div>
    </div>
  </div>
</section>

<!-- OFFER BANNER -->
<section class="offer-banner">
  <div class="offer-inner reveal">
    <div class="offer-tag"><i class="fas fa-tag"></i> Limited Time Offer</div>
    <h2>Up to <em>50% Off</em><br>Selected Packages</h2>
    <p>Don't miss our biggest sale of the year. Grab your dream destination at an unbeatable price — offer ends soon.</p>
    <a href="book.php" class="btn-primary">Claim Your Discount <i class="fas fa-arrow-right"></i></a>
  </div>
  <div class="offer-cards">
    <div class="offer-chip"><div class="pct">50%</div><div class="desc">Beach<br>Packages</div></div>
    <div class="offer-chip"><div class="pct">35%</div><div class="desc">Adventure<br>Tours</div></div>
    <div class="offer-chip"><div class="pct">40%</div><div class="desc">Honeymoon<br>Deals</div></div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="reveal">
    <h2>Ready to Start Your<br><em>Next Adventure?</em></h2>
    <p>Join thousands of happy travelers. Let us handle every detail while you focus on the memories.</p>
    <a href="book.php" class="btn-cta">Plan My Trip <i class="fas fa-arrow-right"></i></a>
  </div>
</section>

<!-- FOOTER -->
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

<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<script>
new Swiper('.hero-swiper', {
  loop:true,autoplay:{delay:5500,disableOnInteraction:false},
  navigation:{nextEl:'.swiper-button-next',prevEl:'.swiper-button-prev'},
  pagination:{el:'.swiper-pagination',clickable:true},
  effect:'fade',fadeEffect:{crossFade:true},speed:900
});
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); } });
}, {threshold:0.1});
document.querySelectorAll('.reveal,.reveal-l,.reveal-r').forEach(el => obs.observe(el));
</script>
</body>
</html>