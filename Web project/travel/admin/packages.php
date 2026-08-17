<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
   header('location:login.php');
   exit();
}
$connection = mysqli_connect('localhost','root','','booking_db');

// Handle Add Package
if(isset($_POST['add_package'])){
   $title       = mysqli_real_escape_string($connection, $_POST['title']);
   $description = mysqli_real_escape_string($connection, $_POST['description']);
   $location    = mysqli_real_escape_string($connection, $_POST['location']);
   $duration    = mysqli_real_escape_string($connection, $_POST['duration']);
   $price       = (float)$_POST['price'];
   $category    = mysqli_real_escape_string($connection, $_POST['category']);
   $image       = mysqli_real_escape_string($connection, $_POST['image']);
   $status      = $_POST['status'];

   $stmt = mysqli_prepare($connection, "INSERT INTO packages (title,description,location,duration,price,category,image,status) VALUES (?,?,?,?,?,?,?,?)");
   mysqli_stmt_bind_param($stmt, "ssssdsss", $title,$description,$location,$duration,$price,$category,$image,$status);
   mysqli_stmt_execute($stmt);
   header('location:packages.php?msg=added');
   exit();
}

// Handle Edit Package
if(isset($_POST['edit_package'])){
   $id          = (int)$_POST['package_id'];
   $title       = mysqli_real_escape_string($connection, $_POST['title']);
   $description = mysqli_real_escape_string($connection, $_POST['description']);
   $location    = mysqli_real_escape_string($connection, $_POST['location']);
   $duration    = mysqli_real_escape_string($connection, $_POST['duration']);
   $price       = (float)$_POST['price'];
   $category    = mysqli_real_escape_string($connection, $_POST['category']);
   $image       = mysqli_real_escape_string($connection, $_POST['image']);
   $status      = $_POST['status'];

   $stmt = mysqli_prepare($connection, "UPDATE packages SET title=?,description=?,location=?,duration=?,price=?,category=?,image=?,status=? WHERE id=?");
   mysqli_stmt_bind_param($stmt, "ssssdsssi", $title,$description,$location,$duration,$price,$category,$image,$status,$id);
   mysqli_stmt_execute($stmt);
   header('location:packages.php?msg=updated');
   exit();
}

// Handle Delete
if(isset($_GET['delete'])){
   $id = (int)$_GET['delete'];
   $stmt = mysqli_prepare($connection, "DELETE FROM packages WHERE id=?");
   mysqli_stmt_bind_param($stmt, "i", $id);
   mysqli_stmt_execute($stmt);
   header('location:packages.php?msg=deleted');
   exit();
}

// Handle Toggle Status
if(isset($_GET['toggle'])){
   $id  = (int)$_GET['toggle'];
   $pkg = mysqli_fetch_assoc(mysqli_query($connection, "SELECT status FROM packages WHERE id=$id"));
   $new = $pkg['status'] == 'active' ? 'inactive' : 'active';
   mysqli_query($connection, "UPDATE packages SET status='$new' WHERE id=$id");
   header('location:packages.php?msg=updated');
   exit();
}

