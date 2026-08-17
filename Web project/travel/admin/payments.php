<?php
session_start();
if(!isset($_SESSION['admin_id'])){
  header('location:admin_login.php'); exit();
}
$connection = mysqli_connect('localhost','root','','booking_db');

// Handle approve / reject
if(isset($_GET['action']) && isset($_GET['id'])){
  $pid    = intval($_GET['id']);
  $action = $_GET['action'];

  $pay_row = mysqli_fetch_assoc(mysqli_query($connection,"SELECT booking_id FROM payments WHERE id=$pid"));
  $bid = $pay_row['booking_id'] ?? 0;

  if($action === 'approve'){
    mysqli_query($connection, "UPDATE payments SET status='approved' WHERE id=$pid");
    mysqli_query($connection, "UPDATE bookings SET status='confirmed' WHERE id=$bid");
    header('location:payments.php?msg=approved'); exit();
  } elseif($action === 'reject'){
    mysqli_query($connection, "UPDATE payments SET status='rejected' WHERE id=$pid");
    mysqli_query($connection, "UPDATE bookings SET status='cancelled' WHERE id=$bid");
    header('location:payments.php?msg=rejected'); exit();
  }
  header('location:payments.php'); exit();
}

// Stats
$total_pay   = mysqli_fetch_assoc(mysqli_query($connection,"SELECT COUNT(*) as t FROM payments"))['t'];
$pending_pay = mysqli_fetch_assoc(mysqli_query($connection,"SELECT COUNT(*) as t FROM payments WHERE status='pending'"))['t'];
$approved    = mysqli_fetch_assoc(mysqli_query($connection,"SELECT COUNT(*) as t FROM payments WHERE status='approved'"))['t'];
$rev_res     = mysqli_query($connection,"SELECT SUM(amount) as t FROM payments WHERE status='approved'");
$revenue     = mysqli_fetch_assoc($rev_res)['t'] ?? 0;

$payments = mysqli_query($connection,
  "SELECT pay.id, pay.booking_id, pay.amount, pay.payment_method,
          pay.card_last4, pay.card_name, pay.status, pay.created_at,
          u.name as user_name, u.email as user_email,
          p.title as pkg_title
   FROM payments pay
   JOIN users u ON pay.user_id = u.id
   JOIN bookings b ON pay.booking_id = b.id
   LEFT JOIN packages p ON b.package_id = p.id
   ORDER BY pay.created_at DESC"
);

