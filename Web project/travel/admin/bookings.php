<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
   header('location:login.php');
   exit();
}
$connection = mysqli_connect('localhost','root','','booking_db');

if(isset($_POST['update_status'])){
   $id     = (int)$_POST['booking_id'];
   $status = $_POST['status'];
   $allowed = ['pending','confirmed','cancelled'];
   if(in_array($status, $allowed)){
      $stmt = mysqli_prepare($connection, "UPDATE bookings SET status=? WHERE id=?");
      mysqli_stmt_bind_param($stmt, "si", $status, $id);
      mysqli_stmt_execute($stmt);
   }
   header('location:bookings.php?msg=updated');
   exit();
}

if(isset($_GET['delete'])){
   $id = (int)$_GET['delete'];
   $stmt = mysqli_prepare($connection, "DELETE FROM bookings WHERE id=?");
   mysqli_stmt_bind_param($stmt, "i", $id);
   mysqli_stmt_execute($stmt);
   header('location:bookings.php?msg=deleted');
   exit();
}

$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
if($status_filter != 'all'){
   $sf = mysqli_real_escape_string($connection, $status_filter);
   $bookings = mysqli_query($connection, "SELECT * FROM bookings WHERE status='$sf' ORDER BY created_at DESC");
} else {
   $bookings = mysqli_query($connection, "SELECT * FROM bookings ORDER BY created_at DESC");
}
$total = mysqli_num_rows($bookings);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookings — Admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   <style>
      *{margin:0;padding:0;box-sizing:border-box;font-family:'DM Sans',sans-serif}
      html{font-size:62.5%}
      body{background:#f5f5f5;min-height:100vh;display:flex}
      .sidebar{width:26rem;background:#0f0f0f;min-height:100vh;position:fixed;top:0;left:0;z-index:100;display:flex;flex-direction:column}
      .sidebar-logo{padding:3rem 2.5rem;border-bottom:1px solid #1a1a1a}
      .sidebar-logo h1{font-size:2.4rem;color:#fff;font-weight:700}
      .sidebar-logo h1 span{color:#c8974a}
      .sidebar-logo p{font-size:1.2rem;color:#666;margin-top:0.4rem}
      .sidebar-menu{padding:2rem 0;flex:1}
      .menu-label{font-size:1.1rem;color:#444;text-transform:uppercase;letter-spacing:1.5px;padding:0 2.5rem;margin:1.5rem 0 0.8rem}
      .menu-item{display:flex;align-items:center;gap:1.4rem;padding:1.3rem 2.5rem;color:#888;font-size:1.4rem;font-weight:500;text-decoration:none;transition:.2s}
      .menu-item:hover,.menu-item.active{background:#1a1a1a;color:#c8974a;border-right:3px solid #c8974a}
      .menu-item i{width:1.8rem;text-align:center;font-size:1.5rem}
      .sidebar-bottom{padding:2rem 2.5rem;border-top:1px solid #1a1a1a}
      .admin-info{display:flex;align-items:center;gap:1.2rem;margin-bottom:1.5rem}
      .admin-avatar{width:4rem;height:4rem;border-radius:50%;background:#c8974a;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.6rem;font-weight:700}
      .admin-info-txt p{font-size:1.3rem;color:#fff;font-weight:600}
      .admin-info-txt span{font-size:1.1rem;color:#666}
      .btn-logout{display:flex;align-items:center;gap:1rem;padding:1rem 1.5rem;background:#1a1a1a;color:#888;border:1px solid #2a2a2a;border-radius:0.8rem;font-size:1.3rem;text-decoration:none;transition:.2s;font-family:'DM Sans',sans-serif;cursor:pointer;width:100%}
      .btn-logout:hover{background:#c0392b;color:#fff;border-color:#c0392b}
      .main{margin-left:26rem;flex:1;padding:3rem}
      .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:3rem}
      .topbar h2{font-size:2.4rem;color:#0f0f0f;font-weight:700}
      .topbar p{font-size:1.4rem;color:#888;margin-top:0.3rem}
      .alert{padding:1.4rem 2rem;border-radius:0.8rem;font-size:1.4rem;margin-bottom:2rem;display:flex;align-items:center;gap:1rem}
      .alert.success{background:#d1e7dd;color:#0f5132;border:1px solid #badbcc}
      .filter-bar{display:flex;gap:1rem;margin-bottom:2rem;flex-wrap:wrap}
      .f-btn{padding:0.9rem 2rem;font-size:1.3rem;border:1.5px solid #e0e0e0;border-radius:5rem;background:#fff;color:#888;cursor:pointer;text-decoration:none;transition:.2s;font-weight:500}
      .f-btn:hover,.f-btn.active{background:#0f0f0f;color:#fff;border-color:#0f0f0f}
      .f-btn.pending.active{background:#856404;border-color:#856404}
      .f-btn.confirmed.active{background:#0f5132;border-color:#0f5132}
      .f-btn.cancelled.active{background:#842029;border-color:#842029}
      .section-card{background:#fff;border-radius:1.6rem;border:1px solid #eee;overflow:hidden}
      .section-head{padding:2rem 2.8rem;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between}
      .section-head h3{font-size:1.8rem;font-weight:700;color:#0f0f0f}
      .section-head span{font-size:1.3rem;color:#888}
      table{width:100%;border-collapse:collapse}
      th{padding:1.4rem 1.6rem;text-align:left;font-size:1.2rem;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:0.8px;border-bottom:1px solid #eee;background:#fafafa}
      td{padding:1.4rem 1.6rem;font-size:1.4rem;color:#333;border-bottom:1px solid #f5f5f5;vertical-align:middle}
      tr:last-child td{border-bottom:none}
      tr:hover td{background:#fafafa}
      .badge{display:inline-flex;align-items:center;padding:0.5rem 1.2rem;border-radius:5rem;font-size:1.2rem;font-weight:600}
      .badge.pending{background:#fff3cd;color:#856404}
      .badge.confirmed{background:#d1e7dd;color:#0f5132}
      .badge.cancelled{background:#f8d7da;color:#842029}
      .actions{display:flex;gap:0.8rem;align-items:center}
      .btn-sm{padding:0.7rem 1.4rem;font-size:1.2rem;border:none;border-radius:0.6rem;cursor:pointer;font-family:'DM Sans',sans-serif;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;transition:.2s}
      .btn-view{background:#e8f4fd;color:#1a6fa8}
      .btn-view:hover{background:#1a6fa8;color:#fff}
      .btn-delete{background:#fde8e8;color:#a81a1a}
      .btn-delete:hover{background:#a81a1a;color:#fff}
      .empty-state{text-align:center;padding:5rem;color:#888;font-size:1.5rem}
      .empty-state i{font-size:4rem;margin-bottom:1rem;display:block;color:#ddd}
      .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center}
      .modal-overlay.show{display:flex}
      .modal{background:#fff;border-radius:1.6rem;width:90%;max-width:60rem;max-height:90vh;overflow-y:auto}
      .modal-head{padding:2.4rem;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between}
      .modal-head h3{font-size:1.8rem;font-weight:700;color:#0f0f0f}
      .modal-close{background:none;border:none;font-size:2rem;cursor:pointer;color:#888;transition:.2s}
      .modal-close:hover{color:#0f0f0f}
      .modal-body{padding:2.4rem}
      .detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.6rem;margin-bottom:2rem}
      .detail-item label{font-size:1.2rem;color:#888;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:0.4rem}
      .detail-item p{font-size:1.4rem;color:#0f0f0f;font-weight:500}
      .status-form{display:flex;gap:1rem;align-items:center;flex-wrap:wrap;padding:2rem;background:#f9f9f9;border-radius:1rem;border:1px solid #eee}
      .status-label{font-size:1.4rem;font-weight:700;color:#0f0f0f}
      .status-select{padding:1rem 1.5rem;font-size:1.4rem;border:1.5px solid #eee;border-radius:0.8rem;font-family:'DM Sans',sans-serif;outline:none;flex:1;cursor:pointer}
      .status-select:focus{border-color:#c8974a}
      .btn-update{padding:1rem 2.4rem;background:#0f0f0f;color:#fff;border:none;border-radius:0.8rem;font-size:1.4rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:.2s}
      .btn-update:hover{background:#c8974a}
   </style>
</head>
<body>

<div class="sidebar">
   <div class="sidebar-logo">
      <h1>travel<span>.</span></h1>
      <p>Admin Panel</p>
   </div>
   <div class="sidebar-menu">
      <p class="menu-label">Main</p>
      <a href="dashboard.php" class="menu-item"><i class="fas fa-chart-pie"></i> Dashboard</a>
      <a href="bookings.php" class="menu-item active"><i class="fas fa-calendar-check"></i> Bookings</a>
      <a href="packages.php" class="menu-item"><i class="fas fa-globe"></i> Packages</a>
      <a href="payments.php" class="menu-item"><i class="fas fa-credit-card"></i> Payments</a>
      <a href="users.php" class="menu-item"><i class="fas fa-users"></i> Users</a>
      <p class="menu-label">Site</p>
      <a href="../home.php" class="menu-item" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a>
    <a href="../employee.php" class="menu-item" target="_blank"><i class="fas fa-external-link-alt"></i> Employee Portal</a>
   </div>
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

<div class="main">
   <div class="topbar">
      <div>
         <h2>Bookings</h2>
         <p>Manage and update all travel bookings</p>
      </div>
   </div>

   <?php if(isset($_GET['msg'])): ?>
   <div class="alert success">
      <i class="fas fa-check-circle"></i>
      <?= $_GET['msg'] == 'updated' ? 'Booking status updated successfully!' : 'Booking deleted successfully!' ?>
   </div>
   <?php endif; ?>

   <div class="filter-bar">
      <a href="bookings.php" class="f-btn <?= $status_filter=='all'?'active':'' ?>">All</a>
      <a href="bookings.php?status=pending" class="f-btn pending <?= $status_filter=='pending'?'active':'' ?>">Pending</a>
      <a href="bookings.php?status=confirmed" class="f-btn confirmed <?= $status_filter=='confirmed'?'active':'' ?>">Confirmed</a>
      <a href="bookings.php?status=cancelled" class="f-btn cancelled <?= $status_filter=='cancelled'?'active':'' ?>">Cancelled</a>
   </div>

   <div class="section-card">
      <div class="section-head">
         <h3>All Bookings</h3>
         <span><?= $total ?> total</span>
      </div>
      <table>
         <thead>
            <tr>
               <th>#</th>
               <th>Name</th>
               <th>Email</th>
               <th>Destination</th>
               <th>Arrival</th>
               <th>Leaving</th>
               <th>Guests</th>
               <th>Status</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php if($total > 0): ?>
               <?php while($b = mysqli_fetch_assoc($bookings)): ?>
               <tr>
                  <td>#<?= $b['id'] ?></td>
                  <td><?= htmlspecialchars($b['name']) ?></td>
                  <td><?= htmlspecialchars($b['email']) ?></td>
                  <td><?= htmlspecialchars($b['location']) ?></td>
                  <td><?= $b['arrival'] ?></td>
                  <td><?= $b['leaving'] ?></td>
                  <td><?= $b['guests'] ?></td>
                  <td><span class="badge <?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                  <td>
                     <div class="actions">
                        <button class="btn-sm btn-view" onclick='viewBooking(<?= json_encode($b) ?>)'>
                           <i class="fas fa-eye"></i> View
                        </button>
                        <a href="bookings.php?delete=<?= $b['id'] ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this booking?')">
                           <i class="fas fa-trash"></i>
                        </a>
                     </div>
                  </td>
               </tr>
               <?php endwhile; ?>
            <?php else: ?>
               <tr><td colspan="9" class="empty-state"><i class="fas fa-inbox"></i>No bookings found</td></tr>
            <?php endif; ?>
         </tbody>
      </table>
   </div>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="bookingModal">
   <div class="modal">
      <div class="modal-head">
         <h3>Booking Details</h3>
         <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
         <div class="detail-grid">
            <div class="detail-item"><label>Name</label><p id="m-name">—</p></div>
            <div class="detail-item"><label>Email</label><p id="m-email">—</p></div>
            <div class="detail-item"><label>Phone</label><p id="m-phone">—</p></div>
            <div class="detail-item"><label>Address</label><p id="m-address">—</p></div>
            <div class="detail-item"><label>Destination</label><p id="m-location">—</p></div>
            <div class="detail-item"><label>Guests</label><p id="m-guests">—</p></div>
            <div class="detail-item"><label>Arrival</label><p id="m-arrival">—</p></div>
            <div class="detail-item"><label>Leaving</label><p id="m-leaving">—</p></div>
            <div class="detail-item"><label>Trip Type</label><p id="m-triptype">—</p></div>
            <div class="detail-item"><label>Accommodation</label><p id="m-accommodation">—</p></div>
            <div class="detail-item"><label>Meal</label><p id="m-meal">—</p></div>
            <div class="detail-item"><label>Transport</label><p id="m-transport">—</p></div>
            <div class="detail-item"><label>Budget</label><p id="m-budget">—</p></div>
            <div class="detail-item"><label>Special Requests</label><p id="m-requests">—</p></div>
         </div>
         <form method="post" class="status-form">
            <input type="hidden" name="booking_id" id="m-id">
            <span class="status-label">Update Status:</span>
            <select name="status" class="status-select" id="m-status">
               <option value="pending">⏳ Pending</option>
               <option value="confirmed">✅ Confirmed</option>
               <option value="cancelled">❌ Cancelled</option>
            </select>
            <button type="submit" name="update_status" class="btn-update">
               <i class="fas fa-save"></i> Update
            </button>
         </form>
      </div>
   </div>
</div>

<script>
function viewBooking(b) {
   document.getElementById('m-name').textContent         = b.name || '—';
   document.getElementById('m-email').textContent        = b.email || '—';
   document.getElementById('m-phone').textContent        = b.phone || '—';
   document.getElementById('m-address').textContent      = b.address || '—';
   document.getElementById('m-location').textContent     = b.location || '—';
   document.getElementById('m-guests').textContent       = b.guests || '—';
   document.getElementById('m-arrival').textContent      = b.arrival || '—';
   document.getElementById('m-leaving').textContent      = b.leaving || '—';
   document.getElementById('m-triptype').textContent     = b.trip_type || '—';
   document.getElementById('m-accommodation').textContent= b.accommodation || '—';
   document.getElementById('m-meal').textContent         = b.meal || '—';
   document.getElementById('m-transport').textContent    = b.transport || '—';
   document.getElementById('m-budget').textContent       = b.budget ? '$'+b.budget : '—';
   document.getElementById('m-requests').textContent     = b.special_requests || 'None';
   document.getElementById('m-id').value                 = b.id;
   document.getElementById('m-status').value             = b.status;
   document.getElementById('bookingModal').classList.add('show');
}
function closeModal() {
   document.getElementById('bookingModal').classList.remove('show');
}
document.getElementById('bookingModal').addEventListener('click', function(e){
   if(e.target === this) closeModal();
});
</script>
</body>
</html>