<?php
session_start();

// ─── DB connection (adjust to your existing connection file) ───────────────
// require_once 'db.php';

// ─── HANDLE POST ACTIONS ──────────────────────────────────────────────────
// ADD EMPLOYEE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_employee') {
    // In production, INSERT into your DB here. For now we store in session.
    if (!isset($_SESSION['extra_employees'])) $_SESSION['extra_employees'] = [];
    $newId = 'EMP' . str_pad(count($_SESSION['extra_employees']) + 100, 3, '0', STR_PAD_LEFT);
    $_SESSION['extra_employees'][] = [
        "id"          => $newId,
        "name"        => trim($_POST['name'] ?? ''),
        "role"        => trim($_POST['role'] ?? ''),
        "dept"        => trim($_POST['dept'] ?? ''),
        "email"       => trim($_POST['email'] ?? ''),
        "phone"       => trim($_POST['phone'] ?? ''),
        "salary"      => intval($_POST['salary'] ?? 0),
        "status"      => trim($_POST['status'] ?? 'active'),
        "joinDate"    => trim($_POST['join_date'] ?? date('Y-m-d')),
        "leaves"      => intval($_POST['leaves'] ?? 0),
        "performance" => intval($_POST['performance'] ?? 85),
    ];
    header("Location: employee.php?tab=employees&toast=Employee+added+successfully");
    exit();
}

// EDIT EMPLOYEE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_employee') {
    // In production, UPDATE your DB here
    $editId = $_POST['edit_id'] ?? '';
    if (isset($_SESSION['extra_employees'])) {
        foreach ($_SESSION['extra_employees'] as &$e) {
            if ($e['id'] === $editId) {
                $e['name']        = trim($_POST['name'] ?? $e['name']);
                $e['email']       = trim($_POST['email'] ?? $e['email']);
                $e['phone']       = trim($_POST['phone'] ?? $e['phone']);
                $e['joinDate']    = trim($_POST['join_date'] ?? $e['joinDate']);
                $e['dept']        = trim($_POST['dept'] ?? $e['dept']);
                $e['role']        = trim($_POST['role'] ?? $e['role']);
                $e['status']      = trim($_POST['status'] ?? $e['status']);
                $e['salary']      = intval($_POST['salary'] ?? $e['salary']);
                $e['performance'] = intval($_POST['performance'] ?? $e['performance']);
                $e['leaves']      = intval($_POST['leaves'] ?? $e['leaves']);
            }
        }
    }
    // Also handle edits to static employees via session overrides
    if (!isset($_SESSION['emp_overrides'])) $_SESSION['emp_overrides'] = [];
    $_SESSION['emp_overrides'][$editId] = [
        "name"        => trim($_POST['name'] ?? ''),
        "role"        => trim($_POST['role'] ?? ''),
        "dept"        => trim($_POST['dept'] ?? ''),
        "email"       => trim($_POST['email'] ?? ''),
        "phone"       => trim($_POST['phone'] ?? ''),
        "salary"      => intval($_POST['salary'] ?? 0),
        "status"      => trim($_POST['status'] ?? 'active'),
        "joinDate"    => trim($_POST['join_date'] ?? date('Y-m-d')),
        "leaves"      => intval($_POST['leaves'] ?? 0),
        "performance" => intval($_POST['performance'] ?? 85),
    ];
    header("Location: employee.php?tab=employees&toast=Employee+updated+successfully");
    exit();
}

// SUBMIT LEAVE REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_leave') {
    if (!isset($_SESSION['extra_leaves'])) $_SESSION['extra_leaves'] = [];
    $from = $_POST['from_date'] ?? date('Y-m-d');
    $to   = $_POST['to_date']   ?? date('Y-m-d');
    $days = max(1, (int)((strtotime($to) - strtotime($from)) / 86400) + 1);
    $newLid = 'LR' . str_pad(count($_SESSION['extra_leaves']) + 100, 3, '0', STR_PAD_LEFT);
    $_SESSION['extra_leaves'][] = [
        "id"     => $newLid,
        "emp"    => trim($_POST['emp'] ?? ''),
        "type"   => trim($_POST['leave_type'] ?? 'Annual Leave'),
        "from"   => $from,
        "to"     => $to,
        "days"   => $days,
        "reason" => trim($_POST['reason'] ?? ''),
        "status" => "pending",
    ];
    header("Location: employee.php?tab=leave&toast=Leave+request+submitted");
    exit();
}

// HANDLE LEAVE APPROVE / REJECT via GET
if (isset($_GET['leave_action']) && isset($_GET['leave_id'])) {
    $lid    = $_GET['leave_id'];
    $action = $_GET['leave_action']; // 'approve' or 'reject'
    if (!isset($_SESSION['leave_overrides'])) $_SESSION['leave_overrides'] = [];
    $_SESSION['leave_overrides'][$lid] = ($action === 'approve') ? 'approved' : 'rejected';
    $filter = $_GET['leave_filter'] ?? 'All';
    header("Location: employee.php?tab=leave&leave_filter=$filter&toast=Leave+" . $action . "d+successfully");
    exit();
}

// HANDLE EMPLOYEE DELETE via GET
if (isset($_GET['delete'])) {
    $delId = $_GET['delete'];
    // Remove from session extra employees
    if (isset($_SESSION['extra_employees'])) {
        $_SESSION['extra_employees'] = array_values(array_filter($_SESSION['extra_employees'], fn($e) => $e['id'] !== $delId));
    }
    // Mark static employee as deleted
    if (!isset($_SESSION['deleted_employees'])) $_SESSION['deleted_employees'] = [];
    $_SESSION['deleted_employees'][] = $delId;
    header("Location: employee.php?tab=employees&toast=Employee+removed");
    exit();
}

// ─── SAMPLE DATA (replace with your DB queries) ───────────────────────────
$static_employees = [
  ["id"=>"EMP001","name"=>"ABC Company",   "role"=>"CEO & Founder",              "dept"=>"Tour Operations","email"=>"aryan@travel.com", "phone"=>"+92 300 1234567","salary"=>280000,"status"=>"active","joinDate"=>"2010-03-15","leaves"=>5, "performance"=>98],
  ["id"=>"EMP002","name"=>"Sara Ahmed",     "role"=>"Head of Destinations",       "dept"=>"Tour Operations","email"=>"sara@travel.com",  "phone"=>"+92 321 2345678","salary"=>185000,"status"=>"active","joinDate"=>"2012-07-01","leaves"=>3, "performance"=>95],
  ["id"=>"EMP003","name"=>"Omar Khalid",    "role"=>"Customer Experience Manager","dept"=>"Customer Service","email"=>"omar@travel.com", "phone"=>"+92 333 3456789","salary"=>145000,"status"=>"remote","joinDate"=>"2015-01-10","leaves"=>8, "performance"=>90],
  ["id"=>"EMP004","name"=>"Nadia Rauf",     "role"=>"Luxury Travel Specialist",   "dept"=>"Tour Operations","email"=>"nadia@travel.com", "phone"=>"+92 345 4567890","salary"=>160000,"status"=>"active","joinDate"=>"2016-06-20","leaves"=>2, "performance"=>92],
  ["id"=>"EMP005","name"=>"Hassan Malik",   "role"=>"Finance Director",           "dept"=>"Finance",        "email"=>"hassan@travel.com","phone"=>"+92 311 5678901","salary"=>195000,"status"=>"active","joinDate"=>"2013-09-05","leaves"=>4, "performance"=>88],
  ["id"=>"EMP006","name"=>"Zara Khan",      "role"=>"Digital Marketing Manager",  "dept"=>"Marketing",      "email"=>"zara@travel.com",  "phone"=>"+92 322 6789012","salary"=>130000,"status"=>"leave", "joinDate"=>"2018-03-22","leaves"=>12,"performance"=>85],
  ["id"=>"EMP007","name"=>"Bilal Tariq",    "role"=>"IT Systems Lead",            "dept"=>"IT & Systems",   "email"=>"bilal@travel.com", "phone"=>"+92 335 7890123","salary"=>155000,"status"=>"active","joinDate"=>"2017-11-14","leaves"=>1, "performance"=>94],
  ["id"=>"EMP008","name"=>"Ayesha Siddiqui","role"=>"Senior Tour Guide",          "dept"=>"Guides",         "email"=>"ayesha@travel.com","phone"=>"+92 312 8901234","salary"=>95000, "status"=>"active","joinDate"=>"2019-05-30","leaves"=>6, "performance"=>89],
  ["id"=>"EMP009","name"=>"Kamran Ali",     "role"=>"Logistics Coordinator",      "dept"=>"Logistics",      "email"=>"kamran@travel.com","phone"=>"+92 344 9012345","salary"=>88000, "status"=>"inactive","joinDate"=>"2020-08-01","leaves"=>0,"performance"=>72],
  ["id"=>"EMP010","name"=>"Fatima Zahra",   "role"=>"HR Specialist",              "dept"=>"HR",             "email"=>"fatima@travel.com","phone"=>"+92 301 0123456","salary"=>112000,"status"=>"active","joinDate"=>"2021-02-15","leaves"=>3, "performance"=>87],
];

// Apply session overrides and deletions to static employees
$deleted = $_SESSION['deleted_employees'] ?? [];
$overrides = $_SESSION['emp_overrides'] ?? [];
$employees = [];
foreach ($static_employees as $e) {
    if (in_array($e['id'], $deleted)) continue;
    if (isset($overrides[$e['id']])) {
        $e = array_merge($e, $overrides[$e['id']]);
    }
    $employees[] = $e;
}
// Append dynamically added employees
foreach (($_SESSION['extra_employees'] ?? []) as $e) {
    if (!in_array($e['id'], $deleted)) $employees[] = $e;
}

