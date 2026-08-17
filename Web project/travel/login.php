<?php
session_start();
if(isset($_SESSION['user_id'])) {
   header('location:book.php');
   exit();
}

$error = '';
$connection = mysqli_connect('localhost','root','','booking_db');

if(isset($_POST['login'])){
   $email    = mysqli_real_escape_string($connection, $_POST['email']);
   $password = $_POST['password'];
   $query    = "SELECT * FROM users WHERE email='$email'";
   $result   = mysqli_query($connection, $query);
   $user     = mysqli_fetch_assoc($result);

   if($user && password_verify($password, $user['password'])){
      $_SESSION['user_id']   = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['user_email']= $user['email'];
      header('location:book.php');
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
   <title>Login — travel.</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
   <style>
      :root{
         --ink:#0f0f0f;--cream:#faf8f5;--stone:#f2ede8;--muted:#888880;
         --accent:#c8974a;--accent2:#8b5e2a;--border:#e8e2da;
      }
      *{margin:0;padding:0;box-sizing:border-box}
      html{font-size:62.5%}
      body{min-height:100vh;display:flex;font-family:'DM Sans',sans-serif;background:var(--cream)}

      /* LEFT PANEL */
      .left-panel{
         flex:1;
         background:var(--ink);
         display:flex;flex-direction:column;
         align-items:center;justify-content:center;
         padding:5rem;position:relative;overflow:hidden;
      }
      .left-panel::before{
         content:'';position:absolute;
         width:50rem;height:50rem;
         background:radial-gradient(circle,rgba(200,151,74,0.12) 0%,transparent 70%);
         border-radius:50%;top:-10rem;left:-10rem;
      }
      .left-panel::after{
         content:'';position:absolute;
         width:40rem;height:40rem;
         background:radial-gradient(circle,rgba(200,151,74,0.08) 0%,transparent 70%);
         border-radius:50%;bottom:-8rem;right:-8rem;
      }
      .brand{
         font-family:'Playfair Display',serif;
         font-size:4rem;font-weight:700;
         color:#fff;margin-bottom:1rem;letter-spacing:-1px;
         position:relative;z-index:2;
      }
      .brand span{color:var(--accent)}
      .tagline{
         font-size:1.6rem;color:rgba(255,255,255,0.5);
         text-align:center;line-height:1.8;
         max-width:35rem;margin-bottom:4rem;
         font-weight:300;position:relative;z-index:2;
      }
      .features{width:100%;max-width:36rem;position:relative;z-index:2}
      .feature-item{
         display:flex;align-items:center;gap:1.6rem;
         padding:1.8rem 2rem;
         background:rgba(255,255,255,0.04);
         border:1px solid rgba(255,255,255,0.07);
         border-radius:1.2rem;margin-bottom:1.2rem;
         transition:.2s;
      }
      .feature-item:hover{background:rgba(200,151,74,0.08);border-color:rgba(200,151,74,0.2)}
      .feature-icon{
         width:4.4rem;height:4.4rem;border-radius:1rem;
         background:rgba(200,151,74,0.15);
         display:flex;align-items:center;justify-content:center;
         font-size:1.8rem;color:var(--accent);flex-shrink:0;
      }
      .feature-txt p{font-size:1.4rem;color:#fff;font-weight:600;margin-bottom:0.2rem}
      .feature-txt span{font-size:1.2rem;color:rgba(255,255,255,0.4)}

      /* RIGHT PANEL */
      .right-panel{
         width:58rem;background:#fff;
         display:flex;flex-direction:column;
         align-items:center;justify-content:center;
         padding:4rem;overflow-y:auto;
      }
      .auth-logo{
         font-family:'Playfair Display',serif;
         font-size:2.8rem;font-weight:700;
         color:var(--ink);margin-bottom:2.5rem;
         letter-spacing:-0.5px;
      }
      .auth-logo span{color:var(--accent)}

      /* TABS */
      .auth-tabs{
         display:flex;width:100%;
         background:var(--stone);
         border-radius:5rem;padding:0.5rem;
         margin-bottom:2.5rem;
      }
      .auth-tab{
         flex:1;text-align:center;padding:1rem;
         font-size:1.4rem;font-weight:600;
         color:var(--muted);cursor:pointer;
         border-radius:5rem;transition:.3s;
         text-decoration:none;
      }
      .auth-tab.active{
         background:var(--ink);color:#fff;
         box-shadow:0 4px 15px rgba(0,0,0,0.2);
      }

      .auth-title{font-size:2.4rem;font-weight:700;color:var(--ink);margin-bottom:0.4rem;font-family:'Playfair Display',serif}
      .auth-sub{font-size:1.4rem;color:var(--muted);margin-bottom:2.5rem;font-weight:300}

      .error-box{
         width:100%;background:#fff0f0;
         border:1px solid #e74c3c;border-radius:0.8rem;
         padding:1.2rem 1.5rem;font-size:1.4rem;
         color:#e74c3c;margin-bottom:2rem;
         display:flex;align-items:center;gap:1rem;
      }

      .auth-form{width:100%}
      .field-group{margin-bottom:1.8rem}
      .field-group label{
         display:block;font-size:1.3rem;
         color:var(--ink);font-weight:600;margin-bottom:0.8rem;
      }
      .input-wrap{position:relative}
      .input-wrap i.fi{
         position:absolute;left:1.5rem;top:50%;
         transform:translateY(-50%);
         color:var(--accent);font-size:1.5rem;
      }
      .input-wrap input{
         width:100%;
         padding:1.4rem 1.5rem 1.4rem 4.5rem;
         font-size:1.5rem;
         border:1.5px solid var(--border);
         border-radius:0.8rem;
         font-family:'DM Sans',sans-serif;
         color:var(--ink);background:#fafafa;
         transition:.2s;outline:none;
      }
      .input-wrap input:focus{
         border-color:var(--accent);background:#fff;
         box-shadow:0 0 0 3px rgba(200,151,74,0.1);
      }
      .eye-btn{
         position:absolute;right:1.5rem;top:50%;
         transform:translateY(-50%);
         background:none;border:none;cursor:pointer;
         color:var(--muted);font-size:1.5rem;padding:0;transition:.2s;
      }
      .eye-btn:hover{color:var(--accent)}

      .remember-row{
         display:flex;justify-content:space-between;align-items:center;
         margin-bottom:2rem;font-size:1.3rem;
      }
      .remember-row label{display:flex;align-items:center;gap:0.8rem;color:var(--ink);cursor:pointer;}
      .remember-row input[type="checkbox"]{accent-color:var(--accent);width:1.6rem;height:1.6rem;}
      .remember-row a{color:var(--accent);text-decoration:none;font-weight:600;}
      .remember-row a:hover{text-decoration:underline;}

      .btn-auth{
         width:100%;padding:1.5rem;
         background:var(--ink);color:#fff;
         font-size:1.5rem;font-weight:600;
         font-family:'DM Sans',sans-serif;
         border:none;border-radius:0.8rem;
         cursor:pointer;transition:.2s;
         letter-spacing:0.3px;margin-top:0.5rem;
      }
      .btn-auth:hover{background:var(--accent);transform:translateY(-2px);box-shadow:0 8px 25px rgba(200,151,74,0.3)}

      .divider{
         display:flex;align-items:center;gap:1.5rem;
         margin:2rem 0;color:var(--muted);font-size:1.3rem;
      }
      .divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border);}

      .bottom-link{text-align:center;font-size:1.4rem;color:var(--muted);margin-top:2rem;}
      .bottom-link a{color:var(--accent);font-weight:600;text-decoration:none;}
      .bottom-link a:hover{text-decoration:underline;}

      @media(max-width:768px){
         body{flex-direction:column}
         .left-panel{padding:4rem 2rem;min-height:28vh}
         .left-panel .features{display:none}
         .right-panel{width:100%;padding:3rem 2rem}
      }
   </style>
</head>
<body>

<!-- LEFT PANEL -->
<div class="left-panel">
   <div class="brand">travel<span>.</span></div>
   <p class="tagline">Your journey begins here. Explore the world with confidence and comfort.</p>
   <div class="features">
      <div class="feature-item">
         <div class="feature-icon"><i class="fas fa-map-marked-alt"></i></div>
         <div class="feature-txt"><p>120+ Destinations</p><span>Handpicked places worldwide</span></div>
      </div>
      <div class="feature-item">
         <div class="feature-icon"><i class="fas fa-tag"></i></div>
         <div class="feature-txt"><p>Best Prices</p><span>Guaranteed affordable packages</span></div>
      </div>
      <div class="feature-item">
         <div class="feature-icon"><i class="fas fa-headset"></i></div>
         <div class="feature-txt"><p>24/7 Support</p><span>Always here to help</span></div>
      </div>
      <div class="feature-item">
         <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
         <div class="feature-txt"><p>Secure Booking</p><span>Your data is always protected</span></div>
      </div>
   </div>
</div>

<!-- RIGHT PANEL -->
<div class="right-panel">
   <div class="auth-logo">travel<span>.</span></div>

   <div class="auth-tabs">
      <a href="login.php" class="auth-tab active">Login</a>
      <a href="register.php" class="auth-tab">Register</a>
   </div>

   <h2 class="auth-title">Welcome back!</h2>
   <p class="auth-sub">Login to book your next adventure 🌍</p>

   <?php if($error): ?>
   <div class="error-box"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
   <?php endif; ?>

   <form class="auth-form" method="post">
      <div class="field-group">
         <label>Email Address</label>
         <div class="input-wrap">
            <i class="fas fa-envelope fi"></i>
            <input type="email" name="email" placeholder="your@email.com" required>
         </div>
      </div>
      <div class="field-group">
         <label>Password</label>
         <div class="input-wrap">
            <i class="fas fa-lock fi"></i>
            <input type="password" name="password" id="loginPass" placeholder="Enter your password" required>
            <button type="button" class="eye-btn" onclick="togglePass('loginPass',this)"><i class="fas fa-eye"></i></button>
         </div>
      </div>

      <div class="remember-row">
         <label><input type="checkbox" name="remember"> Remember me</label>
         <a href="#">Forgot password?</a>
      </div>

      <button type="submit" name="login" class="btn-auth">Login to Account</button>
   </form>

   <div class="divider">or</div>
   <div class="bottom-link">Don't have an account? <a href="register.php">Create one free</a></div>
</div>

<script>
function togglePass(id, btn) {
   const input = document.getElementById(id);
   const icon  = btn.querySelector('i');
   if(input.type === 'password'){ input.type='text'; icon.className='fas fa-eye-slash'; }
   else { input.type='password'; icon.className='fas fa-eye'; }
}
</script>
</body>
</html>