$packages = mysqli_query($connection, "SELECT * FROM packages ORDER BY created_at DESC");
$total    = mysqli_num_rows($packages);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Packages — Admin</title>
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
      .btn-add{display:inline-flex;align-items:center;gap:1rem;padding:1.2rem 2.4rem;background:#0f0f0f;color:#fff;border:none;border-radius:0.8rem;font-size:1.4rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:.2s;text-decoration:none}
      .btn-add:hover{background:#c8974a}
      .alert{padding:1.4rem 2rem;border-radius:0.8rem;font-size:1.4rem;margin-bottom:2rem;display:flex;align-items:center;gap:1rem}
      .alert.success{background:#d1e7dd;color:#0f5132;border:1px solid #badbcc}
      .section-card{background:#fff;border-radius:1.6rem;border:1px solid #eee;overflow:hidden}
      .section-head{padding:2rem 2.8rem;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between}
      .section-head h3{font-size:1.8rem;font-weight:700;color:#0f0f0f}
      .section-head span{font-size:1.3rem;color:#888}
      table{width:100%;border-collapse:collapse}
      th{padding:1.4rem 1.6rem;text-align:left;font-size:1.2rem;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:0.8px;border-bottom:1px solid #eee;background:#fafafa}
      td{padding:1.4rem 1.6rem;font-size:1.4rem;color:#333;border-bottom:1px solid #f5f5f5;vertical-align:middle}
      tr:last-child td{border-bottom:none}
      tr:hover td{background:#fafafa}
      .pkg-img{width:6rem;height:4.5rem;border-radius:0.6rem;object-fit:cover;background:#eee}
      .badge{display:inline-flex;align-items:center;padding:0.5rem 1.2rem;border-radius:5rem;font-size:1.2rem;font-weight:600}
      .badge.active{background:#d1e7dd;color:#0f5132}
      .badge.inactive{background:#f8d7da;color:#842029}
      .cat-badge{display:inline-flex;padding:0.4rem 1rem;border-radius:5rem;font-size:1.2rem;font-weight:600;background:#e8f4fd;color:#1a6fa8}
      .actions{display:flex;gap:0.8rem;align-items:center}
      .btn-sm{padding:0.7rem 1.4rem;font-size:1.2rem;border:none;border-radius:0.6rem;cursor:pointer;font-family:'DM Sans',sans-serif;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;transition:.2s}
      .btn-edit{background:#fff3cd;color:#856404}
      .btn-edit:hover{background:#856404;color:#fff}
      .btn-toggle{background:#e8f4fd;color:#1a6fa8}
      .btn-toggle:hover{background:#1a6fa8;color:#fff}
      .btn-delete{background:#fde8e8;color:#a81a1a}
      .btn-delete:hover{background:#a81a1a;color:#fff}
      .empty-state{text-align:center;padding:5rem;color:#888;font-size:1.5rem}
      .empty-state i{font-size:4rem;margin-bottom:1rem;display:block;color:#ddd}

      /* MODAL */
      .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center}
      .modal-overlay.show{display:flex}
      .modal{background:#fff;border-radius:1.6rem;width:90%;max-width:64rem;max-height:90vh;overflow-y:auto}
      .modal-head{padding:2.4rem;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:10}
      .modal-head h3{font-size:1.8rem;font-weight:700;color:#0f0f0f}
      .modal-close{background:none;border:none;font-size:2rem;cursor:pointer;color:#888}
      .modal-close:hover{color:#0f0f0f}
      .modal-body{padding:2.4rem}
      .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.6rem}
      .form-full{grid-column:1/-1}
      .fld{display:flex;flex-direction:column;gap:0.8rem}
      .fld label{font-size:1.3rem;font-weight:600;color:#333}
      .fld input,.fld select,.fld textarea{padding:1.2rem 1.4rem;font-size:1.4rem;border:1.5px solid #eee;border-radius:0.8rem;font-family:'DM Sans',sans-serif;outline:none;transition:.2s;color:#333}
      .fld input:focus,.fld select:focus,.fld textarea:focus{border-color:#c8974a;box-shadow:0 0 0 3px rgba(200,151,74,0.1)}
      .fld textarea{min-height:8rem;resize:vertical}
      .modal-footer{padding:2rem 2.4rem;border-top:1px solid #eee;display:flex;justify-content:flex-end;gap:1rem}
      .btn-cancel{padding:1.2rem 2.4rem;background:#f5f5f5;color:#333;border:none;border-radius:0.8rem;font-size:1.4rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif}
      .btn-save{padding:1.2rem 2.4rem;background:#0f0f0f;color:#fff;border:none;border-radius:0.8rem;font-size:1.4rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:.2s}
      .btn-save:hover{background:#c8974a}
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
      <a href="packages.php" class="menu-item active"><i class="fas fa-globe"></i> Packages</a>
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
         <h2>Packages</h2>
         <p>Add, edit and manage travel packages</p>
      </div>
      <button class="btn-add" onclick="openAddModal()">
         <i class="fas fa-plus"></i> Add Package
      </button>
   </div>

   <?php if(isset($_GET['msg'])): ?>
   <div class="alert success">
      <i class="fas fa-check-circle"></i>
      <?php
         $msgs = ['added'=>'Package added!','updated'=>'Package updated!','deleted'=>'Package deleted!'];
         echo $msgs[$_GET['msg']] ?? 'Done!';
      ?>
   </div>
   <?php endif; ?>

   <div class="section-card">
      <div class="section-head">
         <h3>All Packages</h3>
         <span><?= $total ?> total</span>
      </div>
      <table>
         <thead>
            <tr>
               <th>#</th>
               <th>Image</th>
               <th>Title</th>
               <th>Location</th>
               <th>Duration</th>
               <th>Price</th>
               <th>Category</th>
               <th>Status</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php if($total > 0): ?>
               <?php while($p = mysqli_fetch_assoc($packages)): ?>
               <tr>
                  <td>#<?= $p['id'] ?></td>
                  <td><img src="../<?= htmlspecialchars($p['image']) ?>" class="pkg-img" onerror="this.src='../images/img-1.jpg'"></td>
                  <td><?= htmlspecialchars($p['title']) ?></td>
                  <td><?= htmlspecialchars($p['location']) ?></td>
                  <td><?= htmlspecialchars($p['duration']) ?></td>
                  <td>$<?= number_format($p['price'],2) ?></td>
                  <td><span class="cat-badge"><?= ucfirst($p['category']) ?></span></td>
                  <td><span class="badge <?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                  <td>
                     <div class="actions">
                        <button class="btn-sm btn-edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($p)) ?>)">
                           <i class="fas fa-edit"></i>
                        </button>
                        <a href="packages.php?toggle=<?= $p['id'] ?>" class="btn-sm btn-toggle">
                           <i class="fas fa-<?= $p['status']=='active'?'eye-slash':'eye' ?>"></i>
                        </a>
                        <a href="packages.php?delete=<?= $p['id'] ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this package?')">
                           <i class="fas fa-trash"></i>
                        </a>
                     </div>
                  </td>
               </tr>
               <?php endwhile; ?>
            <?php else: ?>
               <tr><td colspan="9" class="empty-state"><i class="fas fa-box-open"></i>No packages found</td></tr>
            <?php endif; ?>
         </tbody>
      </table>
   </div>
</div>

<!-- ADD MODAL -->
<div class="modal-overlay" id="addModal">
   <div class="modal">
      <div class="modal-head">
         <h3>Add New Package</h3>
         <button class="modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
      </div>
      <form method="post">
         <div class="modal-body">
            <div class="form-grid">
               <div class="fld form-full">
                  <label>Package Title</label>
                  <input type="text" name="title" placeholder="e.g. Bali Honeymoon Escape" required>
               </div>
               <div class="fld form-full">
                  <label>Description</label>
                  <textarea name="description" placeholder="Package description..." required></textarea>
               </div>
               <div class="fld">
                  <label>Location</label>
                  <input type="text" name="location" placeholder="e.g. Bali, Indonesia" required>
               </div>
               <div class="fld">
                  <label>Duration</label>
                  <input type="text" name="duration" placeholder="e.g. 7 Days" required>
               </div>
               <div class="fld">
                  <label>Price ($)</label>
                  <input type="number" name="price" placeholder="e.g. 1299" step="0.01" required>
               </div>
               <div class="fld">
                  <label>Category</label>
                  <select name="category" required>
                     <option value="">— Select —</option>
                     <option value="adventure">Adventure</option>
                     <option value="honeymoon">Honeymoon</option>
                     <option value="family">Family</option>
                     <option value="luxury">Luxury</option>
                     <option value="beach">Beach</option>
                  </select>
               </div>
               <div class="fld form-full">
                  <label>Image Path</label>
                  <input type="text" name="image" placeholder="e.g. images/img-1.jpg" required>
               </div>
               <div class="fld">
                  <label>Status</label>
                  <select name="status">
                     <option value="active">Active</option>
                     <option value="inactive">Inactive</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal('addModal')">Cancel</button>
            <button type="submit" name="add_package" class="btn-save">Add Package</button>
         </div>
      </form>
   </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editModal">
   <div class="modal">
      <div class="modal-head">
         <h3>Edit Package</h3>
         <button class="modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
      </div>
      <form method="post">
         <input type="hidden" name="package_id" id="e-id">
         <div class="modal-body">
            <div class="form-grid">
               <div class="fld form-full">
                  <label>Package Title</label>
                  <input type="text" name="title" id="e-title" required>
               </div>
               <div class="fld form-full">
                  <label>Description</label>
                  <textarea name="description" id="e-description" required></textarea>
               </div>
               <div class="fld">
                  <label>Location</label>
                  <input type="text" name="location" id="e-location" required>
               </div>
               <div class="fld">
                  <label>Duration</label>
                  <input type="text" name="duration" id="e-duration" required>
               </div>
               <div class="fld">
                  <label>Price ($)</label>
                  <input type="number" name="price" id="e-price" step="0.01" required>
               </div>
               <div class="fld">
                  <label>Category</label>
                  <select name="category" id="e-category">
                     <option value="adventure">Adventure</option>
                     <option value="honeymoon">Honeymoon</option>
                     <option value="family">Family</option>
                     <option value="luxury">Luxury</option>
                     <option value="beach">Beach</option>
                  </select>
               </div>
               <div class="fld form-full">
                  <label>Image Path</label>
                  <input type="text" name="image" id="e-image" required>
               </div>
               <div class="fld">
                  <label>Status</label>
                  <select name="status" id="e-status">
                     <option value="active">Active</option>
                     <option value="inactive">Inactive</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Cancel</button>
            <button type="submit" name="edit_package" class="btn-save">Save Changes</button>
         </div>
      </form>
   </div>
</div>

<script>
function openAddModal(){ document.getElementById('addModal').classList.add('show'); }
function openEditModal(p){
   document.getElementById('e-id').value          = p.id;
   document.getElementById('e-title').value       = p.title;
   document.getElementById('e-description').value = p.description;
   document.getElementById('e-location').value    = p.location;
   document.getElementById('e-duration').value    = p.duration;
   document.getElementById('e-price').value       = p.price;
   document.getElementById('e-category').value    = p.category;
   document.getElementById('e-image').value       = p.image;
   document.getElementById('e-status').value      = p.status;
   document.getElementById('editModal').classList.add('show');
}
function closeModal(id){
   document.getElementById(id).classList.remove('show');
}
document.querySelectorAll('.modal-overlay').forEach(m => {
   m.addEventListener('click', function(e){ if(e.target===this) this.classList.remove('show'); });
});
</script>
</body>
</html>