$static_leave_requests = [
  ["id"=>"LR001","emp"=>"Sara Ahmed",     "type"=>"Annual Leave",   "from"=>"2025-05-20","to"=>"2025-05-25","days"=>5,"reason"=>"Family vacation",    "status"=>"pending"],
  ["id"=>"LR002","emp"=>"Omar Khalid",    "type"=>"Sick Leave",     "from"=>"2025-05-18","to"=>"2025-05-19","days"=>2,"reason"=>"Medical appointment","status"=>"approved"],
  ["id"=>"LR003","emp"=>"Zara Khan",      "type"=>"Annual Leave",   "from"=>"2025-05-15","to"=>"2025-05-22","days"=>7,"reason"=>"Personal travel",    "status"=>"approved"],
  ["id"=>"LR004","emp"=>"Ayesha Siddiqui","type"=>"Emergency Leave","from"=>"2025-05-21","to"=>"2025-05-21","days"=>1,"reason"=>"Family emergency",   "status"=>"pending"],
  ["id"=>"LR005","emp"=>"Kamran Ali",     "type"=>"Casual Leave",   "from"=>"2025-05-23","to"=>"2025-05-23","days"=>1,"reason"=>"Personal work",      "status"=>"rejected"],
];

// Apply leave overrides and append session leaves
$leaveOverrides = $_SESSION['leave_overrides'] ?? [];
$leave_requests = [];
foreach ($static_leave_requests as $r) {
    if (isset($leaveOverrides[$r['id']])) $r['status'] = $leaveOverrides[$r['id']];
    $leave_requests[] = $r;
}
foreach (($_SESSION['extra_leaves'] ?? []) as $r) {
    if (isset($leaveOverrides[$r['id']])) $r['status'] = $leaveOverrides[$r['id']];
    $leave_requests[] = $r;
}

// ─── HELPERS ──────────────────────────────────────────────────────────────
function initials($name) {
    $parts = explode(" ", trim($name));
    $ini = "";
    foreach ($parts as $p) $ini .= strtoupper(substr($p,0,1));
    return substr($ini,0,2);
}
$avatarColors = ["#c8974a","#2d6a4f","#2980b9","#8b5e2a","#c0392b","#7d3c98","#117864","#1a5276"];
function avatarColor($name, $colors) { return $colors[ord($name[0]) % count($colors)]; }
function fmtSalary($n) { return "PKR " . number_format($n); }
function statusBadge($s) {
    $map = ["active"=>"active","remote"=>"remote","leave"=>"leave","inactive"=>"inactive"];
    $cls = $map[$s] ?? "inactive";
    return "<span class='status-badge $cls'>$s</span>";
}

