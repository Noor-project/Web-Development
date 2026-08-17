<?php
session_start();
if(isset($_SESSION['admin_id'])) {
    header('location:dashboard.php');
    exit();
}

$error = '';
$connection = mysqli_connect('localhost','root','','booking_db');

if(isset($_POST['login'])){
    $email    = mysqli_real_escape_string($connection, $_POST['email']);
    $password = $_POST['password'];
    
    $stmt = mysqli_prepare($connection, "SELECT * FROM admin WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin  = mysqli_fetch_assoc($result);
    
    if($admin && password_verify($password, $admin['password'])){
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header('location:dashboard.php');
        exit();
    } else {
        $error = 'Invalid email or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — travel.</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
        body{min-height:100vh;background:linear-gradient(135deg,#0f0f0f 0%,#2c1810 100%);display:flex;align-items:center;justify-content:center;}
        .login-box{background:#fff;border-radius:2rem;padding:4rem;width:100%;max-width:44rem;box-shadow:0 24px 80px rgba(0,0,0,0.3);}
        .logo{text-align:center;margin-bottom:3rem;}
        .logo h1{font-size:3rem;font-weight:700;color:#0f0f0f;}
        .logo h1 span{color:#c8974a;}
        .logo p{font-size:1.4rem;color:#888;margin-top:0.5rem;}
        .error{background:#fff0f0;border:1px solid #e74c3c;border-radius:0.8rem;padding:1.2rem 1.5rem;font-size:1.4rem;color:#e74c3c;margin-bottom:2rem;display:flex;align-items:center;gap:1rem;}
        .field{margin-bottom:2rem;}
        .field label{display:block;font-size:1.3rem;color:#555;font-weight:600;margin-bottom:0.8rem;}
        .input-wrap{position:relative;}
        .input-wrap i{position:absolute;left:1.5rem;top:50%;transform:translateY(-50%);color:#c8974a;font-size:1.5rem;}
        .input-wrap input{width:100%;padding:1.4rem 1.5rem 1.4rem 4.5rem;font-size:1.5rem;border:2px solid #eee;border-radius:1rem;font-family:'Poppins',sans-serif;color:#333;background:#fafafa;transition:0.2s;outline:none;}
        .input-wrap input:focus{border-color:#c8974a;background:#fff;box-shadow:0 0 0 3px rgba(200,151,74,0.1);}
        .btn{width:100%;padding:1.5rem;background:linear-gradient(135deg,#0f0f0f,#2c1810);color:#fff;font-size:1.6rem;font-weight:600;font-family:'Poppins',sans-serif;border:none;border-radius:1rem;cursor:pointer;transition:0.3s;}
        .btn:hover{background:linear-gradient(135deg,#c8974a,#8b5e2a);transform:translateY(-2px);}
        html{font-size:62.5%;}
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">
            <h1>travel<span>.</span></h1>
            <p><i class="fas fa-shield-alt"></i> Admin Panel</p>
        </div>
        <?php if($error): ?>
        <div class="error"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="field">
                <label>Email Address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="admin@travel.com" required>
                </div>
            </div>
            <div class="field">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
            </div>
            <button type="submit" name="login" class="btn">
                <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
            </button>
        </form>
    </div>
</body>
</html>