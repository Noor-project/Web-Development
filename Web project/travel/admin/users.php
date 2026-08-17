<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
   header('location:login.php');
   exit();
}
$connection = mysqli_connect('localhost','root','','booking_db');

// Handle Delete
if(isset($_GET['delete'])){
   $id = (int)$_GET['delete'];
   $stmt = mysqli_prepare($connection, "DELETE FROM users WHERE id=?");
   mysqli_stmt_bind_param($stmt, "i", $id);
   mysqli_stmt_execute($stmt);
   header('location:users.php?msg=deleted');
   exit();
}

$users = mysqli_query($connection, "SELECT u.*, COUNT(b.id) as total_bookings FROM users u LEFT JOIN bookings b ON u.id = b.user_id GROUP BY u.id ORDER BY u.created_at DESC");
$total = mysqli_num_rows($users);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users — Admin</title>
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
      .search-bar{display:flex;gap:1rem;margin-bottom:2rem}
      .search-bar input{flex:1;padding:1.2rem 1.6rem;font-size:1.4rem;border:1.5px solid #eee;border-radius:0.8rem;font-family:'DM Sans',sans-serif;outline:none;transition:.2s;background:#fff}
      .search-bar input:focus{border-color:#c8974a}
      .section-card{background:#fff;border-radius:1.6rem;border:1px solid #eee;overflow:hidden}
      .section-head{padding:2rem 2.8rem;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between}
      .section-head h3{font-size:1.8rem;font-weight:700;color:#0f0f0f}
      .section-head span{font-size:1.3rem;color:#888}
      table{width:100%;border-collapse:collapse}
      th{padding:1.4rem 1.6rem;text-align:left;font-size:1.2rem;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:0.8px;border-bottom:1px solid #eee;background:#fafafa}
      td{padding:1.4rem 1.6rem;font-size:1.4rem;color:#333;border-bottom:1px solid #f5f5f5;vertical-align:middle}
      tr:last-child td{border-bottom:none}
      tr:hover td{background:#fafafa}
      .user-info{display:flex;align-items:center;gap:1.2rem}
      .user-avatar{width:3.8rem;height:3.8rem;border-radius:50%;background:#c8974a;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem;font-weight:700;flex-shrink:0}
      .user-name{font-size:1.4rem;font-weight:600;color:#0f0f0f}
      .user-email{font-size:1.2rem;color:#888}
      .badge{display:inline-flex;align-items:center;padding:0.5rem 1.2rem;border-radius:5rem;font-size:1.2rem;font-weight:600}
      .badge.bookings-0{background:#f5f5f5;color:#888}
      .badge.bookings-plus{background:#d1e7dd;color:#0f5132}
      .actions{display:flex;gap:0.8rem;align-items:center}
      .btn-sm{padding:0.7rem 1.4rem;font-size:1.2rem;border:none;border-radius:0.6rem;cursor:pointer;font-family:'DM Sans',sans-serif;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;transition:.2s}
      .btn-view{background:#e8f4fd;color:#1a6fa8}
      .btn-view:hover{background:#1a6fa8;color:#fff}
      .btn-delete{background:#fde8e8;color:#a81a1a}
      .btn-delete:hover{background:#a81a1a;color:#fff}
      .empty-state{text-align:center;padding:5rem;color:#888;font-size:1.5rem}
      .empty-state i{font-size:4rem;margin-bottom:1rem;display:block;color:#ddd}

      /* MODAL */
      .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center}
      .modal-overlay.show{display:flex}
      .modal{background:#fff;border-radius:1.6rem;width:90%;max-width:50rem;max-height:90vh;overflow-y:auto}
      .modal-head{padding:2.4rem;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between}
      .modal-head h3{font-size:1.8rem;font-weight:700;color:#0f0f0f}
      .modal-close{background:none;border:none;font-size:2rem;cursor:pointer;color:#888}
      .modal-close:hover{color:#0f0f0f}
      .modal-body{padding:2.4rem}
      .user-profile{text-align:center;margin-bottom:2.4rem}
      .profile-avatar{width:8rem;height:8rem;border-radius:50%;background:#c8974a;display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem;font-weight:700;margin:0 auto 1.2rem}
      .profile-name{font-size:2rem;font-weight:700;color:#0f0f0f}
      .profile-email{font-size:1.4rem;color:#888;margin-top:0.4rem}
      .detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.6rem}
      .detail-item label{font-size:1.2rem;color:#888;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:0.4rem}
      .detail-item p{font-size:1.4rem;color:#0f0f0f;font-weight:500}
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
      <a href="dashboard.php" class="menu-item"><i class="fas fa-chart-pie"></i> Dashboard</a>
      <a href="bookings.php" class="menu-item"><i class="fas fa-calendar-check"></i> Bookings</a>
      <a href="packages.php" class="menu-item"><i class="fas fa-globe"></i> Packages</a>
      <a href="payments.php" class="menu-item"><i class="fas fa-credit-card"></i> Payments</a>
      <a href="users.php" class="menu-item active"><i class="fas fa-users"></i> Users</a>
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
         <h2>Users</h2>
         <p>View and manage registered users</p>
      </div>
   </div>

   <?php if(isset($_GET['msg'])): ?>
   <div class="alert success">
      <i class="fas fa-check-circle"></i> User deleted successfully!
   </div>
   <?php endif; ?>

   <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Search users by name or email..." onkeyup="searchUsers()">
   </div>

   <div class="section-card">
      <div class="section-head">
         <h3>All Users</h3>
         <span><?= $total ?> registered</span>
      </div>
      <table id="usersTable">
         <thead>
            <tr>
               <th>#</th>
               <th>User</th>
               <th>Phone</th>
               <th>Bookings</th>
               <th>Joined</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php if($total > 0): ?>
               <?php while($u = mysqli_fetch_assoc($users)): ?>
               <tr>
                  <td>#<?= $u['id'] ?></td>
                  <td>
                     <div class="user-info">
                        <div class="user-avatar"><?= strtoupper(substr($u['name'],0,1)) ?></div>
                        <div>
                           <div class="user-name"><?= htmlspecialchars($u['name']) ?></div>
                           <div class="user-email"><?= htmlspecialchars($u['email']) ?></div>
                        </div>
                     </div>
                  </td>
                  <td><?= htmlspecialchars($u['phone']) ?></td>
                  <td>
                     <span class="badge <?= $u['total_bookings'] > 0 ? 'bookings-plus' : 'bookings-0' ?>">
                        <?= $u['total_bookings'] ?> booking<?= $u['total_bookings'] != 1 ? 's' : '' ?>
                     </span>
                  </td>
                  <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                  <td>
                     <div class="actions">
                        <button class="btn-sm btn-view" onclick="viewUser(<?= htmlspecialchars(json_encode($u)) ?>)">
                           <i class="fas fa-eye"></i> View
                        </button>
                        <a href="users.php?delete=<?= $u['id'] ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this user? This cannot be undone.')">
                           <i class="fas fa-trash"></i>
                        </a>
                     </div>
                  </td>
               </tr>
               <?php endwhile; ?>
            <?php else: ?>
               <tr><td colspan="6" class="empty-state"><i class="fas fa-users"></i>No users registered yet</td></tr>
            <?php endif; ?>
         </tbody>
      </table>
   </div>
</div>

<!-- VIEW MODAL -->
<div class="modal-overlay" id="userModal">
   <div class="modal">
      <div class="modal-head">
         <h3>User Details</h3>
         <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
         <div class="user-profile">
            <div class="profile-avatar" id="m-avatar">A</div>
            <div class="profile-name" id="m-name">—</div>
            <div class="profile-email" id="m-email">—</div>
         </div>
         <div class="detail-grid">
            <div class="detail-item"><label>Phone</label><p id="m-phone">—</p></div>
            <div class="detail-item"><label>Total Bookings</label><p id="m-bookings">—</p></div>
            <div class="detail-item"><label>User ID</label><p id="m-id">—</p></div>
            <div class="detail-item"><label>Joined</label><p id="m-joined">—</p></div>
         </div>
      </div>
   </div>
</div>

<script>
function viewUser(u) {
   const initial = u.name.charAt(0).toUpperCase();
   document.getElementById('m-avatar').textContent    = initial;
   document.getElementById('m-name').textContent      = u.name;
   document.getElementById('m-email').textContent     = u.email;
   document.getElementById('m-phone').textContent     = u.phone || '—';
   document.getElementById('m-bookings').textContent  = u.total_bookings + ' booking(s)';
   document.getElementById('m-id').textContent        = '#' + u.id;
   document.getElementById('m-joined').textContent    = u.created_at;
   document.getElementById('userModal').classList.add('show');
}
function closeModal() {
   document.getElementById('userModal').classList.remove('show');
}
document.getElementById('userModal').addEventListener('click', function(e){
   if(e.target === this) closeModal();
});
function searchUsers() {
   const input  = document.getElementById('searchInput').value.toLowerCase();
   const rows   = document.querySelectorAll('#usersTable tbody tr');
   rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(input) ? '' : 'none';
   });
}
</script>
</body>
</html>