// Safe JSON for inline onclick — produces a JS object literal without HTML-breaking quotes
function empJson($emp) {
    return htmlspecialchars(json_encode($emp, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8');
}

// ─── STATS ────────────────────────────────────────────────────────────────
$total   = count($employees);
$active  = count(array_filter($employees, fn($e)=>$e['status']==='active'));
$onLeave = count(array_filter($employees, fn($e)=>$e['status']==='leave'));
$remote  = count(array_filter($employees, fn($e)=>$e['status']==='remote'));
$payroll = array_sum(array_column($employees,'salary'));
$pendingLeaves = count(array_filter($leave_requests, fn($r)=>$r['status']==='pending'));
$maxSalary = $payroll > 0 ? max(array_column($employees,'salary')) : 0;
$avgSalary = $total > 0 ? round($payroll / $total) : 0;

$depts = ["Tour Operations","Customer Service","Finance","Marketing","IT & Systems","HR","Guides","Logistics"];
$deptCounts = [];
foreach($depts as $d) {
    $c = count(array_filter($employees, fn($e)=>$e['dept']===$d));
    if ($c > 0) $deptCounts[] = ["dept"=>$d,"count"=>$c];
}
usort($deptCounts, fn($a,$b)=>$b['count']-$a['count']);

$activeTab   = $_GET['tab'] ?? 'dashboard';
$leaveFilter = $_GET['leave_filter'] ?? 'All';

// Toast from redirect
$redirectToast = $_GET['toast'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Portal — Travel Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --ink:     #0f0f0f;
  --cream:   #faf8f5;
  --stone:   #f2ede8;
  --muted:   #888880;
  --accent:  #c8974a;
  --accent2: #8b5e2a;
  --green:   #2d6a4f;
  --green-l: #d8f3dc;
  --red:     #c0392b;
  --red-l:   #fdecea;
  --border:  #e8e2da;
  --shadow:  0 4px 32px rgba(0,0,0,0.07);
  --r:       1.2rem;
}
*{box-sizing:border-box;margin:0;padding:0}
html{font-size:62.5%}
body{font-family:'DM Sans',sans-serif;background:var(--stone);color:var(--ink);font-size:1.5rem;line-height:1.6}

/* ── SIDEBAR ── */
.ems-sidebar{position:fixed;top:0;left:0;bottom:0;width:26rem;background:var(--ink);display:flex;flex-direction:column;z-index:200;border-right:1px solid rgba(255,255,255,0.05);transition:transform .3s ease}
.ems-sidebar.collapsed{transform:translateX(-26rem)}
.sidebar-brand{padding:2.8rem 2.8rem 2rem;border-bottom:1px solid rgba(255,255,255,0.06);flex-shrink:0}
.brand-logo{font-family:'Playfair Display',serif;font-size:2.6rem;font-weight:700;color:#fff;letter-spacing:-0.5px;text-decoration:none;display:block}
.brand-logo span{color:var(--accent)}
.brand-sub{font-size:1.15rem;color:rgba(255,255,255,0.35);margin-top:0.3rem;font-weight:400;letter-spacing:1.5px;text-transform:uppercase}
.sidebar-nav{flex:1;overflow-y:auto;padding:2rem 0;scrollbar-width:none}
.sidebar-nav::-webkit-scrollbar{display:none}
.nav-section-label{font-size:1rem;font-weight:700;color:rgba(255,255,255,0.25);text-transform:uppercase;letter-spacing:2px;padding:1.6rem 2.8rem 0.8rem}
.nav-item{display:flex;align-items:center;gap:1.4rem;padding:1.2rem 2.8rem;font-size:1.4rem;font-weight:500;color:rgba(255,255,255,0.5);cursor:pointer;transition:all .2s;border-left:3px solid transparent;text-decoration:none}
.nav-item:hover{color:rgba(255,255,255,0.85);background:rgba(255,255,255,0.03)}
.nav-item.active{color:#fff;background:rgba(200,151,74,0.1);border-left-color:var(--accent)}
.nav-icon{font-size:1.6rem;width:2rem;text-align:center}
.nav-badge{margin-left:auto;background:var(--accent);color:#fff;font-size:1rem;font-weight:700;padding:0.2rem 0.8rem;border-radius:5rem;min-width:2rem;text-align:center}
.nav-badge.red{background:var(--red)}
.sidebar-footer{padding:2rem 2.8rem;border-top:1px solid rgba(255,255,255,0.06);flex-shrink:0}
.sidebar-user{display:flex;align-items:center;gap:1.2rem}
.user-avatar-sm{width:4rem;height:4rem;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:1.6rem;color:#fff;font-weight:700;flex-shrink:0}
.user-info .name{font-size:1.4rem;font-weight:600;color:#fff}
.user-info .role{font-size:1.2rem;color:rgba(255,255,255,0.4)}

/* ── TOPBAR ── */
.ems-topbar{position:fixed;top:0;left:26rem;right:0;height:7rem;background:rgba(250,248,245,0.92);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 3.2rem;z-index:100;gap:2rem;transition:left .3s ease}
.ems-topbar.expanded{left:0}
.topbar-toggle{background:none;border:none;cursor:pointer;font-size:2rem;color:var(--muted);padding:0.6rem;border-radius:0.6rem;transition:.2s}
.topbar-toggle:hover{color:var(--ink);background:var(--stone)}
.topbar-title{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--ink);letter-spacing:-0.3px}
.topbar-title em{font-style:italic;color:var(--accent)}
.topbar-search{margin-left:auto;display:flex;align-items:center;gap:0.8rem;background:var(--stone);border:1.5px solid var(--border);border-radius:0.8rem;padding:0.8rem 1.4rem;font-size:1.4rem;color:var(--muted);min-width:28rem}
.topbar-search input{border:none;background:none;outline:none;font-size:1.4rem;font-family:'DM Sans',sans-serif;color:var(--ink);flex:1}
.topbar-actions{display:flex;align-items:center;gap:1.2rem}
.topbar-btn{width:4rem;height:4rem;border-radius:0.8rem;background:var(--stone);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:1.6rem;cursor:pointer;transition:.2s;color:var(--muted);position:relative}
.topbar-btn:hover{background:var(--ink);color:#fff;border-color:var(--ink)}

/* ── MAIN ── */
.ems-main{margin-left:26rem;margin-top:7rem;padding:3.6rem;min-height:calc(100vh - 7rem);transition:margin-left .3s ease}
.ems-main.expanded{margin-left:0}

/* ── STAT CARDS ── */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:2rem;margin-bottom:3rem}
.stat-card{background:linear-gradient(145deg,#ffffff,#f8f5f1);border-radius:1.6rem;border:1px solid var(--border);padding:2.4rem;transition:.25s;position:relative;overflow:hidden;box-shadow:0 15px 35px rgba(0,0,0,0.05)}
.stat-card:hover{transform:translateY(-4px);box-shadow:var(--shadow)}
.stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--accent)}
.stat-card.green::before{background:var(--green)}
.stat-card.red::before{background:var(--red)}
.stat-card.blue::before{background:#2980b9}
.stat-card::after{content:'';position:absolute;width:120px;height:120px;background:rgba(200,151,74,0.05);border-radius:50%;right:-30px;top:-30px}
.stat-label{font-size:1.25rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:1rem}
.stat-number{font-family:'Playfair Display',serif;font-size:4rem;font-weight:700;color:var(--ink);line-height:1;margin-bottom:0.8rem}
.stat-number span{color:var(--accent)}
.stat-change{font-size:1.2rem;display:flex;align-items:center;gap:0.5rem}
.stat-change.up{color:var(--green)}
.stat-change.down{color:var(--red)}
.stat-icon{position:absolute;bottom:2rem;right:2.4rem;font-size:4rem;opacity:0.06;color:var(--ink)}

/* ── SECTION HEADER ── */
.section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem}
.section-title{font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:700;color:var(--ink)}
.section-title em{font-style:italic;color:var(--accent)}

/* ── BUTTONS ── */
.btn{border:none;outline:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-weight:600;border-radius:.8rem;transition:.25s ease;display:inline-flex;align-items:center;justify-content:center;gap:.6rem;text-decoration:none}
.btn-sm{padding:.9rem 1.6rem;font-size:1.3rem}
.btn-xs{padding:.6rem 1rem;font-size:1.2rem}
.btn-accent{background:var(--accent);color:#fff}
.btn-accent:hover{background:var(--accent2)}
.btn-outline{background:#fff;border:1.5px solid var(--border);color:var(--ink)}
.btn-outline:hover{border-color:var(--accent);color:var(--accent)}
.btn-danger{background:var(--red);color:#fff}
.btn-danger:hover{background:#a93226}
.btn-green{background:var(--green);color:#fff}
.btn-green:hover{background:#1f513b}

/* ── DASHBOARD GRID ── */
.dashboard-grid{display:grid;grid-template-columns:2fr 1fr;gap:2rem;margin-bottom:2rem}
.analytics-card{background:#fff;border-radius:2rem;padding:2.4rem;border:1px solid rgba(0,0,0,0.05);box-shadow:0 12px 40px rgba(0,0,0,0.06);position:relative;overflow:hidden;transition:.3s ease}
.analytics-card:hover{transform:translateY(-5px)}
.analytics-card.large{min-height:42rem}
.card-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem}
.card-head h3{font-family:'Playfair Display',serif;font-size:2rem}
.card-head span{background:rgba(200,151,74,0.12);color:var(--accent);padding:.7rem 1.3rem;border-radius:5rem;font-size:1.2rem;font-weight:600}
.analytics-card canvas{width:100%!important;height:320px!important}

/* ── FILTER BAR ── */
.filter-bar{display:flex;align-items:center;gap:1.2rem;flex-wrap:wrap;background:#fff;border:1px solid var(--border);border-radius:1.2rem;padding:1.4rem 2rem;margin-bottom:2rem}
.filter-label{font-size:1.3rem;font-weight:600;color:var(--muted);white-space:nowrap}
.filter-btn{padding:0.7rem 1.6rem;font-size:1.3rem;font-family:'DM Sans',sans-serif;border:1.5px solid var(--border);border-radius:5rem;background:#fff;color:var(--muted);cursor:pointer;transition:.2s;font-weight:500;text-decoration:none;display:inline-block}
.filter-btn:hover{border-color:var(--accent);color:var(--accent)}
.filter-btn.active{background:var(--accent);border-color:var(--accent);color:#fff;font-weight:600}

/* ── TABLE ── */
.table-wrap{background:#fff;border-radius:1.6rem;border:1px solid var(--border);overflow:hidden;overflow-x:auto;-webkit-overflow-scrolling:touch}
.data-table{width:100%;border-collapse:collapse;min-width:80rem}
.data-table th{background:var(--stone);padding:1.4rem 1.8rem;font-size:1.2rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.8px;text-align:left;border-bottom:1px solid var(--border);white-space:nowrap}
.data-table td{padding:1.4rem 1.8rem;font-size:1.4rem;color:var(--ink);border-bottom:1px solid rgba(232,226,218,0.5);vertical-align:middle}
.data-table tr:last-child td{border-bottom:none}
.data-table tr:hover td{background:rgba(250,248,245,0.6)}
.emp-name{font-weight:600;color:var(--ink)}
.emp-id{font-size:1.2rem;color:var(--muted);margin-top:0.2rem}

/* ── AVATAR ── */
.emp-avatar{width:3.8rem;height:3.8rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:#fff;flex-shrink:0}

/* ── STATUS BADGE ── */
.status-badge{display:inline-flex;align-items:center;gap:0.5rem;padding:0.4rem 1.2rem;border-radius:5rem;font-size:1.2rem;font-weight:600}
.status-badge::before{content:'';width:0.6rem;height:0.6rem;border-radius:50%}
.status-badge.active{background:var(--green-l);color:var(--green)}
.status-badge.active::before{background:var(--green)}
.status-badge.inactive{background:#fdecea;color:var(--red)}
.status-badge.inactive::before{background:var(--red)}
.status-badge.leave{background:#fff3cd;color:#856404}
.status-badge.leave::before{background:#ffc107}
.status-badge.remote{background:rgba(200,151,74,0.1);color:var(--accent)}
.status-badge.remote::before{background:var(--accent)}
.status-badge.approved{background:var(--green-l);color:var(--green)}
.status-badge.approved::before{background:var(--green)}
.status-badge.pending{background:#fff3cd;color:#856404}
.status-badge.pending::before{background:#ffc107}
.status-badge.rejected{background:#fdecea;color:var(--red)}
.status-badge.rejected::before{background:var(--red)}

/* ── CHART CARD ── */
.chart-card{background:#fff;border-radius:1.6rem;border:1px solid var(--border);padding:2.4rem}
.chart-title{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;margin-bottom:0.4rem}
.chart-sub{font-size:1.3rem;color:var(--muted);margin-bottom:2rem}
.bar-row{display:flex;align-items:center;gap:1.4rem;margin-bottom:1.2rem}
.bar-label{font-size:1.3rem;color:var(--ink);width:11rem;flex-shrink:0;font-weight:500}
.bar-track{flex:1;background:var(--stone);border-radius:5rem;height:0.8rem}
.bar-fill{height:100%;border-radius:5rem;background:var(--accent)}
.bar-val{font-size:1.3rem;font-weight:700;color:var(--ink);width:3.5rem;text-align:right}

/* ── TIMELINE ── */
.timeline{position:relative;padding-left:3rem}
.timeline::before{content:'';position:absolute;left:1rem;top:0;bottom:0;width:2px;background:var(--border)}
.tl-item{position:relative;padding-bottom:2.4rem}
.tl-dot{position:absolute;left:-2.3rem;top:0.4rem;width:1.4rem;height:1.4rem;border-radius:50%;background:var(--accent);border:2px solid #fff;box-shadow:0 0 0 2px var(--accent)}
.tl-dot.green{background:var(--green);box-shadow:0 0 0 2px var(--green)}
.tl-head{font-size:1.4rem;font-weight:600;color:var(--ink)}
.tl-meta{font-size:1.25rem;color:var(--muted);margin-top:0.2rem}

/* ── MODAL ── */
.modal-overlay{position:fixed;inset:0;z-index:500;background:rgba(15,15,15,0.65);backdrop-filter:blur(6px);display:flex;align-items:center;justify-content:center;padding:2rem}
.modal{background:#fff;border-radius:2rem;width:100%;max-width:64rem;max-height:90vh;overflow-y:auto;box-shadow:0 24px 80px rgba(0,0,0,0.2);scrollbar-width:none;position:relative;user-select:none}
.modal::-webkit-scrollbar{display:none}
.modal-head{padding:2.8rem 3.2rem 2rem;background:var(--ink);position:sticky;top:0;z-index:2;display:flex;align-items:flex-start;justify-content:space-between;cursor:grab;border-radius:2rem 2rem 0 0}
.modal-head:active{cursor:grabbing}
.modal-drag-hint{font-size:1.1rem;color:rgba(255,255,255,0.25);margin-top:0.6rem;display:flex;align-items:center;gap:0.4rem;letter-spacing:0.5px}
.modal-head h3{font-family:'Playfair Display',serif;font-size:2.2rem;color:#fff}
.modal-head h3 em{font-style:italic;color:var(--accent)}
.modal-head p{font-size:1.3rem;color:rgba(255,255,255,0.45);margin-top:0.3rem}
.modal-close{background:rgba(255,255,255,0.08);border:none;cursor:pointer;width:3.6rem;height:3.6rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:rgba(255,255,255,0.6);transition:.2s;flex-shrink:0}
.modal-close:hover{background:rgba(255,255,255,0.15);color:#fff}
.modal-body{padding:3.2rem}
.modal-foot{padding:2rem 3.2rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:1.2rem;background:var(--stone)}

/* ── FORM ── */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:2rem}
.fld{display:flex;flex-direction:column;gap:0.7rem;margin-bottom:0.4rem}
.fld label{font-size:1.3rem;font-weight:600;color:var(--ink)}
.fld input,.fld select,.fld textarea{padding:1.2rem 1.4rem;font-size:1.4rem;font-family:'DM Sans',sans-serif;border:1.5px solid var(--border);border-radius:0.8rem;background:#fafafa;color:var(--ink);outline:none;transition:.2s}
.fld input:focus,.fld select:focus,.fld textarea:focus{border-color:var(--accent);background:#fff;box-shadow:0 0 0 3px rgba(200,151,74,0.1)}
.fld textarea{min-height:8rem;resize:vertical}
.section-divider{margin:2rem 0 1.6rem;padding:1.2rem 1.6rem;background:var(--stone);border-radius:0.8rem;border-left:3px solid var(--accent);font-size:1.3rem;font-weight:600;color:var(--accent);display:flex;align-items:center;gap:0.8rem}

/* ── TABS ── */
.tabs{display:flex;gap:0.4rem;background:var(--stone);border-radius:1rem;padding:0.4rem;margin-bottom:2.4rem;width:fit-content}
.tab{padding:0.9rem 2rem;font-size:1.35rem;font-weight:500;border-radius:0.7rem;cursor:pointer;transition:.2s;color:var(--muted);border:none;background:none;font-family:'DM Sans',sans-serif;text-decoration:none;display:inline-block}
.tab.active{background:#fff;color:var(--ink);font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,0.08)}

/* ── PAYROLL ── */
.action-btns{display:flex;gap:0.6rem}

/* ── TOAST ── */
#toast-wrap{position:fixed;bottom:3rem;right:3rem;z-index:900;display:flex;flex-direction:column;gap:1rem;pointer-events:none}
.toast{background:var(--ink);color:#fff;padding:1.4rem 2rem;border-radius:1rem;font-size:1.4rem;font-weight:500;display:flex;align-items:center;gap:1rem;border-left:3px solid var(--accent);pointer-events:all;box-shadow:0 8px 32px rgba(0,0,0,0.2);animation:toastIn .3s ease}
.toast.success{border-left-color:var(--green)}
.toast.error{border-left-color:var(--red)}
@keyframes toastIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}

/* ── EMPTY STATE ── */
.empty-state{text-align:center;padding:6rem 2rem}
.empty-icon{font-size:5rem;margin-bottom:1.6rem;opacity:.3;display:block}
.empty-state h3{font-family:'Playfair Display',serif;font-size:2rem;margin-bottom:0.8rem}
.empty-state p{font-size:1.4rem;color:var(--muted)}

/* ── PROCESS PAYROLL MODAL ── */
.payroll-summary-row{display:flex;justify-content:space-between;align-items:center;padding:1.2rem 0;border-bottom:1px solid var(--border)}
.payroll-summary-row:last-child{border-bottom:none;font-weight:700;font-size:1.6rem}

/* ── RESPONSIVE ── */
@media(max-width:1200px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:992px){.dashboard-grid{grid-template-columns:1fr}}
@media(max-width:768px){
  .ems-sidebar{transform:translateX(-26rem)}
  .ems-sidebar.mobile-open{transform:translateX(0)}
  .ems-topbar{left:0}.ems-main{margin-left:0;padding:2rem}
  .stats-grid{grid-template-columns:1fr 1fr}
  .form-grid{grid-template-columns:1fr}
  .topbar-search{display:none}
}
</style>
</head>
<body>

<!-- ═══ SIDEBAR ═══ -->
<aside class="ems-sidebar" id="sidebar">
  <div class="sidebar-brand">
    <span class="brand-logo">travel<span>.</span></span>
    <div class="brand-sub">Admin Portal</div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Main Menu</div>
    <a href="?tab=dashboard"    class="nav-item <?= $activeTab==='dashboard'   ? 'active' : '' ?>"><span class="nav-icon">📊</span>Dashboard</a>
    <a href="?tab=employees"    class="nav-item <?= $activeTab==='employees'   ? 'active' : '' ?>"><span class="nav-icon">👥</span>Employees</a>
    <a href="?tab=leave"        class="nav-item <?= $activeTab==='leave'       ? 'active' : '' ?>"><span class="nav-icon">📅</span>Leave<?php if($pendingLeaves>0): ?><span class="nav-badge red"><?= $pendingLeaves ?></span><?php endif; ?></a>
    <a href="?tab=payroll"      class="nav-item <?= $activeTab==='payroll'     ? 'active' : '' ?>"><span class="nav-icon">💰</span>Payroll</a>
    <a href="?tab=performance"  class="nav-item <?= $activeTab==='performance' ? 'active' : '' ?>"><span class="nav-icon">📈</span>Performance</a>
    <a href="/travel/home.php"  target="_blank" class="nav-item"><span class="nav-icon">🌍</span><span>View Website</span></a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="user-avatar-sm">A</div>
      <div class="user-info">
        <div class="name">ABC Company</div>
        <div class="role">Super Admin</div>
      </div>
    </div>
  </div>
</aside>

<!-- ═══ TOPBAR ═══ -->
<header class="ems-topbar" id="topbar">
  <button class="topbar-toggle" onclick="toggleSidebar()">☰</button>
  <div class="topbar-title">Employee <em>Portal</em></div>

  <form class="topbar-search" method="GET" action="">
    <span>🔍</span>
    <input name="search" placeholder="Search employees…" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <input type="hidden" name="tab" value="<?= htmlspecialchars($activeTab) ?>">
  </form>

  <div class="topbar-actions">
    <button class="topbar-btn" onclick="showToast('<?= $pendingLeaves ?> pending leave request(s)','error')" title="Alerts">🔔</button>
    <button class="btn btn-accent btn-sm" onclick="openModal('modal-add')">+ Add Employee</button>
  </div>
</header>

<!-- ═══ MAIN ═══ -->
<main class="ems-main" id="main">

  <!-- ── SUB-NAV TABS ── -->
  <div class="tabs" style="margin-bottom:2.8rem">
    <?php foreach(['dashboard'=>'📊 Dashboard','employees'=>'👥 Employees','leave'=>'📅 Leave','payroll'=>'💰 Payroll','performance'=>'📈 Performance'] as $t=>$lbl): ?>
      <a href="?tab=<?= $t ?>" class="tab <?= $activeTab===$t?'active':'' ?>"><?= $lbl ?></a>
    <?php endforeach; ?>
  </div>

  <!-- ════════════════════
       DASHBOARD TAB
  ════════════════════ -->
  <?php if($activeTab==='dashboard'): ?>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Employees</div>
      <div class="stat-number"><?= $total ?><span>+</span></div>
      <div class="stat-change up">↑ 2 this month</div>
      <div class="stat-icon">👥</div>
    </div>
    <div class="stat-card green">
      <div class="stat-label">Active Staff</div>
      <div class="stat-number" style="color:var(--green)"><?= $active ?></div>
      <div class="stat-change up">↑ On duty today</div>
      <div class="stat-icon">✅</div>
    </div>
    <div class="stat-card red">
      <div class="stat-label">Pending Leaves</div>
      <div class="stat-number" style="color:var(--red)"><?= $pendingLeaves ?></div>
      <div class="stat-change down">⚠ Needs approval</div>
      <div class="stat-icon">📅</div>
    </div>
    <div class="stat-card blue">
      <div class="stat-label">Monthly Payroll</div>
      <div class="stat-number" style="font-size:2.8rem;color:#2980b9"><?= number_format($payroll/100000,1) ?><span>L</span></div>
      <div class="stat-change" style="color:var(--muted)">PKR <?= number_format($payroll) ?></div>
      <div class="stat-icon">💰</div>
    </div>
  </div>

  <div class="dashboard-grid">
    <div class="analytics-card large">
      <div class="card-head"><h3>Workforce Overview</h3><span>2025 Analytics</span></div>
      <canvas id="workforceChart"></canvas>
    </div>
    <div style="display:flex;flex-direction:column;gap:2rem">
      <div class="analytics-card">
        <div class="card-head"><h3>Employee Status</h3></div>
        <canvas id="statusChart"></canvas>
      </div>
      <div class="analytics-card">
        <div class="card-head"><h3>Payroll Distribution</h3></div>
        <canvas id="payrollChart"></canvas>
      </div>
    </div>
  </div>

  <div class="analytics-card" style="margin-bottom:2rem">
    <div class="card-head"><h3>Recent Activity</h3><span>Live Updates</span></div>
    <div class="timeline">
      <?php foreach([
        ['Fatima Zahra joined HR team','2 days ago','green'],
        ['Zara Khan leave approved','3 days ago',''],
        ['Payroll processed for May','5 days ago','green'],
        ['Performance reviews completed','1 week ago','']
      ] as [$txt,$time,$cls]): ?>
      <div class="tl-item">
        <div class="tl-dot <?= $cls ?>"></div>
        <div class="tl-head"><?= $txt ?></div>
        <div class="tl-meta"><?= $time ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="section-header">
    <div class="section-title">Recently <em>Added</em></div>
    <a href="?tab=employees" class="btn btn-outline btn-sm">View All →</a>
  </div>
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>Employee</th><th>Department</th><th>Role</th><th>Status</th><th>Performance</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach(array_slice($employees,0,5) as $emp): ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:1.2rem">
              <div class="emp-avatar" style="background:<?= avatarColor($emp['name'],$avatarColors) ?>"><?= initials($emp['name']) ?></div>
              <div><div class="emp-name"><?= htmlspecialchars($emp['name']) ?></div><div class="emp-id"><?= $emp['id'] ?></div></div>
            </div>
          </td>
          <td><?= htmlspecialchars($emp['dept']) ?></td>
          <td style="color:var(--muted)"><?= htmlspecialchars($emp['role']) ?></td>
          <td><?= statusBadge($emp['status']) ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:0.8rem">
              <div style="flex:1;background:var(--stone);border-radius:5rem;height:0.6rem">
                <div style="width:<?= $emp['performance'] ?>%;height:100%;border-radius:5rem;background:<?= $emp['performance']>=90?'var(--green)':($emp['performance']>=75?'var(--accent)':'var(--red)') ?>"></div>
              </div>
              <span style="font-size:1.25rem;font-weight:600"><?= $emp['performance'] ?>%</span>
            </div>
          </td>
          <td>
            <div class="action-btns">
              <button class="btn btn-outline btn-xs" onclick='viewEmployee(<?= empJson($emp) ?>)'>View</button>
              <button class="btn btn-outline btn-xs" onclick='editEmployee(<?= empJson($emp) ?>)'>Edit</button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- ════════════════════
       EMPLOYEES TAB
  ════════════════════ -->
  <?php elseif($activeTab==='employees'):
    $search = $_GET['search'] ?? '';
    $deptF  = $_GET['dept']   ?? 'All';
    $statF  = $_GET['status'] ?? 'All';
    $filtered = array_filter($employees, function($e) use($search,$deptF,$statF){
      $s = strtolower($search);
      $ms = !$s || str_contains(strtolower($e['name']),$s)||str_contains(strtolower($e['role']),$s)||str_contains(strtolower($e['dept']),$s)||str_contains(strtolower($e['id']),$s);
      $md = $deptF==='All'||$e['dept']===$deptF;
      $mst= $statF==='All'||$e['status']===$statF;
      return $ms&&$md&&$mst;
    });
  ?>

  <div class="filter-bar">
    <span class="filter-label">Dept:</span>
    <?php foreach(array_merge(['All'],$depts) as $d): ?>
      <a href="?tab=employees&dept=<?= urlencode($d) ?>&status=<?= urlencode($statF) ?>&search=<?= urlencode($search) ?>"
         class="filter-btn <?= $deptF===$d?'active':'' ?>"><?= htmlspecialchars($d) ?></a>
    <?php endforeach; ?>
    <span class="filter-label" style="margin-left:1rem">Status:</span>
    <?php foreach(['All','active','remote','leave','inactive'] as $s): ?>
      <a href="?tab=employees&dept=<?= urlencode($deptF) ?>&status=<?= urlencode($s) ?>&search=<?= urlencode($search) ?>"
         class="filter-btn <?= $statF===$s?'active':'' ?>"><?= $s ?></a>
    <?php endforeach; ?>
    <div style="margin-left:auto;display:flex;gap:0.8rem">
      <button class="btn btn-accent btn-sm" onclick="openModal('modal-add')">+ Add Employee</button>
    </div>
  </div>

  <div style="margin-bottom:1.4rem;color:var(--muted);font-size:1.3rem">
    Showing <strong style="color:var(--ink)"><?= count($filtered) ?></strong> of <?= $total ?> employees
  </div>

  <div class="table-wrap">
    <?php if(empty($filtered)): ?>
      <div class="empty-state">
        <span class="empty-icon">👥</span>
        <h3>No employees found</h3>
        <p>Try adjusting your search or filters</p>
      </div>
    <?php else: ?>
    <table class="data-table">
      <thead><tr><th>Employee</th><th>Department</th><th>Role</th><th>Contact</th><th>Salary</th><th>Status</th><th>Performance</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($filtered as $emp): ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:1.2rem">
              <div class="emp-avatar" style="background:<?= avatarColor($emp['name'],$avatarColors) ?>"><?= initials($emp['name']) ?></div>
              <div><div class="emp-name"><?= htmlspecialchars($emp['name']) ?></div><div class="emp-id"><?= $emp['id'] ?></div></div>
            </div>
          </td>
          <td><?= htmlspecialchars($emp['dept']) ?></td>
          <td style="color:var(--muted)"><?= htmlspecialchars($emp['role']) ?></td>
          <td style="font-size:1.3rem">
            <div><?= htmlspecialchars($emp['email']) ?></div>
            <div style="color:var(--muted)"><?= htmlspecialchars($emp['phone']) ?></div>
          </td>
          <td style="font-family:'Playfair Display',serif;font-weight:700"><?= fmtSalary($emp['salary']) ?></td>
          <td><?= statusBadge($emp['status']) ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:0.8rem">
              <div style="width:6rem;background:var(--stone);border-radius:5rem;height:0.6rem">
                <div style="width:<?= $emp['performance'] ?>%;height:100%;border-radius:5rem;background:<?= $emp['performance']>=90?'var(--green)':($emp['performance']>=75?'var(--accent)':'var(--red)') ?>"></div>
              </div>
              <span style="font-size:1.25rem;font-weight:600"><?= $emp['performance'] ?>%</span>
            </div>
          </td>
          <td>
            <div class="action-btns">
              <button class="btn btn-outline btn-xs" onclick='viewEmployee(<?= empJson($emp) ?>)'>👁 View</button>
              <button class="btn btn-outline btn-xs" onclick='editEmployee(<?= empJson($emp) ?>)'>✏️ Edit</button>
              <a href="?tab=employees&delete=<?= $emp['id'] ?>&dept=<?= urlencode($deptF) ?>&status=<?= urlencode($statF) ?>"
                 class="btn btn-danger btn-xs"
                 onclick="return confirm('Remove <?= addslashes(htmlspecialchars($emp['name'])) ?>?')">🗑</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <!-- ════════════════════
       LEAVE TAB
  ════════════════════ -->
  <?php elseif($activeTab==='leave'): ?>

  <div class="section-header">
    <div class="section-title">Leave <em>Requests</em></div>
    <button class="btn btn-accent btn-sm" onclick="openModal('modal-leave')">+ Submit Request</button>
  </div>

  <!-- Leave stats -->
  <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:2rem">
    <?php
      $lPending  = count(array_filter($leave_requests, fn($r)=>$r['status']==='pending'));
      $lApproved = count(array_filter($leave_requests, fn($r)=>$r['status']==='approved'));
      $lRejected = count(array_filter($leave_requests, fn($r)=>$r['status']==='rejected'));
    ?>
    <div class="stat-card"><div class="stat-label">Total Requests</div><div class="stat-number"><?= count($leave_requests) ?></div></div>
    <div class="stat-card"><div class="stat-label">Pending</div><div class="stat-number" style="color:#856404"><?= $lPending ?></div><div class="stat-change down">Needs action</div></div>
    <div class="stat-card green"><div class="stat-label">Approved</div><div class="stat-number" style="color:var(--green)"><?= $lApproved ?></div></div>
    <div class="stat-card red"><div class="stat-label">Rejected</div><div class="stat-number" style="color:var(--red)"><?= $lRejected ?></div></div>
  </div>

  <div class="tabs">
    <?php foreach(['All','pending','approved','rejected'] as $t): ?>
      <a href="?tab=leave&leave_filter=<?= $t ?>" class="tab <?= $leaveFilter===$t?'active':'' ?>"><?= ucfirst($t) ?></a>
    <?php endforeach; ?>
  </div>

  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>Ref</th><th>Employee</th><th>Type</th><th>From</th><th>To</th><th>Days</th><th>Reason</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php
        $hasRows = false;
        foreach($leave_requests as $req):
          if($leaveFilter!=='All' && $req['status']!==$leaveFilter) continue;
          $hasRows = true;
          $sc = $req['status']==='approved'?'approved':($req['status']==='rejected'?'rejected':'pending');
        ?>
        <tr>
          <td style="font-weight:600;color:var(--accent)"><?= $req['id'] ?></td>
          <td><?= htmlspecialchars($req['emp']) ?></td>
          <td><?= htmlspecialchars($req['type']) ?></td>
          <td><?= $req['from'] ?></td>
          <td><?= $req['to'] ?></td>
          <td style="font-weight:700;font-family:'Playfair Display',serif"><?= $req['days'] ?></td>
          <td style="color:var(--muted);max-width:16rem"><?= htmlspecialchars($req['reason']) ?></td>
          <td><span class="status-badge <?= $sc ?>"><?= $req['status'] ?></span></td>
          <td>
            <?php if($req['status']==='pending'): ?>
            <div class="action-btns">
              <a href="?tab=leave&leave_action=approve&leave_id=<?= urlencode($req['id']) ?>&leave_filter=<?= $leaveFilter ?>"
                 class="btn btn-green btn-sm"
                 onclick="return confirm('Approve leave for <?= addslashes(htmlspecialchars($req['emp'])) ?>?')">✓ Approve</a>
              <a href="?tab=leave&leave_action=reject&leave_id=<?= urlencode($req['id']) ?>&leave_filter=<?= $leaveFilter ?>"
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('Reject leave for <?= addslashes(htmlspecialchars($req['emp'])) ?>?')">✗ Reject</a>
            </div>
            <?php else: ?><span style="font-size:1.2rem;color:var(--muted)">—</span><?php endif; ?>
          </td>
        </tr>
        <?php endforeach;
        if(!$hasRows): ?>
        <tr><td colspan="9"><div class="empty-state"><span class="empty-icon">📅</span><h3>No <?= $leaveFilter !== 'All' ? $leaveFilter : '' ?> requests</h3><p>No leave requests match the selected filter.</p></div></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- ════════════════════
       PAYROLL TAB
  ════════════════════ -->
  <?php elseif($activeTab==='payroll'): ?>

  <div class="section-header">
    <div class="section-title">Payroll <em>Center</em></div>
    <div style="display:flex;gap:1rem">
      <button class="btn btn-outline btn-sm" onclick="exportPayrollPDF()">📥 Export PDF</button>
      <button class="btn btn-accent btn-sm" onclick="openModal('modal-payroll')">▶ Process Payroll</button>
    </div>
  </div>

  <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:2.4rem">
    <div class="stat-card">
      <div class="stat-label">Total Monthly</div>
      <div class="stat-number" style="font-size:3rem">PKR <?= number_format($payroll/100000,1) ?><span>L</span></div>
      <div class="stat-change" style="color:var(--muted)"><?= $total ?> employees</div>
    </div>
    <div class="stat-card green">
      <div class="stat-label">Highest Salary</div>
      <div class="stat-number" style="font-size:2.8rem;color:var(--green)"><?= fmtSalary($maxSalary) ?></div>
      <div class="stat-change" style="color:var(--muted)">Top position</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Average Salary</div>
      <div class="stat-number" style="font-size:2.8rem"><?= fmtSalary($avgSalary) ?></div>
      <div class="stat-change" style="color:var(--muted)">per employee</div>
    </div>
  </div>

  <?php $empPayroll = $employees; usort($empPayroll,fn($a,$b)=>$b['salary']-$a['salary']); ?>
  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>Employee</th><th>Department</th><th>Role</th><th>Basic Salary</th><th>Leaves Deduct</th><th>Net Salary</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach($empPayroll as $emp):
          $deduct = $emp['leaves'] * round($emp['salary']/30);
          $net    = $emp['salary'] - ($emp['status']==='inactive' ? $deduct : 0);
          $paidClass = ($emp['status']==='active'||$emp['status']==='remote') ? 'approved' : 'pending';
          $paidStatus = ($emp['status']==='active'||$emp['status']==='remote') ? 'paid' : 'pending';
        ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:1.2rem">
              <div class="emp-avatar" style="width:3.4rem;height:3.4rem;font-size:1.3rem;background:<?= avatarColor($emp['name'],$avatarColors) ?>"><?= initials($emp['name']) ?></div>
              <div><div class="emp-name"><?= htmlspecialchars($emp['name']) ?></div><div class="emp-id"><?= $emp['id'] ?></div></div>
            </div>
          </td>
          <td><?= htmlspecialchars($emp['dept']) ?></td>
          <td style="color:var(--muted)"><?= htmlspecialchars($emp['role']) ?></td>
          <td style="font-family:'Playfair Display',serif;font-weight:700"><?= fmtSalary($emp['salary']) ?></td>
          <td style="color:var(--red)"><?= ($emp['leaves']>0&&$emp['status']==='inactive')?'-'.fmtSalary($deduct):'—' ?></td>
          <td style="font-family:'Playfair Display',serif;font-weight:700;color:var(--green)"><?= fmtSalary($net) ?></td>
          <td><span class="status-badge <?= $paidClass ?>"><?= $paidStatus ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- ════════════════════
       PERFORMANCE TAB
  ════════════════════ -->
  <?php elseif($activeTab==='performance'): ?>

  <div class="section-header">
    <div class="section-title">Performance <em>Tracker</em></div>
    <button class="btn btn-outline btn-sm" onclick="exportPerformancePDF()">📥 Export PDF</button>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
    <div class="chart-card">
      <div class="chart-title">Top <em style="font-style:italic;color:var(--accent)">Performers</em></div>
      <div class="chart-sub">Ranked by performance score</div>
      <?php
        $sorted = $employees;
        usort($sorted,fn($a,$b)=>$b['performance']-$a['performance']);
        foreach(array_slice($sorted,0,6) as $i=>$emp):
      ?>
      <div class="bar-row">
        <div style="display:flex;align-items:center;gap:0.8rem;width:14rem;flex-shrink:0">
          <span style="font-size:1.2rem;font-weight:700;color:var(--muted);width:1.6rem"><?= $i+1 ?></span>
          <div class="emp-avatar" style="width:2.6rem;height:2.6rem;font-size:1rem;background:<?= avatarColor($emp['name'],$avatarColors) ?>"><?= initials($emp['name']) ?></div>
          <span style="font-size:1.25rem;font-weight:500"><?= explode(' ',$emp['name'])[0] ?></span>
        </div>
        <div class="bar-track">
          <div class="bar-fill" style="width:<?= $emp['performance'] ?>%;background:<?= $emp['performance']>=90?'var(--green)':'var(--accent)' ?>"></div>
        </div>
        <div class="bar-val"><?= $emp['performance'] ?>%</div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="chart-card">
      <div class="chart-title">Dept <em style="font-style:italic;color:var(--accent)">Avg Performance</em></div>
      <div class="chart-sub">Average score per department</div>
      <?php foreach($depts as $dept):
        $de = array_filter($employees,fn($e)=>$e['dept']===$dept);
        if(!$de) continue;
        $avg = round(array_sum(array_column($de,'performance'))/count($de));
      ?>
      <div class="bar-row">
        <div class="bar-label"><?= explode(' ',$dept)[0] ?></div>
        <div class="bar-track">
          <div class="bar-fill" style="width:<?= $avg ?>%;background:<?= $avg>=90?'var(--green)':($avg>=80?'var(--accent)':'var(--red)') ?>"></div>
        </div>
        <div class="bar-val"><?= $avg ?>%</div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="table-wrap">
    <table class="data-table">
      <thead><tr><th>Rank</th><th>Employee</th><th>Dept</th><th>Score</th><th>Rating</th><th>Leaves</th></tr></thead>
      <tbody>
        <?php foreach($sorted as $i=>$emp): ?>
        <tr>
          <td style="font-family:'Playfair Display',serif;font-weight:700;color:var(--accent);font-size:1.6rem">#<?= $i+1 ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:1rem">
              <div class="emp-avatar" style="width:3.4rem;height:3.4rem;font-size:1.2rem;background:<?= avatarColor($emp['name'],$avatarColors) ?>"><?= initials($emp['name']) ?></div>
              <div><div class="emp-name"><?= htmlspecialchars($emp['name']) ?></div><div class="emp-id"><?= htmlspecialchars($emp['role']) ?></div></div>
            </div>
          </td>
          <td><?= htmlspecialchars($emp['dept']) ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:0.8rem">
              <div style="width:8rem;background:var(--stone);border-radius:5rem;height:0.7rem">
                <div style="width:<?= $emp['performance'] ?>%;height:100%;border-radius:5rem;background:<?= $emp['performance']>=90?'var(--green)':($emp['performance']>=75?'var(--accent)':'var(--red)') ?>"></div>
              </div>
              <strong><?= $emp['performance'] ?>%</strong>
            </div>
          </td>
          <td><?= str_repeat('⭐', round($emp['performance']/20)) ?></td>
          <td><?= $emp['leaves'] ?> days</td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php endif; ?>

</main>

<!-- ═══════════════
     MODALS
════════════════ -->

<!-- ADD EMPLOYEE MODAL -->
<div class="modal-overlay" id="modal-add" style="display:none">
  <div class="modal">
    <div class="modal-head" id="drag-modal-add">
      <div><h3>Add New <em>Employee</em></h3><p>Fill in the details to add a new team member</p><div class="modal-drag-hint">⠿ drag to move · arrow keys to nudge</div></div>
      <button class="modal-close" onclick="closeModal('modal-add')">✕</button>
    </div>
    <form method="POST" action="employee.php?tab=employees">
      <div class="modal-body">
        <div class="section-divider">👤 Personal Information</div>
        <div class="form-grid" style="margin-bottom:2rem">
          <div class="fld"><label>Full Name *</label><input name="name" required placeholder="e.g. Sara Ahmed"></div>
          <div class="fld"><label>Email Address *</label><input type="email" name="email" required placeholder="sara@travel.com"></div>
          <div class="fld"><label>Phone Number</label><input name="phone" placeholder="+92 300 0000000"></div>
          <div class="fld"><label>Join Date</label><input type="date" name="join_date" value="<?= date('Y-m-d') ?>"></div>
        </div>
        <div class="section-divider">🏢 Position Details</div>
        <div class="form-grid" style="margin-bottom:2rem">
          <div class="fld"><label>Department *</label>
            <select name="dept" required>
              <option value="">— Select Department —</option>
              <?php foreach($depts as $d): ?><option value="<?= $d ?>"><?= $d ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="fld"><label>Role / Title *</label>
            <input name="role" required placeholder="e.g. Senior Executive">
          </div>
          <div class="fld"><label>Status</label>
            <select name="status">
              <option value="active">Active</option>
              <option value="remote">Remote</option>
              <option value="leave">On Leave</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="fld"><label>Monthly Salary (PKR)</label><input type="number" name="salary" placeholder="e.g. 120000" min="0"></div>
        </div>
        <div class="section-divider">📊 Performance</div>
        <div class="form-grid">
          <div class="fld"><label>Performance Score (0–100)</label><input type="number" name="performance" min="0" max="100" value="85"></div>
          <div class="fld"><label>Leaves Taken</label><input type="number" name="leaves" min="0" value="0"></div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-add')">Cancel</button>
        <button type="submit" name="action" value="add_employee" class="btn btn-accent">✓ Add Employee</button>
      </div>
    </form>
  </div>
</div>

<!-- VIEW EMPLOYEE MODAL -->
<div class="modal-overlay" id="modal-view" style="display:none">
  <div class="modal">
    <div class="modal-head">
      <div><h3>Employee <em>Profile</em></h3><p id="view-meta"></p></div>
      <button class="modal-close" onclick="closeModal('modal-view')">✕</button>
    </div>
    <div class="modal-body">
      <div style="display:flex;align-items:center;gap:2rem;margin-bottom:2.8rem;padding:2rem;background:var(--stone);border-radius:1.2rem">
        <div id="view-avatar" style="width:7rem;height:7rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:2.8rem;color:#fff;flex-shrink:0"></div>
        <div>
          <div id="view-name" style="font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:700"></div>
          <div id="view-role" style="color:var(--accent);font-weight:600;font-size:1.4rem"></div>
          <div id="view-dept" style="color:var(--muted);font-size:1.3rem;margin-top:0.4rem"></div>
        </div>
        <span id="view-status" class="status-badge" style="margin-left:auto;font-size:1.3rem;padding:0.6rem 1.6rem"></span>
      </div>
      <div id="view-details" style="display:grid;grid-template-columns:1fr 1fr;gap:1.4rem;margin-bottom:2rem"></div>
      <div>
        <div style="font-size:1.3rem;font-weight:600;margin-bottom:1rem;color:var(--ink)">Performance Score</div>
        <div style="background:var(--stone);border-radius:5rem;height:1.2rem;overflow:hidden">
          <div id="view-perf-bar" style="height:100%;border-radius:5rem;transition:width 1s"></div>
        </div>
        <div id="view-perf-text" style="font-size:1.2rem;color:var(--muted);margin-top:0.6rem"></div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-outline" onclick="closeModal('modal-view')">Close</button>
      <button class="btn btn-accent" id="view-edit-btn">✏️ Edit Employee</button>
    </div>
  </div>
</div>

<!-- EDIT EMPLOYEE MODAL -->
<div class="modal-overlay" id="modal-edit" style="display:none">
  <div class="modal">
    <div class="modal-head">
      <div><h3>Edit <em>Employee</em></h3><p>Update employee information</p></div>
      <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
    </div>
    <form method="POST" action="employee.php?tab=employees">
      <input type="hidden" name="action" value="edit_employee">
      <input type="hidden" name="edit_id" id="edit-id">
      <div class="modal-body">
        <div class="section-divider">👤 Personal Information</div>
        <div class="form-grid" style="margin-bottom:2rem">
          <div class="fld"><label>Full Name *</label><input name="name" id="edit-name" required></div>
          <div class="fld"><label>Email *</label><input type="email" name="email" id="edit-email" required></div>
          <div class="fld"><label>Phone</label><input name="phone" id="edit-phone"></div>
          <div class="fld"><label>Join Date</label><input type="date" name="join_date" id="edit-join"></div>
        </div>
        <div class="section-divider">🏢 Position Details</div>
        <div class="form-grid" style="margin-bottom:2rem">
          <div class="fld"><label>Department *</label>
            <select name="dept" id="edit-dept" required>
              <?php foreach($depts as $d): ?><option value="<?= $d ?>"><?= $d ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="fld"><label>Role / Title *</label>
            <input name="role" id="edit-role" required>
          </div>
          <div class="fld"><label>Status</label>
            <select name="status" id="edit-status">
              <option value="active">Active</option>
              <option value="remote">Remote</option>
              <option value="leave">On Leave</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="fld"><label>Monthly Salary (PKR)</label><input type="number" name="salary" id="edit-salary" min="0"></div>
        </div>
        <div class="section-divider">📊 Performance</div>
        <div class="form-grid">
          <div class="fld"><label>Performance Score (0–100)</label><input type="number" name="performance" id="edit-perf" min="0" max="100"></div>
          <div class="fld"><label>Leaves Taken</label><input type="number" name="leaves" id="edit-leaves" min="0"></div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-edit')">Cancel</button>
        <button type="submit" class="btn btn-accent">✓ Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- LEAVE REQUEST MODAL -->
<div class="modal-overlay" id="modal-leave" style="display:none">
  <div class="modal" style="max-width:48rem">
    <div class="modal-head">
      <div><h3>Submit <em>Leave Request</em></h3><p>Fill in leave details below</p></div>
      <button class="modal-close" onclick="closeModal('modal-leave')">✕</button>
    </div>
    <form method="POST" action="employee.php?tab=leave">
      <div class="modal-body">
        <div style="display:flex;flex-direction:column;gap:1.8rem">
          <div class="fld"><label>Employee Name *</label>
            <select name="emp" required>
              <option value="">— Select Employee —</option>
              <?php foreach($employees as $e): ?><option value="<?= htmlspecialchars($e['name']) ?>"><?= htmlspecialchars($e['name']) ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="fld"><label>Leave Type</label>
            <select name="leave_type">
              <?php foreach(["Annual Leave","Sick Leave","Emergency Leave","Casual Leave","Maternity Leave","Paternity Leave"] as $t): ?><option><?= $t ?></option><?php endforeach; ?>
            </select>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.4rem">
            <div class="fld"><label>From Date *</label><input type="date" name="from_date" required></div>
            <div class="fld"><label>To Date *</label><input type="date" name="to_date" required></div>
          </div>
          <div class="fld"><label>Reason</label><textarea name="reason" placeholder="Brief reason for leave request…"></textarea></div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn btn-outline" onclick="closeModal('modal-leave')">Cancel</button>
        <button type="submit" name="action" value="submit_leave" class="btn btn-accent">✓ Submit Request</button>
      </div>
    </form>
  </div>
</div>

<!-- PROCESS PAYROLL MODAL -->
<div class="modal-overlay" id="modal-payroll" style="display:none">
  <div class="modal" style="max-width:52rem">
    <div class="modal-head">
      <div><h3>Process <em>Payroll</em></h3><p>Review and confirm payroll for <?= date('F Y') ?></p></div>
      <button class="modal-close" onclick="closeModal('modal-payroll')">✕</button>
    </div>
    <div class="modal-body">
      <div style="background:var(--stone);border-radius:1.2rem;padding:2rem;margin-bottom:2rem">
        <div class="payroll-summary-row">
          <span style="color:var(--muted)">Pay Period</span>
          <strong><?= date('F Y') ?></strong>
        </div>
        <div class="payroll-summary-row">
          <span style="color:var(--muted)">Total Employees</span>
          <strong><?= $total ?></strong>
        </div>
        <div class="payroll-summary-row">
          <span style="color:var(--muted)">Active / Remote (will be paid)</span>
          <strong style="color:var(--green)"><?= $active + $remote ?></strong>
        </div>
        <div class="payroll-summary-row">
          <span style="color:var(--muted)">Inactive / On Leave (deductions may apply)</span>
          <strong style="color:var(--red)"><?= $onLeave + count(array_filter($employees,fn($e)=>$e['status']==='inactive')) ?></strong>
        </div>
        <div class="payroll-summary-row" style="margin-top:1rem;padding-top:1.6rem;border-top:2px solid var(--border)">
          <span>Total Gross Payroll</span>
          <strong style="color:var(--accent);font-size:2rem;font-family:'Playfair Display',serif"><?= fmtSalary($payroll) ?></strong>
        </div>
      </div>
      <div style="background:rgba(200,151,74,0.08);border:1px solid rgba(200,151,74,0.3);border-radius:1rem;padding:1.6rem;font-size:1.3rem;color:var(--accent2)">
        ⚠️ This action will mark all active employee salaries as processed for <?= date('F Y') ?>. This cannot be undone.
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-outline" onclick="closeModal('modal-payroll')">Cancel</button>
      <button class="btn btn-accent" onclick="confirmProcessPayroll()">✓ Confirm & Process Payroll</button>
    </div>
  </div>
</div>

<!-- ── TOAST ── -->
<div id="toast-wrap"></div>

<script>
/* ── SIDEBAR TOGGLE ── */
const sidebar = document.getElementById('sidebar');
const topbar  = document.getElementById('topbar');
const main    = document.getElementById('main');
function toggleSidebar(){
  sidebar.classList.toggle('collapsed');
  topbar.classList.toggle('expanded');
  main.classList.toggle('expanded');
}

/* ── MODALS ── */
function openModal(id){ document.getElementById(id).style.display='flex'; document.body.style.overflow='hidden'; }
function closeModal(id){ document.getElementById(id).style.display='none'; document.body.style.overflow=''; }
document.querySelectorAll('.modal-overlay').forEach(el=>{
  el.addEventListener('click',e=>{ if(e.target===el){ el.style.display='none'; document.body.style.overflow=''; } });
});

/* ── TOAST ── */
function showToast(msg, type='success'){
  const wrap = document.getElementById('toast-wrap');
  const t = document.createElement('div');
  t.className = 'toast ' + type;
  t.innerHTML = (type==='success'?'✅':type==='error'?'❌':'ℹ️') + ' ' + msg;
  wrap.appendChild(t);
  setTimeout(()=>{ t.style.opacity='0'; t.style.transition='opacity .4s'; setTimeout(()=>t.remove(),400); }, 3500);
}

/* ── Show redirect toast ── */
<?php if($redirectToast): ?>
window.addEventListener('DOMContentLoaded',()=>{
  showToast(<?= json_encode(urldecode($redirectToast)) ?>,'success');
});
<?php endif; ?>

/* ── AVATAR HELPERS (JS) ── */
const avatarColors = ["#c8974a","#2d6a4f","#2980b9","#8b5e2a","#c0392b","#7d3c98","#117864","#1a5276"];
function avatarColor(name){ return avatarColors[name.charCodeAt(0) % avatarColors.length]; }
function initials(name){ return name.split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase(); }
function fmtSalary(n){ return 'PKR ' + Number(n).toLocaleString(); }

/* ── VIEW EMPLOYEE ── */
let _currentViewEmp = null;
function viewEmployee(emp){
  _currentViewEmp = emp;
  document.getElementById('view-meta').textContent   = emp.id + ' · ' + emp.dept;
  document.getElementById('view-avatar').textContent = initials(emp.name);
  document.getElementById('view-avatar').style.background = avatarColor(emp.name);
  document.getElementById('view-name').textContent   = emp.name;
  document.getElementById('view-role').textContent   = emp.role;
  document.getElementById('view-dept').textContent   = emp.dept + ' · Joined ' + (emp.joinDate || '—');
  const sb = document.getElementById('view-status');
  sb.textContent = emp.status; sb.className = 'status-badge ' + emp.status;

  const fields = [
    ['📧 Email', emp.email || '—'],
    ['📱 Phone', emp.phone || '—'],
    ['📅 Join Date', emp.joinDate || '—'],
    ['💰 Monthly Salary', fmtSalary(emp.salary)],
    ['🏖️ Leaves Used', (emp.leaves || 0) + ' days'],
    ['📊 Performance', (emp.performance || 0) + '%']
  ];
  document.getElementById('view-details').innerHTML = fields.map(([l,v])=>`
    <div style="background:var(--stone);border-radius:1rem;padding:1.4rem 1.6rem">
      <div style="font-size:1.15rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-bottom:.4rem">${l}</div>
      <div style="font-size:1.5rem;font-weight:600;color:var(--ink)">${v}</div>
    </div>`).join('');

  const p = emp.performance || 0;
  const bar = document.getElementById('view-perf-bar');
  setTimeout(()=>{ bar.style.width = p+'%'; }, 50);
  bar.style.background = p>=90?'var(--green)':p>=75?'var(--accent)':'var(--red)';
  const rating = p>=90?'Excellent 🏆':p>=75?'Good 👍':p>=60?'Average':'Needs Improvement ⚠️';
  document.getElementById('view-perf-text').textContent = p+'% — '+rating;

  // Wire up edit button in view modal
  document.getElementById('view-edit-btn').onclick = ()=>{ closeModal('modal-view'); editEmployee(emp); };

  openModal('modal-view');
}

/* ── EDIT EMPLOYEE ── */
function editEmployee(emp){
  document.getElementById('edit-id').value     = emp.id;
  document.getElementById('edit-name').value   = emp.name;
  document.getElementById('edit-email').value  = emp.email;
  document.getElementById('edit-phone').value  = emp.phone;
  document.getElementById('edit-join').value   = emp.joinDate;
  document.getElementById('edit-salary').value = emp.salary;
  document.getElementById('edit-perf').value   = emp.performance;
  document.getElementById('edit-leaves').value = emp.leaves;
  setSelect('edit-dept',   emp.dept);
  setSelect('edit-status', emp.status);
  // Role is now a text input
  document.getElementById('edit-role').value = emp.role;
  openModal('modal-edit');
}
function setSelect(id, val){
  const s = document.getElementById(id);
  if(!s) return;
  for(let o of s.options){ if(o.value===val||o.text===val){ s.value=o.value; break; } }
}

/* ── PROCESS PAYROLL ── */
function confirmProcessPayroll(){
  closeModal('modal-payroll');
  // Simulate processing with a loading toast then success
  showToast('Processing payroll…', 'success');
  setTimeout(()=>{
    showToast('✅ Payroll for <?= date("F Y") ?> processed successfully! <?= $total ?> employees paid.', 'success');
  }, 1800);
}

/* ── PDF HELPERS ── */
function addPDFHeader(doc, title){
  doc.setFillColor(15,15,15);
  doc.rect(0,0,300,30,'F');
  doc.setTextColor(255,255,255);
  doc.setFontSize(24);
  doc.setFont("helvetica","bold");
  doc.text("travel.",15,18);
  doc.setTextColor(200,151,74);
  doc.text("admin",48,18);
  doc.setTextColor(255,255,255);
  doc.setFontSize(18);
  doc.text(title,15,45);
  doc.setFontSize(11);
  doc.setTextColor(120);
  doc.text("Generated: " + new Date().toLocaleString(),15,53);
  doc.setDrawColor(230,230,230);
  doc.line(15,58,280,58);
}
function addPDFFooter(doc){
  const pages = doc.internal.getNumberOfPages();
  for(let i=1;i<=pages;i++){
    doc.setPage(i);
    doc.setFontSize(10);
    doc.setTextColor(120);
    doc.text("Page "+i+" of "+pages, 260, 200);
  }
}

/* ── EXPORT PAYROLL PDF ── */
function exportPayrollPDF(){
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ orientation:'landscape' });
  addPDFHeader(doc,"Payroll Report — <?= date('F Y') ?>");
  let rows = [];
  <?php foreach($empPayroll ?? $employees as $emp):
    $deduct = $emp['leaves'] * round($emp['salary']/30);
    $net    = $emp['salary'] - ($emp['status']==='inactive' ? $deduct : 0);
  ?>
  rows.push(["<?= $emp['id'] ?>","<?= addslashes($emp['name']) ?>","<?= addslashes($emp['dept']) ?>","<?= addslashes($emp['role']) ?>","PKR <?= number_format($emp['salary']) ?>","<?= $emp['leaves'] ?> Days","PKR <?= number_format($net) ?>","<?= ucfirst($emp['status']) ?>"]);
  <?php endforeach; ?>
  doc.autoTable({
    startY:65,
    head:[["ID","Employee","Department","Role","Salary","Leaves","Net Salary","Status"]],
    body:rows,
    theme:'grid',
    headStyles:{fillColor:[15,15,15],textColor:[255,255,255],fontStyle:'bold',halign:'center'},
    bodyStyles:{fontSize:10,cellPadding:4},
    alternateRowStyles:{fillColor:[248,248,248]}
  });
  addPDFFooter(doc);
  doc.save("travel-payroll-report.pdf");
  showToast("Payroll PDF downloaded!", "success");
}

/* ── EXPORT PERFORMANCE PDF ── */
function exportPerformancePDF(){
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ orientation:'landscape' });
  addPDFHeader(doc,"Performance Report — <?= date('F Y') ?>");
  let rows = [];
  <?php
  $perfSorted = $employees;
  usort($perfSorted,fn($a,$b)=>$b['performance']-$a['performance']);
  foreach($perfSorted as $i=>$emp):
  ?>
  rows.push(["#<?= $i+1 ?>","<?= addslashes($emp['name']) ?>","<?= addslashes($emp['dept']) ?>","<?= addslashes($emp['role']) ?>","<?= $emp['performance'] ?>%","<?= $emp['leaves'] ?> Days","<?= ucfirst($emp['status']) ?>"]);
  <?php endforeach; ?>
  doc.autoTable({
    startY:65,
    head:[["Rank","Employee","Department","Role","Performance","Leaves","Status"]],
    body:rows,
    theme:'grid',
    headStyles:{fillColor:[200,151,74],textColor:[255,255,255],fontStyle:'bold',halign:'center'},
    bodyStyles:{fontSize:10,cellPadding:4},
    alternateRowStyles:{fillColor:[249,246,241]}
  });
  addPDFFooter(doc);
  doc.save("travel-performance-report.pdf");
  showToast("Performance PDF downloaded!", "success");
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if($activeTab==='dashboard'): ?>
new Chart(document.getElementById('workforceChart'),{
  type:'line',
  data:{
    labels:['Jan','Feb','Mar','Apr','May','Jun'],
    datasets:[{
      label:'Employees',
      data:[18,22,26,24,30,<?= $total ?>],
      borderColor:'#f19809',
      backgroundColor:'rgba(243, 171, 56, 0.15)',
      fill:true,tension:.4,
      pointBackgroundColor:'#ab7723',pointRadius:5
    }]
  },
  options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(0,0,0,0.05)'}},x:{grid:{display:false}}}}
});
new Chart(document.getElementById('statusChart'),{
  type:'doughnut',
  data:{
    labels:['Active','Remote','Leave','Inactive'],
    datasets:[{
      data:[<?= $active ?>,<?= $remote ?>,<?= $onLeave ?>,<?= count(array_filter($employees,fn($e)=>$e['status']==='inactive')) ?>],
      backgroundColor:['#07de7d','#e8940c','#c2db0b','#c0392b'],
      borderWidth:0
    }]
  },
  options:{cutout:'72%',plugins:{legend:{position:'bottom'}}}
});
new Chart(document.getElementById('payrollChart'),{
  type:'bar',
  data:{
    labels:['Marketing','Finance','HR','IT','Operations'],
    datasets:[{label:'Payroll (L)',data:[1.3,1.95,1.12,1.55,5.5],backgroundColor:'#0f0f0f',borderRadius:8}]
  },
  options:{plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(0,0,0,0.05)'}},x:{grid:{display:false}}}}
});
<?php endif; ?>
</script>
</body>
</html>