if(!$payments){
  die('Query error: '.mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Payments — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{--ink:#0f0f0f;--cream:#faf8f5;--accent:#c8974a;--accent2:#8b5e2a;--border:#e8e2da;--muted:#888;--sidebar:#111}
*{margin:0;padding:0;box-sizing:border-box}
html{font-size:62.5%}
body{background:#f5f5f5;font-family:'DM Sans',sans-serif;font-size:1.5rem;display:flex;min-height:100vh}

/* SIDEBAR */
.sidebar{width:26rem;background:var(--sidebar);min-height:100vh;display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:100}
.sb-brand{padding:3rem 2.4rem 2rem;border-bottom:1px solid rgba(255,255,255,0.07)}
.sb-brand .logo{font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:700;color:#fff}
.sb-brand .logo span{color:var(--accent)}
.sb-brand .sub{font-size:1.2rem;color:rgba(255,255,255,0.3);margin-top:0.4rem;letter-spacing:1px;text-transform:uppercase}
.sb-section{padding:2rem 1.6rem 0.8rem;font-size:1.1rem;color:rgba(255,255,255,0.25);letter-spacing:2px;text-transform:uppercase;font-weight:600}
.sb-link{display:flex;align-items:center;gap:1.4rem;padding:1.2rem 1.6rem;margin:0.2rem 0.8rem;border-radius:0.8rem;color:rgba(255,255,255,0.5);text-decoration:none;font-size:1.4rem;font-weight:500;transition:.2s}
.sb-link i{width:1.8rem;font-size:1.5rem;text-align:center}
.sb-link:hover{background:rgba(255,255,255,0.06);color:#fff}
.sb-link.active{background:rgba(200,151,74,0.15);color:var(--accent);border-left:3px solid var(--accent);border-radius:0 0.8rem 0.8rem 0;margin-left:0.8rem;padding-left:1.3rem}
.sb-admin{padding:2rem 2.4rem;border-top:1px solid rgba(255,255,255,0.07);margin-top:auto;display:flex;align-items:center;gap:1.4rem}
.sb-avatar{width:4rem;height:4rem;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:#fff;font-weight:700;font-family:'Playfair Display',serif}
.sb-admin-info strong{display:block;color:#fff;font-size:1.4rem}
.sb-admin-info span{font-size:1.2rem;color:rgba(255,255,255,0.3)}

/* MAIN */
.main-content{margin-left:26rem;flex:1;padding:3.5rem 4rem 6rem}
.page-title{font-family:'Playfair Display',serif;font-size:3rem;color:var(--ink);margin-bottom:0.6rem}
.page-title em{color:var(--accent);font-style:italic}
.page-sub{font-size:1.4rem;color:var(--muted);margin-bottom:3.5rem}

/* STATS */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1.8rem;margin-bottom:3.5rem}
.stat-box{background:#fff;border:1px solid var(--border);border-radius:1.2rem;padding:2.4rem 2rem;text-align:center}
.stat-box .n{font-family:'Playfair Display',serif;font-size:3.2rem;font-weight:700;line-height:1}
.stat-box .n.gold{color:var(--accent)}
.stat-box .n.orange{color:#e67e22}
.stat-box .n.green{color:#2d6a4f}
.stat-box .l{font-size:1.3rem;color:var(--muted);margin-top:0.6rem}

/* TABLE */
.table-box{background:#fff;border:1px solid var(--border);border-radius:1.4rem;overflow:hidden}
.table-box table{width:100%;border-collapse:collapse}
.table-box thead th{background:var(--ink);color:#fff;font-size:1.2rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;padding:1.6rem 1.8rem;text-align:left;white-space:nowrap}
.table-box tbody tr{border-bottom:1px solid var(--border);transition:.2s}
.table-box tbody tr:hover{background:rgba(200,151,74,0.03)}
.table-box tbody tr:last-child{border-bottom:none}
.table-box td{padding:1.4rem 1.8rem;font-size:1.4rem;color:var(--ink);vertical-align:middle}
.td-user strong{display:block;font-weight:600;color:var(--ink)}
.td-user span{font-size:1.2rem;color:var(--muted)}
.td-amount{font-family:'Playfair Display',serif;font-size:1.8rem;color:var(--accent);font-weight:700}
.badge{display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 1.2rem;border-radius:5rem;font-size:1.2rem;font-weight:600;white-space:nowrap}
.badge-pending{background:rgba(230,126,34,0.1);color:#e67e22;border:1px solid rgba(230,126,34,0.3)}
.badge-approved{background:rgba(45,106,79,0.1);color:#2d6a4f;border:1px solid rgba(45,106,79,0.3)}
.badge-rejected{background:rgba(231,76,60,0.1);color:#e74c3c;border:1px solid rgba(231,76,60,0.3)}
.action-btns{display:flex;gap:0.8rem;flex-wrap:wrap}
.btn-approve{padding:0.7rem 1.4rem;background:#2d6a4f;color:#fff;border:none;border-radius:0.6rem;font-size:1.2rem;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;transition:.2s;white-space:nowrap}
.btn-approve:hover{background:#1e4d38}
.btn-reject{padding:0.7rem 1.4rem;background:#e74c3c;color:#fff;border:none;border-radius:0.6rem;font-size:1.2rem;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;transition:.2s;white-space:nowrap}
.btn-reject:hover{background:#c0392b}
.empty-state{text-align:center;padding:6rem 2rem;color:var(--muted)}
.empty-state i{font-size:4rem;color:var(--border);margin-bottom:1.6rem;display:block}
.empty-state p{font-size:1.5rem}

/* CONFIRM MODAL */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
.modal-overlay.show{display:flex}
.modal{background:#fff;border-radius:1.6rem;width:90%;max-width:44rem;overflow:hidden;animation:slideUp .3s cubic-bezier(.34,1.56,.64,1);box-shadow:0 24px 80px rgba(0,0,0,0.2)}
@keyframes slideUp{from{transform:translateY(30px);opacity:0}to{transform:translateY(0);opacity:1}}
.modal-top{padding:3rem 3rem 2rem;text-align:center}
.modal-icon{width:7rem;height:7rem;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 2rem;font-size:2.8rem}
.modal-icon.approve{background:#d1fae5;color:#059669}
.modal-icon.reject{background:#fee2e2;color:#dc2626}
.modal-top h3{font-family:'Playfair Display',serif;font-size:2.2rem;color:var(--ink);margin-bottom:0.8rem}
.modal-top p{font-size:1.4rem;color:var(--muted);line-height:1.7}
.modal-body{padding:0 3rem 1.6rem}
.modal-detail{background:#f9f9f9;border-radius:1rem;padding:1.6rem 2rem;border:1px solid var(--border);margin-bottom:0.8rem}
.modal-detail-row{display:flex;justify-content:space-between;font-size:1.3rem;padding:0.5rem 0;border-bottom:1px solid #eee}
.modal-detail-row:last-child{border-bottom:none}
.modal-detail-row .lbl{color:var(--muted)}
.modal-detail-row .val{font-weight:600;color:var(--ink)}
.modal-footer{padding:2rem 3rem;display:flex;gap:1.2rem;border-top:1px solid var(--border);background:#fafafa}
.btn-modal-confirm{flex:1;padding:1.3rem;font-size:1.4rem;font-weight:700;border:none;border-radius:0.8rem;cursor:pointer;font-family:'DM Sans',sans-serif;transition:.2s;display:flex;align-items:center;justify-content:center;gap:0.8rem}
.btn-modal-confirm.approve{background:#2d6a4f;color:#fff}
.btn-modal-confirm.approve:hover{background:#1e4d38}
.btn-modal-confirm.reject{background:#e74c3c;color:#fff}
.btn-modal-confirm.reject:hover{background:#c0392b}
.btn-modal-cancel{flex:1;padding:1.3rem;font-size:1.4rem;font-weight:600;border:1.5px solid var(--border);border-radius:0.8rem;cursor:pointer;font-family:'DM Sans',sans-serif;background:#fff;color:var(--muted);transition:.2s}
.btn-modal-cancel:hover{background:var(--ink);color:#fff;border-color:var(--ink)}

/* SUCCESS / REJECT RESULT MODAL */
.result-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1001;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
.result-modal.show{display:flex}
.result-box{background:#fff;border-radius:1.6rem;width:90%;max-width:42rem;overflow:hidden;animation:slideUp .3s cubic-bezier(.34,1.56,.64,1);box-shadow:0 24px 80px rgba(0,0,0,0.2);text-align:center;padding:4rem 3rem}
.result-icon{width:8rem;height:8rem;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 2.4rem;font-size:3.2rem;animation:scaleIn .4s cubic-bezier(.34,1.56,.64,1)}
@keyframes scaleIn{from{transform:scale(0)}to{transform:scale(1)}}
.result-icon.success{background:#d1fae5;color:#059669}
.result-icon.danger{background:#fee2e2;color:#dc2626}
.result-box h3{font-family:'Playfair Display',serif;font-size:2.4rem;color:var(--ink);margin-bottom:1rem}
.result-box p{font-size:1.4rem;color:var(--muted);line-height:1.8;margin-bottom:2.4rem}
.result-badge{display:inline-flex;align-items:center;gap:0.8rem;padding:0.8rem 2rem;border-radius:5rem;font-size:1.3rem;font-weight:600;margin-bottom:2.4rem}
.result-badge.success{background:#d1fae5;color:#065f46;border:1px solid #6ee7b7}
.result-badge.danger{background:#fee2e2;color:#7f1d1d;border:1px solid #fca5a5}
.btn-result-close{padding:1.2rem 4rem;background:var(--ink);color:#fff;border:none;border-radius:0.8rem;font-size:1.4rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:.2s}
.btn-result-close:hover{background:var(--accent)}

@media(max-width:900px){
  .sidebar{width:7rem}
  .sb-brand .sub,.sb-link span,.sb-admin-info,.sb-section{display:none}
  .sb-brand .logo{font-size:1.8rem}
  .main-content{margin-left:7rem;padding:2rem}
  .stats-row{grid-template-columns:1fr 1fr}
  .table-box{overflow-x:auto}
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sb-brand">
    <div class="logo">travel<span>.</span></div>
    <div class="sub">Admin Panel</div>
  </div>
  <div class="sb-section">Main</div>
<a href="dashboard.php" class="sb-link"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a>  <a href="bookings.php" class="sb-link"><i class="fas fa-calendar-check"></i> <span>Bookings</span></a>
  <a href="packages.php" class="sb-link"><i class="fas fa-globe"></i> <span>Packages</span></a>
  <a href="payments.php" class="sb-link active"><i class="fas fa-credit-card"></i> <span>Payments</span></a>
  <a href="users.php" class="sb-link"><i class="fas fa-users"></i> <span>Users</span></a>
  <div class="sb-section">Site</div>
  <a href="../home.php" target="_blank" class="sb-link"><i class="fas fa-external-link-alt"></i> <span>View Website</span></a>
  <a href="../employee.php" target="_blank" class="sb-link"><i class="fas fa-external-link-alt"></i> <span>Employee Portal</span></a>
  <div class="sb-admin">
    <div class="sb-avatar">A</div>
    <div class="sb-admin-info">
      <strong>Admin</strong>
      <span>Administrator</span>
    </div>
  </div>
</aside>

<!-- MAIN -->
<div class="main-content">
  <div class="page-title">Payment <em>Management</em></div>
  <p class="page-sub">Review and approve or reject customer payment submissions.</p>

  <!-- STATS -->
  <div class="stats-row">
    <div class="stat-box"><div class="n gold"><?= $total_pay ?></div><div class="l">Total Payments</div></div>
    <div class="stat-box"><div class="n orange"><?= $pending_pay ?></div><div class="l">Pending Review</div></div>
    <div class="stat-box"><div class="n green"><?= $approved ?></div><div class="l">Approved</div></div>
    <div class="stat-box"><div class="n gold">$<?= number_format($revenue, 0) ?></div><div class="l">Total Revenue</div></div>
  </div>

  <!-- TABLE -->
  <div class="table-box">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Customer</th>
          <th>Package</th>
          <th>Amount</th>
          <th>Method</th>
          <th>Card (Last 4)</th>
          <th>Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php if(mysqli_num_rows($payments) === 0): ?>
        <tr><td colspan="9">
          <div class="empty-state">
            <i class="fas fa-credit-card"></i>
            <p>No payments submitted yet.</p>
          </div>
        </td></tr>
      <?php else: ?>
      <?php while($pay = mysqli_fetch_assoc($payments)): ?>
        <tr>
          <td style="color:var(--muted);font-size:1.3rem">#<?= $pay['id'] ?></td>
          <td class="td-user">
            <strong><?= htmlspecialchars($pay['user_name']) ?></strong>
            <span><?= htmlspecialchars($pay['user_email']) ?></span>
          </td>
          <td style="font-size:1.3rem"><?= htmlspecialchars($pay['pkg_title'] ?? 'N/A') ?></td>
          <td class="td-amount">$<?= number_format($pay['amount'], 2) ?></td>
          <td style="text-transform:capitalize;font-size:1.3rem"><?= htmlspecialchars($pay['payment_method']) ?></td>
          <td style="font-size:1.3rem;color:var(--muted)">
            <?= $pay['card_last4'] ? '•••• '.$pay['card_last4'] : '—' ?>
          </td>
          <td style="font-size:1.3rem;color:var(--muted);white-space:nowrap">
            <?= date('d M Y', strtotime($pay['created_at'])) ?><br>
            <span style="font-size:1.1rem"><?= date('h:i A', strtotime($pay['created_at'])) ?></span>
          </td>
          <td>
            <?php if($pay['status']==='pending'): ?>
              <span class="badge badge-pending"><i class="fas fa-clock"></i> Pending</span>
            <?php elseif($pay['status']==='approved'): ?>
              <span class="badge badge-approved"><i class="fas fa-check"></i> Approved</span>
            <?php else: ?>
              <span class="badge badge-rejected"><i class="fas fa-times"></i> Rejected</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if($pay['status']==='pending'): ?>
            <div class="action-btns">
              <button class="btn-approve"
                onclick="openConfirm('approve', <?= $pay['id'] ?>, '<?= htmlspecialchars($pay['user_name']) ?>', '$<?= number_format($pay['amount'],2) ?>', '<?= htmlspecialchars($pay['payment_method']) ?>')">
                <i class="fas fa-check"></i> Approve
              </button>
              <button class="btn-reject"
                onclick="openConfirm('reject', <?= $pay['id'] ?>, '<?= htmlspecialchars($pay['user_name']) ?>', '$<?= number_format($pay['amount'],2) ?>', '<?= htmlspecialchars($pay['payment_method']) ?>')">
                <i class="fas fa-times"></i> Reject
              </button>
            </div>
            <?php else: ?>
              <span style="font-size:1.3rem;color:var(--muted)">—</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- CONFIRM MODAL -->
<div class="modal-overlay" id="confirmModal">
  <div class="modal">
    <div class="modal-top">
      <div class="modal-icon" id="modalIcon"><i id="modalIconI" class="fas fa-check"></i></div>
      <h3 id="modalTitle">Approve Payment?</h3>
      <p id="modalDesc">Are you sure you want to approve this payment? The booking will be marked as confirmed.</p>
    </div>
    <div class="modal-body">
      <div class="modal-detail">
        <div class="modal-detail-row"><span class="lbl">Customer</span><span class="val" id="mdCustomer">—</span></div>
        <div class="modal-detail-row"><span class="lbl">Amount</span><span class="val" id="mdAmount">—</span></div>
        <div class="modal-detail-row"><span class="lbl">Method</span><span class="val" id="mdMethod">—</span></div>
        <div class="modal-detail-row"><span class="lbl">Result</span><span class="val" id="mdResult">Booking → Confirmed</span></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-modal-cancel" onclick="closeConfirm()"><i class="fas fa-times"></i> Cancel</button>
      <a id="modalActionBtn" href="#" class="btn-modal-confirm approve"><i class="fas fa-check"></i> Confirm</a>
    </div>
  </div>
</div>

<!-- RESULT MODAL (shown after page reloads with ?msg=) -->
<?php if(isset($_GET['msg'])): ?>
<div class="result-modal show" id="resultModal">
  <div class="result-box">
    <?php if($_GET['msg']==='approved'): ?>
      <div class="result-icon success" id="resultIcon"><i class="fas fa-check"></i></div>
      <h3>Payment Approved!</h3>
      <p>The payment has been successfully approved.<br>The booking status has been updated to <strong>Confirmed</strong>.</p>
      <div class="result-badge success"><i class="fas fa-check-circle"></i> Booking is now Confirmed</div>
    <?php else: ?>
      <div class="result-icon danger" id="resultIcon"><i class="fas fa-times"></i></div>
      <h3>Payment Rejected</h3>
      <p>The payment has been rejected.<br>The booking status has been updated to <strong>Cancelled</strong>.</p>
      <div class="result-badge danger"><i class="fas fa-times-circle"></i> Booking is now Cancelled</div>
    <?php endif; ?>
    <br>
    <button class="btn-result-close" onclick="closeResult()">Done</button>
  </div>
</div>
<?php endif; ?>

<script>
let currentAction = '';
let currentId = 0;

function openConfirm(action, id, customer, amount, method) {
  currentAction = action;
  currentId = id;

  const isApprove = action === 'approve';

  document.getElementById('modalIcon').className    = 'modal-icon ' + (isApprove ? 'approve' : 'reject');
  document.getElementById('modalIconI').className   = 'fas fa-' + (isApprove ? 'check' : 'times');
  document.getElementById('modalTitle').textContent = isApprove ? 'Approve Payment?' : 'Reject Payment?';
  document.getElementById('modalDesc').textContent  = isApprove
    ? 'Are you sure you want to approve this payment? The booking will be marked as Confirmed.'
    : 'Are you sure you want to reject this payment? The booking will be marked as Cancelled.';
  document.getElementById('mdCustomer').textContent = customer;
  document.getElementById('mdAmount').textContent   = amount;
  document.getElementById('mdMethod').textContent   = method;
  document.getElementById('mdResult').textContent   = isApprove ? 'Booking → Confirmed' : 'Booking → Cancelled';

  const btn = document.getElementById('modalActionBtn');
  btn.href = 'payments.php?action=' + action + '&id=' + id;
  btn.className = 'btn-modal-confirm ' + (isApprove ? 'approve' : 'reject');
  btn.innerHTML = isApprove
    ? '<i class="fas fa-check"></i> Yes, Approve'
    : '<i class="fas fa-times"></i> Yes, Reject';

  document.getElementById('confirmModal').classList.add('show');
  document.body.style.overflow = 'hidden';
}

function closeConfirm() {
  document.getElementById('confirmModal').classList.remove('show');
  document.body.style.overflow = '';
}

function closeResult() {
  document.getElementById('resultModal').classList.remove('show');
  document.body.style.overflow = '';
  // Clean URL
  window.history.replaceState({}, document.title, 'payments.php');
}

// Close modals on backdrop click
document.getElementById('confirmModal').addEventListener('click', function(e){
  if(e.target === this) closeConfirm();
});

<?php if(isset($_GET['msg'])): ?>
document.body.style.overflow = 'hidden';
// Auto close result after 6 seconds
setTimeout(() => closeResult(), 6000);
<?php endif; ?>
</script>

</body>
</html>