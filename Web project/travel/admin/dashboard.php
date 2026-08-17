<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
   header('location:login.php');
   exit();
}
$connection = mysqli_connect('localhost','root','','booking_db');

// Get stats
$total_bookings  = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM bookings"))['total'];
$pending_bookings = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM bookings WHERE status='pending'"))['total'];
$confirmed_bookings = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM bookings WHERE status='confirmed'"))['total'];
$total_users     = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM users"))['total'];
$total_packages  = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM packages"))['total'];
$cancelled_bookings = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM bookings WHERE status='cancelled'"))['total'];

// Recent bookings
$recent_bookings = mysqli_query($connection, "SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard — Admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   <style>
      *{margin:0;padding:0;box-sizing:border-box;font-family:'DM Sans',sans-serif}
      html{font-size:62.5%}
      body{background:#f5f5f5;min-height:100vh;display:flex}

      /* SIDEBAR */
      .sidebar{width:26rem;background:#0f0f0f;min-height:100vh;position:fixed;top:0;left:0;z-index:100;display:flex;flex-direction:column}
      .sidebar-logo{padding:3rem 2.5rem;border-bottom:1px solid #1a1a1a}
      .sidebar-logo h1{font-size:2.4rem;color:#fff;font-weight:700}
      .sidebar-logo h1 span{color:#c8974a}
      .sidebar-logo p{font-size:1.2rem;color:#666;margin-top:0.4rem}
      .sidebar-menu{padding:2rem 0;flex:1}
      .menu-label{font-size:1.1rem;color:#444;text-transform:uppercase;letter-spacing:1.5px;padding:0 2.5rem;margin:1.5rem 0 0.8rem}
      .menu-item{display:flex;align-items:center;gap:1.4rem;padding:1.3rem 2.5rem;color:#888;font-size:1.4rem;font-weight:500;text-decoration:none;transition:.2s;cursor:pointer}
      .menu-item:hover,.menu-item.active{background:#1a1a1a;color:#c8974a;border-right:3px solid #c8974a}
      .menu-item i{width:1.8rem;text-align:center;font-size:1.5rem}
      .sidebar-bottom{padding:2rem 2.5rem;border-top:1px solid #1a1a1a}
      .admin-info{display:flex;align-items:center;gap:1.2rem;margin-bottom:1.5rem}
      .admin-avatar{width:4rem;height:4rem;border-radius:50%;background:#c8974a;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.6rem;font-weight:700}
      .admin-info-txt p{font-size:1.3rem;color:#fff;font-weight:600}
      .admin-info-txt span{font-size:1.1rem;color:#666}
      .btn-logout{display:flex;align-items:center;gap:1rem;padding:1rem 1.5rem;background:#1a1a1a;color:#888;border:1px solid #2a2a2a;border-radius:0.8rem;font-size:1.3rem;text-decoration:none;transition:.2s;font-family:'DM Sans',sans-serif;cursor:pointer;width:100%}
      .btn-logout:hover{background:#c0392b;color:#fff;border-color:#c0392b}

      /* MAIN */
      .main{margin-left:26rem;flex:1;padding:3rem}

      /* TOPBAR */
      .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:3rem}
      .topbar h2{font-size:2.4rem;color:#0f0f0f;font-weight:700}
      .topbar p{font-size:1.4rem;color:#888;margin-top:0.3rem}

      /* STATS */
      .stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;margin-bottom:3rem}
      .stat-card{background:#fff;border-radius:1.6rem;padding:2.8rem;border:1px solid #eee;display:flex;align-items:center;gap:2rem;transition:.2s}
      .stat-card:hover{box-shadow:0 8px 32px rgba(0,0,0,0.08);transform:translateY(-2px)}
      .stat-icon{width:5.6rem;height:5.6rem;border-radius:1.2rem;display:flex;align-items:center;justify-content:center;font-size:2.2rem;flex-shrink:0}
      .stat-icon.gold{background:rgba(200,151,74,0.1);color:#c8974a}
      .stat-icon.green{background:rgba(45,106,79,0.1);color:#2d6a4f}
      .stat-icon.blue{background:rgba(52,152,219,0.1);color:#3498db}
      .stat-icon.red{background:rgba(192,57,43,0.1);color:#c0392b}
      .stat-icon.purple{background:rgba(142,68,173,0.1);color:#8e44ad}
      .stat-icon.orange{background:rgba(230,126,34,0.1);color:#e67e22}
      .stat-info p{font-size:1.3rem;color:#888;margin-bottom:0.4rem}
      .stat-info h3{font-size:3.2rem;font-weight:700;color:#0f0f0f;line-height:1}

      /* TABLE */
      .section-card{background:#fff;border-radius:1.6rem;border:1px solid #eee;overflow:hidden;margin-bottom:2rem}
      .section-head{padding:2rem 2.8rem;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between}
      .section-head h3{font-size:1.8rem;font-weight:700;color:#0f0f0f}
      .section-head a{font-size:1.3rem;color:#c8974a;text-decoration:none;font-weight:600}
      .section-head a:hover{text-decoration:underline}
      table{width:100%;border-collapse:collapse}
      th{padding:1.4rem 2rem;text-align:left;font-size:1.2rem;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:0.8px;border-bottom:1px solid #eee;background:#fafafa}
      td{padding:1.6rem 2rem;font-size:1.4rem;color:#333;border-bottom:1px solid #f5f5f5}
      tr:last-child td{border-bottom:none}
      tr:hover td{background:#fafafa}
      .badge{display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 1.2rem;border-radius:5rem;font-size:1.2rem;font-weight:600}
      .badge.pending{background:#fff3cd;color:#856404}
      .badge.confirmed{background:#d1e7dd;color:#0f5132}
      .badge.cancelled{background:#f8d7da;color:#842029}
      .empty-state{text-align:center;padding:5rem;color:#888;font-size:1.5rem}
      .empty-state i{font-size:4rem;margin-bottom:1rem;display:block;color:#ddd}
   </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
   <div class="sidebar-logo">
      <h1>travel<span>.</span></h1>
      <p>Admin Panel</p>
   </div>
   <div class="sidebar-menu">
      <p class="menu-label">Main</p>
      <a href="dashboard.php" class="menu-item active"><i class="fas fa-chart-pie"></i> Dashboard</a>
      <a href="bookings.php" class="menu-item"><i class="fas fa-calendar-check"></i> Bookings</a>
      <a href="packages.php" class="menu-item"><i class="fas fa-globe"></i> Packages</a>
     <a href="payments.php" class="menu-item"><i class="fas fa-credit-card"></i> Payments</a>
      <a href="users.php" class="menu-item"><i class="fas fa-users"></i> Users</a>
      <p class="menu-label">Site</p>
      <a href="../home.php" class="menu-item" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a>
      <a href="../employee.php" class="menu-item" target="_blank"><i class="fas fa-external-link-alt"></i> Employee Portal</a>
   </div>
   <div class="sidebar-bottom">
      <div class="admin-info">
         <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin_name'],0,1)) ?></div>
         <div class="admin-info-txt">
            <p><?= htmlspecialchars($_SESSION['admin_name']) ?></p>
            <span>Administrator</span>
         </div>
      </div>
      <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
   </div>
</div>

<!-- MAIN -->
<div class="main">
   <div class="topbar">
      <div>
         <h2>Dashboard</h2>
         <p>Welcome back, <?= htmlspecialchars($_SESSION['admin_name']) ?>! Here's what's happening.</p>
      </div>
   </div>

   <!-- STATS -->
   <div class="stats-grid">
      <div class="stat-card">
         <div class="stat-icon gold"><i class="fas fa-calendar-check"></i></div>
         <div class="stat-info"><p>Total Bookings</p><h3><?= $total_bookings ?></h3></div>
      </div>
      <div class="stat-card">
         <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
         <div class="stat-info"><p>Pending</p><h3><?= $pending_bookings ?></h3></div>
      </div>
      <div class="stat-card">
         <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
         <div class="stat-info"><p>Confirmed</p><h3><?= $confirmed_bookings ?></h3></div>
      </div>
      <div class="stat-card">
         <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
         <div class="stat-info"><p>Cancelled</p><h3><?= $cancelled_bookings ?></h3></div>
      </div>
      <div class="stat-card">
         <div class="stat-icon blue"><i class="fas fa-users"></i></div>
         <div class="stat-info"><p>Total Users</p><h3><?= $total_users ?></h3></div>
      </div>
      <div class="stat-card">
         <div class="stat-icon purple"><i class="fas fa-globe"></i></div>
         <div class="stat-info"><p>Total Packages</p><h3><?= $total_packages ?></h3></div>
      </div>
   </div>

   <!-- RECENT BOOKINGS -->
   <div class="section-card">
      <div class="section-head">
         <h3>Recent Bookings</h3>
         <a href="bookings.php">View All →</a>
      </div>
      <table>
         <thead>
            <tr>
               <th>#</th>
               <th>Name</th>
               <th>Email</th>
               <th>Destination</th>
               <th>Arrival</th>
               <th>Guests</th>
               <th>Status</th>
            </tr>
         </thead>
         <tbody>
            <?php if(mysqli_num_rows($recent_bookings) > 0): ?>
               <?php while($b = mysqli_fetch_assoc($recent_bookings)): ?>
               <tr>
                  <td>#<?= $b['id'] ?></td>
                  <td><?= htmlspecialchars($b['name']) ?></td>
                  <td><?= htmlspecialchars($b['email']) ?></td>
                  <td><?= htmlspecialchars($b['location']) ?></td>
                  <td><?= $b['arrival'] ?></td>
                  <td><?= $b['guests'] ?></td>
                  <td><span class="badge <?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
               </tr>
               <?php endwhile; ?>
            <?php else: ?>
               <tr><td colspan="7" class="empty-state"><i class="fas fa-inbox"></i>No bookings yet</td></tr>
            <?php endif; ?>
         </tbody>
      </table>
   </div>
</div>

</body>
</html>