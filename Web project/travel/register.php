<?php
session_start();
if(isset($_SESSION['user_id'])) {
   header('location:book.php');
   exit();
}

$error = '';
$connection = mysqli_connect('localhost','root','','booking_db');

if(isset($_POST['register'])){
   $name     = mysqli_real_escape_string($connection, trim($_POST['name']));
   $email    = mysqli_real_escape_string($connection, trim($_POST['email']));
   $phone    = mysqli_real_escape_string($connection, trim($_POST['phone']));
   $password = $_POST['password'];
   $confirm  = $_POST['confirm_password'];

   if(empty($name)||empty($email)||empty($phone)||empty($password)){
      $error = 'All fields are required!';
   } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $error = 'Please enter a valid email!';
   } elseif(strlen($password) < 6){
      $error = 'Password must be at least 6 characters!';
   } elseif($password !== $confirm){
      $error = 'Passwords do not match!';
   } else {
      $check = mysqli_query($connection, "SELECT id FROM users WHERE email='$email'");
      if(mysqli_num_rows($check) > 0){
         $error = 'Email already registered! Please login.';
      } else {
         $hashed = password_hash($password, PASSWORD_DEFAULT);
         $insert = "INSERT INTO users(name,email,phone,password) VALUES('$name','$email','$phone','$hashed')";
         if(mysqli_query($connection, $insert)){
            $result = mysqli_query($connection, "SELECT * FROM users WHERE email='$email'");
            $user   = mysqli_fetch_assoc($result);
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header('location:book.php');
            exit();
         } else {
            $error = 'Registration failed! Please try again.';
         }
      }
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register — travel.</title>
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
      .fields-row{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem}
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

      /* PASSWORD STRENGTH */
      .password-strength{margin-top:0.8rem}
      .strength-bar{height:4px;border-radius:2px;background:#eee;margin-bottom:0.5rem;overflow:hidden}
      .strength-fill{height:100%;border-radius:2px;transition:.3s;width:0%}
      .strength-text{font-size:1.2rem;color:var(--muted)}

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

      .terms{font-size:1.2rem;color:var(--muted);text-align:center;margin-top:1.5rem}
      .terms a{color:var(--accent);text-decoration:none}
      .bottom-link{text-align:center;font-size:1.4rem;color:var(--muted);margin-top:2rem}
      .bottom-link a{color:var(--accent);font-weight:600;text-decoration:none}
      .bottom-link a:hover{text-decoration:underline}

      @media(max-width:768px){
         body{flex-direction:column}
         .left-panel{padding:4rem 2rem;min-height:28vh}
         .left-panel .features{display:none}
         .right-panel{width:100%;padding:3rem 2rem}
         .fields-row{grid-template-columns:1fr}
      }
   </style>
</head>
<body>

<!-- LEFT PANEL -->
<div class="left-panel">
   <div class="brand">travel<span>.</span></div>
   <p class="tagline">Join thousands of explorers. Create your free account and start your adventure today.</p>
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
      <a href="login.php" class="auth-tab">Login</a>
      <a href="register.php" class="auth-tab active">Register</a>
   </div>

   <h2 class="auth-title">Create Account</h2>
   <p class="auth-sub">Start your adventure today 🌍</p>

   <?php if($error): ?>
   <div class="error-box"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
   <?php endif; ?>

   <form class="auth-form" method="post">
      <div class="fields-row">
         <div class="field-group">
            <label>Full Name</label>
            <div class="input-wrap">
               <i class="fas fa-user fi"></i>
               <input type="text" name="name" placeholder="Your full name" required>
            </div>
         </div>
         <div class="field-group">
            <label>Phone Number</label>
            <div class="input-wrap">
               <i class="fas fa-phone fi"></i>
               <input type="number" name="phone" placeholder="Your phone" required>
            </div>
         </div>
      </div>

      <div class="field-group">
         <label>Email Address</label>
         <div class="input-wrap">
            <i class="fas fa-envelope fi"></i>
            <input type="email" name="email" placeholder="your@email.com" required>
         </div>
      </div>

      <div class="fields-row">
         <div class="field-group">
            <label>Password</label>
            <div class="input-wrap">
               <i class="fas fa-lock fi"></i>
               <input type="password" name="password" id="regPass" placeholder="Min 6 characters" oninput="checkStrength(this.value)" required>
               <button type="button" class="eye-btn" onclick="togglePass('regPass',this)"><i class="fas fa-eye"></i></button>
            </div>
            <div class="password-strength">
               <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
               <span class="strength-text" id="strengthText"></span>
            </div>
         </div>
         <div class="field-group">
            <label>Confirm Password</label>
            <div class="input-wrap">
               <i class="fas fa-lock fi"></i>
               <input type="password" name="confirm_password" id="confPass" placeholder="Repeat password" required>
               <button type="button" class="eye-btn" onclick="togglePass('confPass',this)"><i class="fas fa-eye"></i></button>
            </div>
         </div>
      </div>

      <button type="submit" name="register" class="btn-auth">Create My Account 🚀</button>
      <p class="terms">By registering you agree to our <a href="#">Terms of Service</a> & <a href="#">Privacy Policy</a></p>
   </form>

   <div class="bottom-link">Already have an account? <a href="login.php">Login here</a></div>
</div>

<script>
function togglePass(id, btn) {
   const input = document.getElementById(id);
   const icon  = btn.querySelector('i');
   if(input.type === 'password'){ input.type='text'; icon.className='fas fa-eye-slash'; }
   else { input.type='password'; icon.className='fas fa-eye'; }
}
function checkStrength(val) {
   const fill = document.getElementById('strengthFill');
   const text = document.getElementById('strengthText');
   let score = 0;
   if(val.length >= 6) score++;
   if(val.length >= 10) score++;
   if(/[A-Z]/.test(val)) score++;
   if(/[0-9]/.test(val)) score++;
   if(/[^A-Za-z0-9]/.test(val)) score++;
   const levels = [
      {pct:'20%',color:'#e74c3c',label:'Very Weak'},
      {pct:'40%',color:'#e67e22',label:'Weak'},
      {pct:'60%',color:'#f39c12',label:'Fair'},
      {pct:'80%',color:'#2ecc71',label:'Strong'},
      {pct:'100%',color:'#27ae60',label:'Very Strong'}
   ];
   const l = levels[Math.min(score, levels.length-1)];
   fill.style.width = l.pct;
   fill.style.background = l.color;
   text.textContent = l.label;
   text.style.color = l.color;
}
</script>
</body>
</html>