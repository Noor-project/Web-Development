<?php
session_start();
if(!isset($_SESSION['user_id'])){
   header('location:login.php');
   exit();
}
$connection = mysqli_connect('localhost','root','','booking_db');

$user_id  = (int)$_SESSION['user_id'];
$bookings = mysqli_query($connection,
   "SELECT * FROM bookings WHERE user_id=$user_id ORDER BY created_at DESC"
);
$total = mysqli_num_rows($bookings);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Bookings — travel.</title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <style>
      :root{--ink:#0f0f0f;--cream:#faf8f5;--stone:#f2ede8;--muted:#888880;--accent:#c8974a;--accent2:#8b5e2a;--green:#2d6a4f;--border:#e8e2da;--shadow:0 4px 32px rgba(0,0,0,0.07)}
      *{box-sizing:border-box;margin:0;padding:0}
      html{font-size:62.5%;scroll-behavior:smooth}
      body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);font-size:1.5rem;line-height:1.6}

      /* HEADER */
      .site-header{position:fixed;top:0;left:0;right:0;z-index:900;display:flex;align-items:center;justify-content:space-between;padding:1.8rem 6rem;background:rgba(250,248,245,0.92);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)}
      .logo{font-family:'Playfair Display',serif;font-size:2.6rem;font-weight:700;color:var(--ink);text-decoration:none;letter-spacing:-0.5px}
      .logo span{color:var(--accent)}
      .nav-links{display:flex;gap:3.2rem}
      .nav-links a{font-size:1.4rem;font-weight:500;color:var(--muted);text-decoration:none;transition:color .2s}
      .nav-links a:hover,.nav-links a.active{color:var(--ink)}
      .header-right{display:flex;align-items:center;gap:1.6rem}
      .user-pill{display:flex;align-items:center;gap:1rem;background:var(--stone);border:1px solid var(--border);border-radius:5rem;padding:0.8rem 1.8rem;font-size:1.3rem;font-weight:500}
      .user-pill .avatar{width:2.8rem;height:2.8rem;border-radius:50%;background:var(--accent);display:grid;place-items:center;color:#fff;font-size:1.2rem;font-weight:700}
      .btn-signin{font-size:1.3rem;color:var(--muted);text-decoration:none;padding:0.8rem 1.8rem;border:1px solid var(--border);border-radius:5rem;transition:.2s}
      .btn-signin:hover{background:var(--ink);color:#fff;border-color:var(--ink)}

      /* PAGE */
      .page-wrap{max-width:120rem;margin:0 auto;padding:12rem 6rem 6rem}

      /* PAGE HEADER */
      .page-header{margin-bottom:4rem}
      .page-header h1{font-family:'Playfair Display',serif;font-size:4rem;color:var(--ink);font-weight:700;margin-bottom:0.8rem}
      .page-header p{font-size:1.6rem;color:var(--muted)}

      /* STATS ROW */
      .stats-row{display:flex;gap:2rem;margin-bottom:4rem;flex-wrap:wrap}
      .stat-box{background:#fff;border:1px solid var(--border);border-radius:1.4rem;padding:2.4rem 3rem;flex:1;min-width:16rem;display:flex;align-items:center;gap:1.6rem}
      .stat-icon{width:5rem;height:5rem;border-radius:1rem;display:flex;align-items:center;justify-content:center;font-size:2rem;flex-shrink:0}
      .stat-icon.gold{background:rgba(200,151,74,0.1);color:var(--accent)}
      .stat-icon.green{background:rgba(45,106,79,0.1);color:var(--green)}
      .stat-icon.orange{background:rgba(230,126,34,0.1);color:#e67e22}
      .stat-icon.red{background:rgba(192,57,43,0.1);color:#c0392b}
      .stat-txt p{font-size:1.2rem;color:var(--muted);margin-bottom:0.2rem}
      .stat-txt h3{font-size:2.8rem;font-weight:700;color:var(--ink);line-height:1}

      /* EMPTY */
      .empty-box{text-align:center;padding:8rem 2rem;background:#fff;border-radius:2rem;border:1px solid var(--border)}
      .empty-box i{font-size:5rem;color:var(--border);display:block;margin-bottom:2rem}
      .empty-box h3{font-family:'Playfair Display',serif;font-size:2.4rem;color:var(--ink);margin-bottom:1rem}
      .empty-box p{font-size:1.5rem;color:var(--muted);margin-bottom:2.4rem}
      .btn-book{display:inline-flex;align-items:center;gap:1rem;padding:1.4rem 3rem;background:var(--ink);color:#fff;border-radius:0.8rem;font-size:1.5rem;font-weight:600;text-decoration:none;transition:.2s}
      .btn-book:hover{background:var(--accent)}

      /* BOOKING CARDS */
      .bookings-grid{display:grid;gap:2rem}
      .booking-card{background:#fff;border-radius:2rem;border:1px solid var(--border);overflow:hidden;transition:.2s}
      .booking-card:hover{box-shadow:var(--shadow)}
      .card-header{padding:2rem 2.8rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border);background:var(--stone)}
      .card-header-left{display:flex;align-items:center;gap:1.6rem}
      .booking-id{font-size:1.3rem;color:var(--muted);font-weight:600}
      .booking-dest{font-family:'Playfair Display',serif;font-size:2rem;color:var(--ink);font-weight:700}
      .card-header-right{display:flex;align-items:center;gap:1.2rem}
      .badge{display:inline-flex;align-items:center;gap:0.6rem;padding:0.7rem 1.6rem;border-radius:5rem;font-size:1.3rem;font-weight:600}
      .badge.pending{background:#fff3cd;color:#856404}
      .badge.confirmed{background:#d1e7dd;color:#0f5132}
      .badge.cancelled{background:#f8d7da;color:#842029}
      .badge.payment_pending{background:#fff3cd;color:#856404}

      /* PAY NOW BUTTON */
      .btn-pay-now{
         display:inline-flex;align-items:center;gap:0.8rem;
         padding:0.9rem 2rem;
         background:var(--accent);color:#fff;
         border-radius:5rem;font-size:1.3rem;font-weight:600;
         text-decoration:none;transition:.2s;
         border:none;cursor:pointer;
         font-family:'DM Sans',sans-serif;
         white-space:nowrap;
      }
      .btn-pay-now:hover{background:var(--accent2);transform:translateY(-1px);box-shadow:0 4px 16px rgba(200,151,74,0.35)}
      .btn-pay-now i{font-size:1.2rem}

      /* DUE AMOUNT BANNER */
      .due-banner{
         background:linear-gradient(135deg,rgba(200,151,74,0.08),rgba(200,151,74,0.04));
         border:1px solid rgba(200,151,74,0.25);
         border-radius:0 0 0 0;
         padding:1.4rem 2.8rem;
         display:flex;align-items:center;justify-content:space-between;
         border-top:1px dashed rgba(200,151,74,0.3);
         flex-wrap:wrap;gap:1rem;
      }
      .due-info{display:flex;align-items:center;gap:1.2rem}
      .due-info i{color:var(--accent);font-size:1.6rem}
      .due-info span{font-size:1.4rem;color:var(--ink)}
      .due-info strong{color:var(--accent);font-size:1.6rem;font-family:'Playfair Display',serif}

      .card-body{padding:2.4rem 2.8rem}
      .info-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:2rem;margin-bottom:2rem}
      .info-item label{font-size:1.2rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:0.4rem}
      .info-item p{font-size:1.4rem;color:var(--ink);font-weight:500}
      .info-item p i{color:var(--accent);margin-right:0.6rem}
      .card-footer{padding:1.8rem 2.8rem;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem}
      .trip-tags{display:flex;gap:0.8rem;flex-wrap:wrap}
      .tag{display:inline-flex;align-items:center;gap:0.5rem;background:var(--stone);border:1px solid var(--border);border-radius:5rem;padding:0.5rem 1.2rem;font-size:1.2rem;color:var(--ink);font-weight:500}
      .tag i{color:var(--accent);font-size:1.1rem}
      .booking-date{font-size:1.2rem;color:var(--muted)}

      /* FOOTER */
      .site-footer{background:#070707;padding:4rem 6rem;border-top:1px solid rgba(255,255,255,0.06);text-align:center}
      .site-footer p{font-size:1.3rem;color:rgba(255,255,255,0.2)}
      .site-footer span{color:var(--accent)}

      @media(max-width:768px){
         .site-header{padding:1.6rem 2rem}
         .nav-links{display:none}
         .page-wrap{padding:10rem 2rem 4rem}
         .info-grid{grid-template-columns:1fr 1fr}
         .stats-row{gap:1.2rem}
         .due-banner{flex-direction:column;align-items:flex-start}
      }
   </style>
</head>
<body>

<!-- HEADER -->
<header class="site-header">
   <a href="home.php" class="logo">travel<span>.</span></a>
   <nav class="nav-links">
      <a href="home.php">Home</a>
      <a href="about.php">About</a>
      <a href="package.php">Packages</a>
      <a href="book.php">Book</a>
      <a href="my_bookings.php" class="active">My Bookings</a>
   </nav>
   <div class="header-right">
      <div class="user-pill">
         <div class="avatar"><?= strtoupper(substr($_SESSION['user_name'],0,1)) ?></div>
         <?= htmlspecialchars($_SESSION['user_name']) ?>
      </div>
      <a href="logout.php" class="btn-signin">Sign Out</a>
   </div>
</header>

<!-- PAGE -->
<div class="page-wrap">
   <div class="page-header">
      <h1>My Bookings</h1>
      <p>Track and manage all your travel bookings in one place</p>
   </div>

   <?php
   $all      = mysqli_query($connection, "SELECT COUNT(*) as c FROM bookings WHERE user_id=$user_id");
   $pending  = mysqli_query($connection, "SELECT COUNT(*) as c FROM bookings WHERE user_id=$user_id AND status='pending'");
   $confirmed= mysqli_query($connection, "SELECT COUNT(*) as c FROM bookings WHERE user_id=$user_id AND status='confirmed'");
   $cancelled= mysqli_query($connection, "SELECT COUNT(*) as c FROM bookings WHERE user_id=$user_id AND status='cancelled'");
   $t_all    = mysqli_fetch_assoc($all)['c'];
   $t_pend   = mysqli_fetch_assoc($pending)['c'];
   $t_conf   = mysqli_fetch_assoc($confirmed)['c'];
   $t_canc   = mysqli_fetch_assoc($cancelled)['c'];
   ?>

   <!-- STATS -->
   <div class="stats-row">
      <div class="stat-box">
         <div class="stat-icon gold"><i class="fas fa-suitcase"></i></div>
         <div class="stat-txt"><p>Total Trips</p><h3><?= $t_all ?></h3></div>
      </div>
      <div class="stat-box">
         <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
         <div class="stat-txt"><p>Pending</p><h3><?= $t_pend ?></h3></div>
      </div>
      <div class="stat-box">
         <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
         <div class="stat-txt"><p>Confirmed</p><h3><?= $t_conf ?></h3></div>
      </div>
      <div class="stat-box">
         <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
         <div class="stat-txt"><p>Cancelled</p><h3><?= $t_canc ?></h3></div>
      </div>
   </div>

   <?php if($total == 0): ?>
   <div class="empty-box">
      <i class="fas fa-map-marked-alt"></i>
      <h3>No Bookings Yet</h3>
      <p>You haven't booked any trips yet. Start exploring our amazing packages!</p>
      <a href="book.php" class="btn-book">Book Your First Trip <i class="fas fa-arrow-right"></i></a>
   </div>

   <?php else: ?>
   <div class="bookings-grid">
      <?php while($b = mysqli_fetch_assoc($bookings)):

         // Calculate total amount same as book-form.php
         $nights = 1;
         if($b['arrival'] && $b['leaving']){
            $nights = max(1, round((strtotime($b['leaving']) - strtotime($b['arrival'])) / 86400));
         }
         $total_amount = $b['guests'] * $nights * $b['budget'];

         // Check if payment already submitted for this booking
         $pay_check = mysqli_fetch_assoc(mysqli_query($connection,
            "SELECT status FROM payments WHERE booking_id={$b['id']} ORDER BY id DESC LIMIT 1"
         ));
         $payment_status = $pay_check['status'] ?? null;

         // Show Pay Now only if booking is pending AND no payment submitted yet
         $show_pay_btn = ($b['status'] === 'pending' && $payment_status === null);
         // Show "Awaiting Approval" if payment submitted but not yet approved
         $awaiting = ($b['status'] === 'pending' && $payment_status === 'pending');

      ?>
      <div class="booking-card">

         <!-- CARD HEADER -->
         <div class="card-header">
            <div class="card-header-left">
               <div>
                  <div class="booking-id">Booking #<?= $b['id'] ?></div>
                  <div class="booking-dest"><?= htmlspecialchars($b['location']) ?></div>
               </div>
            </div>
            <div class="card-header-right">
               <?php if($show_pay_btn): ?>
                  <a href="payment.php?booking_id=<?= $b['id'] ?>&amount=<?= $total_amount ?>"
                     class="btn-pay-now">
                     <i class="fas fa-credit-card"></i> Pay Now
                  </a>
               <?php endif; ?>
               <span class="badge <?= $b['status'] ?>">
                  <i class="fas fa-<?= $b['status']=='confirmed'?'check-circle':($b['status']=='cancelled'?'times-circle':'clock') ?>"></i>
                  <?= ucfirst($b['status']) ?>
               </span>
            </div>
         </div>

         <!-- DUE AMOUNT BANNER — shows if payment not yet submitted -->
         <?php if($show_pay_btn): ?>
         <div class="due-banner">
            <div class="due-info">
               <i class="fas fa-exclamation-circle"></i>
               <span>Payment due for this booking &nbsp;·&nbsp; <strong>$<?= number_format($total_amount, 2) ?></strong></span>
            </div>
            <a href="payment.php?booking_id=<?= $b['id'] ?>&amount=<?= $total_amount ?>"
               class="btn-pay-now">
               <i class="fas fa-lock"></i> Complete Payment
            </a>
         </div>
         <?php endif; ?>

         <!-- AWAITING APPROVAL BANNER -->
         <?php if($awaiting): ?>
         <div style="background:rgba(255,243,205,0.6);border-top:1px dashed #ffc107;padding:1.4rem 2.8rem;display:flex;align-items:center;gap:1.2rem;font-size:1.4rem;color:#856404">
            <i class="fas fa-hourglass-half"></i>
            <span>Payment submitted — <strong>awaiting admin approval</strong>. Your booking will be confirmed shortly.</span>
         </div>
         <?php endif; ?>

         <!-- CONFIRMED BANNER -->
         <?php if($b['status'] === 'confirmed'): ?>
         <div style="background:rgba(209,231,221,0.5);border-top:1px dashed #6ee7b7;padding:1.4rem 2.8rem;display:flex;align-items:center;gap:1.2rem;font-size:1.4rem;color:#065f46">
            <i class="fas fa-check-circle"></i>
            <span>Payment approved — <strong>Booking confirmed!</strong> Have a wonderful trip.</span>
         </div>
         <?php endif; ?>

         <!-- CARD BODY -->
         <div class="card-body">
            <div class="info-grid">
               <div class="info-item">
                  <label>Arrival</label>
                  <p><i class="fas fa-plane-arrival"></i><?= date('M d, Y', strtotime($b['arrival'])) ?></p>
               </div>
               <div class="info-item">
                  <label>Departure</label>
                  <p><i class="fas fa-plane-departure"></i><?= date('M d, Y', strtotime($b['leaving'])) ?></p>
               </div>
               <div class="info-item">
                  <label>Guests</label>
                  <p><i class="fas fa-users"></i><?= $b['guests'] ?> Person(s)</p>
               </div>
               <div class="info-item">
                  <label>Budget</label>
                  <p><i class="fas fa-dollar-sign"></i><?= number_format($b['budget'],2) ?>/person</p>
               </div>
            </div>
         </div>

         <!-- CARD FOOTER -->
         <div class="card-footer">
            <div class="trip-tags">
               <?php if($b['trip_type']): ?>
               <span class="tag"><i class="fas fa-tag"></i><?= htmlspecialchars($b['trip_type']) ?></span>
               <?php endif; ?>
               <?php if($b['accommodation']): ?>
               <span class="tag"><i class="fas fa-hotel"></i><?= htmlspecialchars($b['accommodation']) ?></span>
               <?php endif; ?>
               <?php if($b['meal']): ?>
               <span class="tag"><i class="fas fa-utensils"></i><?= htmlspecialchars($b['meal']) ?></span>
               <?php endif; ?>
               <?php if($b['transport']): ?>
               <span class="tag"><i class="fas fa-car"></i><?= htmlspecialchars($b['transport']) ?></span>
               <?php endif; ?>
            </div>
            <div class="booking-date">
               Booked on <?= date('M d, Y', strtotime($b['created_at'])) ?>
            </div>
         </div>

      </div>
      <?php endwhile; ?>
   </div>
   <?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="site-footer">
   <p>© 2025 <span>travel.</span> — All rights reserved</p>
</footer>

</body>
</html>