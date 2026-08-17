<?php
session_start();
if(!isset($_SESSION['user_id'])){
   header('location:login.php');
   exit();
}

$connection = mysqli_connect('localhost','root','','booking_db');
$booking_id = intval($_GET['booking_id'] ?? 0);
$amount     = floatval($_GET['amount'] ?? 0);
$user_id    = (int)$_SESSION['user_id'];
$error      = '';
$success    = false;

// Get booking details
$result = mysqli_query($connection, "SELECT * FROM bookings WHERE id=$booking_id AND user_id=$user_id");
if(!$result || mysqli_num_rows($result) === 0){
  header('location:book.php'); exit();
}
$booking = mysqli_fetch_assoc($result);

// Handle payment submission
if(isset($_POST['pay'])){
   $method     = mysqli_real_escape_string($connection, $_POST['payment_method'] ?? 'card');
   $card_name  = mysqli_real_escape_string($connection, $_POST['card_name'] ?? '');
   $card_num   = $_POST['card_number'] ?? '';
   $card_last4 = substr(preg_replace('/\D/','',$card_num), -4);
   $exp        = $_POST['expiry'] ?? '';
   $cvv        = $_POST['cvv'] ?? '';
   $pay_amt    = floatval($_POST['amount'] ?? $amount);

   if($method === 'card'){
      $clean = preg_replace('/\D/','',$card_num);
      if(strlen($clean) < 13 || strlen($clean) > 19){
         $error = 'Please enter a valid card number.';
      } elseif(empty($card_name)){
         $error = 'Please enter the name on card.';
      } elseif(empty($exp)){
         $error = 'Please enter expiry date.';
      } elseif(strlen($cvv) < 3){
         $error = 'Please enter a valid CVV.';
      }
   }

   if(!$error){
      $sql = "INSERT INTO payments (booking_id, user_id, amount, payment_method, card_last4, card_name, status)
              VALUES ('$booking_id','$user_id','$pay_amt','$method','$card_last4','$card_name','pending')";
      if(mysqli_query($connection, $sql)){
         $success = true;
         mysqli_query($connection, "UPDATE bookings SET status='pending' WHERE id=$booking_id");
      } else {
         $error = 'Payment failed. Please try again. '.mysqli_error($connection);
      }
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment — travel.</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{--ink:#0f0f0f;--cream:#faf8f5;--stone:#f2ede8;--muted:#888880;--accent:#c8974a;--accent2:#8b5e2a;--green:#2d6a4f;--border:#e8e2da;--shadow:0 4px 32px rgba(0,0,0,0.07);--red:#e74c3c}
*{box-sizing:border-box;margin:0;padding:0}
html{font-size:62.5%}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);min-height:100vh}
.site-header{position:fixed;top:0;left:0;right:0;z-index:900;display:flex;align-items:center;justify-content:space-between;padding:1.8rem 6rem;background:rgba(250,248,245,0.92);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)}
.logo{font-family:'Playfair Display',serif;font-size:2.6rem;font-weight:700;color:var(--ink);text-decoration:none;letter-spacing:-0.5px}
.logo span{color:var(--accent)}
.secure-badge{display:flex;align-items:center;gap:0.8rem;font-size:1.3rem;color:var(--green);font-weight:600}
.secure-badge i{font-size:1.6rem}
.page-wrap{max-width:120rem;margin:0 auto;padding:12rem 6rem 6rem;display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:start}

/* SUCCESS */
.success-screen{text-align:center;background:#fff;border-radius:2rem;padding:6rem 4rem;border:1px solid var(--border);box-shadow:var(--shadow);grid-column:1/-1;max-width:60rem;margin:0 auto}
.success-icon{width:9rem;height:9rem;border-radius:50%;background:var(--green);display:flex;align-items:center;justify-content:center;margin:0 auto 3rem;font-size:3.6rem;color:#fff;animation:scaleIn .5s cubic-bezier(.34,1.56,.64,1)}
@keyframes scaleIn{from{transform:scale(0)}to{transform:scale(1)}}
.success-screen h2{font-family:'Playfair Display',serif;font-size:3.2rem;color:var(--ink);margin-bottom:1.2rem}
.success-screen p{font-size:1.6rem;color:var(--muted);line-height:1.8;margin-bottom:1.2rem}
.ref-box{background:var(--stone);border:1px solid var(--border);border-radius:1.2rem;padding:2rem;margin:2.4rem 0}
.ref-box .ref-label{font-size:1.2rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.6rem}
.ref-box .ref-num{font-family:'Playfair Display',serif;font-size:2.8rem;color:var(--accent);font-weight:700;letter-spacing:2px}
.pending-note{background:#fff3cd;border:1px solid #ffc107;border-radius:1rem;padding:1.6rem 2rem;font-size:1.4rem;color:#856404;margin:2rem 0;display:flex;align-items:center;gap:1rem}
.success-btns{display:flex;gap:1.4rem;justify-content:center;margin-top:2rem}
.btn-home{padding:1.2rem 3rem;background:var(--ink);color:#fff;border:none;border-radius:0.8rem;font-size:1.5rem;font-weight:600;cursor:pointer;text-decoration:none;font-family:'DM Sans',sans-serif;transition:.2s}
.btn-home:hover{background:var(--accent)}
.btn-mybookings{padding:1.2rem 3rem;background:#fff;color:var(--ink);border:1.5px solid var(--border);border-radius:0.8rem;font-size:1.5rem;font-weight:600;cursor:pointer;text-decoration:none;font-family:'DM Sans',sans-serif;transition:.2s}
.btn-mybookings:hover{background:var(--stone)}

/* ORDER SUMMARY */
.order-summary{background:var(--ink);border-radius:2rem;padding:3.6rem;color:#fff;position:sticky;top:10rem}
.order-summary h3{font-family:'Playfair Display',serif;font-size:2.2rem;color:#fff;margin-bottom:2.4rem;padding-bottom:1.6rem;border-bottom:1px solid rgba(255,255,255,0.1)}
.sum-row{display:flex;justify-content:space-between;padding:1rem 0;font-size:1.4rem;border-bottom:1px solid rgba(255,255,255,0.06)}
.sum-row:last-of-type{border-bottom:none}
.sum-row .lbl{color:rgba(255,255,255,0.5)}
.sum-row .val{color:#fff;font-weight:500;text-align:right;max-width:60%}
.sum-total{margin-top:2.4rem;padding-top:2rem;border-top:2px solid rgba(200,151,74,0.4)}
.sum-total .lbl{color:rgba(255,255,255,0.7);font-size:1.5rem;font-weight:600}
.sum-total .val{font-family:'Playfair Display',serif;font-size:3.2rem;color:var(--accent);font-weight:700}
.trust-badges{margin-top:2.8rem;display:flex;flex-direction:column;gap:1rem}
.trust-item{display:flex;align-items:center;gap:1rem;font-size:1.3rem;color:rgba(255,255,255,0.4)}
.trust-item i{color:var(--accent);font-size:1.4rem;width:1.6rem}

/* PAYMENT FORM */
.payment-card{background:#fff;border-radius:2rem;padding:3.6rem;border:1px solid var(--border);box-shadow:var(--shadow)}
.payment-card h3{font-family:'Playfair Display',serif;font-size:2.4rem;color:var(--ink);margin-bottom:0.6rem}
.payment-card .sub{font-size:1.4rem;color:var(--muted);margin-bottom:3rem;font-weight:300}
.error-box{background:#fff0f0;border:1px solid var(--red);border-radius:0.8rem;padding:1.2rem 1.6rem;font-size:1.4rem;color:var(--red);margin-bottom:2rem;display:flex;align-items:center;gap:1rem}

/* METHOD TABS */
.method-tabs{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:3rem}
.method-tab{border:1.5px solid var(--border);border-radius:1rem;padding:1.4rem 1rem;text-align:center;cursor:pointer;transition:.2s;background:#fafafa}
.method-tab label{cursor:pointer;display:flex;flex-direction:column;align-items:center;gap:0.8rem;font-size:1.3rem;color:var(--muted);font-weight:600}
.method-tab label i{font-size:2.2rem;color:var(--muted);transition:.2s}
.method-tab.selected{border-color:var(--accent);background:rgba(200,151,74,0.05)}
.method-tab.selected label{color:var(--accent)}
.method-tab.selected label i{color:var(--accent)}

/* CARD PREVIEW */
.card-preview{background:linear-gradient(135deg,var(--ink) 0%,#2c1810 100%);border-radius:1.6rem;padding:2.8rem;margin-bottom:2.4rem;position:relative;overflow:hidden}
.card-preview::before{content:'';position:absolute;top:-3rem;right:-3rem;width:14rem;height:14rem;border-radius:50%;background:rgba(200,151,74,0.1)}
.card-preview::after{content:'';position:absolute;bottom:-2rem;left:4rem;width:10rem;height:10rem;border-radius:50%;background:rgba(200,151,74,0.06)}
.card-chip{width:4rem;height:3rem;background:linear-gradient(135deg,#d4af37,#f5e642);border-radius:0.4rem;margin-bottom:2rem}
.card-num-display{font-size:2rem;color:#fff;letter-spacing:0.3rem;font-weight:300;margin-bottom:1.6rem;font-family:'DM Sans',sans-serif}
.card-bottom{display:flex;justify-content:space-between;align-items:flex-end}
.card-holder-label{font-size:1rem;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.3rem}
.card-holder-name{font-size:1.4rem;color:#fff;font-weight:500;text-transform:uppercase;letter-spacing:1px}
.card-exp-label{font-size:1rem;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.3rem;text-align:right}
.card-exp-val{font-size:1.4rem;color:#fff;font-weight:500}
.card-logo{position:absolute;top:2rem;right:2rem;font-size:3rem;opacity:0.6}

/* FIELDS */
.fld{margin-bottom:2rem}
.fld label{display:block;font-size:1.3rem;font-weight:600;color:var(--ink);margin-bottom:0.8rem}
.fld label .req{color:var(--red)}
.inp-wrap{position:relative}
.inp-wrap .ic{position:absolute;left:1.4rem;top:50%;transform:translateY(-50%);color:var(--accent);font-size:1.4rem;pointer-events:none}
.inp-wrap input{width:100%;padding:1.3rem 1.4rem 1.3rem 4rem;font-size:1.5rem;font-family:'DM Sans',sans-serif;border:1.5px solid var(--border);border-radius:0.8rem;background:#fafafa;color:var(--ink);outline:none;transition:.2s}
.inp-wrap input:focus{border-color:var(--accent);background:#fff;box-shadow:0 0 0 3px rgba(200,151,74,0.1)}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.6rem}

/* PAY BUTTON */
.btn-pay{width:100%;padding:1.8rem;background:var(--green);color:#fff;font-size:1.7rem;font-weight:700;font-family:'DM Sans',sans-serif;border:none;border-radius:1rem;cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;gap:1.2rem;margin-top:0.8rem}
.btn-pay:hover{background:#1e4d38;transform:translateY(-2px);box-shadow:0 8px 24px rgba(45,106,79,0.35)}
.pay-note{text-align:center;font-size:1.2rem;color:var(--muted);margin-top:1.6rem;display:flex;align-items:center;justify-content:center;gap:0.6rem}
.pay-note i{color:var(--green)}

/* OTHER METHODS */
.other-method{background:var(--stone);border-radius:1.4rem;padding:3rem;text-align:center;border:1.5px dashed var(--border)}
.other-method i{font-size:4rem;color:var(--accent);margin-bottom:1.6rem;display:block}
.other-method h4{font-family:'Playfair Display',serif;font-size:2rem;color:var(--ink);margin-bottom:1rem}
.other-method p{font-size:1.4rem;color:var(--muted);line-height:1.7;margin-bottom:2rem}
.other-method .contact-detail{background:#fff;border-radius:0.8rem;padding:1.2rem 2rem;font-size:1.4rem;font-weight:600;color:var(--ink);margin-bottom:1rem;border:1px solid var(--border)}

@media(max-width:768px){
  .site-header{padding:1.6rem 2rem}
  .page-wrap{grid-template-columns:1fr;padding:10rem 2rem 4rem;gap:3rem}
  .order-summary{position:static}
  .grid-2{grid-template-columns:1fr}
  .method-tabs{grid-template-columns:1fr 1fr 1fr}
}
</style>
</head>
<body>

<header class="site-header">
  <a href="home.php" class="logo">travel<span>.</span></a>
  <div class="secure-badge"><i class="fas fa-lock"></i> Secure Payment</div>
</header>

<?php if($success): ?>
<!-- SUCCESS SCREEN -->
<div class="page-wrap" style="grid-template-columns:1fr;justify-items:center;padding-top:14rem">
  <div class="success-screen">
    <div class="success-icon"><i class="fas fa-check"></i></div>
    <h2>Payment Submitted!</h2>
    <p>Your payment has been received and is pending admin approval. You will be notified once confirmed.</p>
    <div class="ref-box">
      <div class="ref-label">Booking Reference</div>
      <div class="ref-num">TRV-<?= str_pad($booking_id, 6, '0', STR_PAD_LEFT) ?></div>
    </div>
    <div class="pending-note">
      <i class="fas fa-clock"></i>
      <span>Your booking is <strong>Pending Approval</strong>. Our team will review your payment and confirm within 24 hours.</span>
    </div>
    <div class="success-btns">
      <a href="home.php" class="btn-home"><i class="fas fa-home"></i> Home</a>
      <a href="my_bookings.php" class="btn-mybookings"><i class="fas fa-list"></i> My Bookings</a>
    </div>
  </div>
</div>

<?php else: ?>
<!-- PAYMENT PAGE -->
<div class="page-wrap">

  <!-- ORDER SUMMARY -->
  <div class="order-summary">
    <h3><i class="fas fa-receipt" style="color:var(--accent);margin-right:1rem"></i>Order Summary</h3>
    <div class="sum-row"><span class="lbl">Passenger Name</span><span class="val"><?= htmlspecialchars($booking['name']) ?></span></div>
    <div class="sum-row"><span class="lbl">Destination</span><span class="val"><?= htmlspecialchars($booking['location']) ?></span></div>
    <div class="sum-row"><span class="lbl">Arrival</span><span class="val"><?= date('d M Y', strtotime($booking['arrival'])) ?></span></div>
    <div class="sum-row"><span class="lbl">Departure</span><span class="val"><?= date('d M Y', strtotime($booking['leaving'])) ?></span></div>
    <div class="sum-row"><span class="lbl">Guests</span><span class="val"><?= $booking['guests'] ?> Person(s)</span></div>
    <div class="sum-row"><span class="lbl">Trip Type</span><span class="val"><?= htmlspecialchars($booking['trip_type'] ?: 'General') ?></span></div>
    <div class="sum-row"><span class="lbl">Accommodation</span><span class="val"><?= htmlspecialchars($booking['accommodation'] ?: 'Standard') ?></span></div>
    <div class="sum-row"><span class="lbl">Meal Plan</span><span class="val"><?= htmlspecialchars($booking['meal'] ?: 'No preference') ?></span></div>
    <div class="sum-row"><span class="lbl">Transport</span><span class="val"><?= htmlspecialchars($booking['transport'] ?: 'No preference') ?></span></div>
    <div class="sum-total sum-row">
      <span class="lbl">Total Amount</span>
      <span class="val">$<?= number_format($amount, 2) ?></span>
    </div>
    <div class="trust-badges">
      <div class="trust-item"><i class="fas fa-shield-alt"></i> 256-bit SSL Encryption</div>
      <div class="trust-item"><i class="fas fa-lock"></i> Secure Payment Gateway</div>
      <div class="trust-item"><i class="fas fa-undo"></i> Free Cancellation Policy</div>
      <div class="trust-item"><i class="fas fa-headset"></i> 24/7 Support Available</div>
    </div>
  </div>

  <!-- PAYMENT FORM -->
  <div class="payment-card">
    <h3>Complete Payment</h3>
    <p class="sub">Choose your payment method and enter your details securely</p>

    <?php if($error): ?>
    <div class="error-box"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
    <?php endif; ?>

    <div class="method-tabs">
      <div class="method-tab selected" id="tab-card" onclick="selectMethod('card')">
        <label><i class="fas fa-credit-card"></i> Credit Card</label>
      </div>
      <div class="method-tab" id="tab-bank" onclick="selectMethod('bank')">
        <label><i class="fas fa-university"></i> Bank Transfer</label>
      </div>
      <div class="method-tab" id="tab-cash" onclick="selectMethod('cash')">
        <label><i class="fas fa-money-bill-wave"></i> Cash / Office</label>
      </div>
    </div>

    <form method="post" id="payForm">
      <input type="hidden" name="payment_method" id="f-method" value="card">
      <input type="hidden" name="amount" value="<?= $amount ?>">

      <!-- CARD PAYMENT -->
      <div id="card-section">
        <div class="card-preview">
          <div class="card-chip"></div>
          <div class="card-num-display" id="preview-num">•••• •••• •••• ••••</div>
          <div class="card-bottom">
            <div>
              <div class="card-holder-label">Card Holder</div>
              <div class="card-holder-name" id="preview-name">YOUR NAME</div>
            </div>
            <div>
              <div class="card-exp-label">Expires</div>
              <div class="card-exp-val" id="preview-exp">MM/YY</div>
            </div>
          </div>
          <div class="card-logo"><i class="fab fa-cc-visa"></i></div>
        </div>

        <div class="fld">
          <label>Card Number <span class="req">*</span></label>
          <div class="inp-wrap">
            <i class="fas fa-credit-card ic"></i>
            <input type="text" name="card_number" id="card-num" placeholder="1234 5678 9012 3456" maxlength="19" oninput="formatCard(this)">
          </div>
        </div>
        <div class="fld">
          <label>Name on Card <span class="req">*</span></label>
          <div class="inp-wrap">
            <i class="fas fa-user ic"></i>
            <input type="text" name="card_name" id="card-name" placeholder="As shown on card" oninput="document.getElementById('preview-name').textContent=this.value.toUpperCase()||'YOUR NAME'">
          </div>
        </div>
        <div class="grid-2">
          <div class="fld">
            <label>Expiry Date <span class="req">*</span></label>
            <div class="inp-wrap">
              <i class="fas fa-calendar ic"></i>
              <input type="text" name="expiry" id="card-exp" placeholder="MM/YY" maxlength="5" oninput="formatExpiry(this)">
            </div>
          </div>
          <div class="fld">
            <label>CVV <span class="req">*</span></label>
            <div class="inp-wrap">
              <i class="fas fa-lock ic"></i>
              <input type="password" name="cvv" placeholder="•••" maxlength="4">
            </div>
          </div>
        </div>
        <button type="submit" name="pay" class="btn-pay">
          <i class="fas fa-lock"></i> Pay $<?= number_format($amount, 2) ?> Securely
        </button>
        <p class="pay-note"><i class="fas fa-shield-alt"></i> Your payment information is encrypted and secure</p>
      </div>

      <!-- BANK TRANSFER -->
      <div id="bank-section" style="display:none">
        <div class="other-method">
          <i class="fas fa-university"></i>
          <h4>Bank Transfer</h4>
          <p>Transfer the amount to our bank account and submit proof of payment. Your booking will be confirmed after verification.</p>
          <div class="contact-detail"><i class="fas fa-building"></i> Bank: National Bank of Travel</div>
          <div class="contact-detail"><i class="fas fa-hashtag"></i> Account: 1234-5678-9012</div>
          <div class="contact-detail"><i class="fas fa-code-branch"></i> IBAN: PK36 SCBL 0000 0016 7607 0201</div>
          <div class="contact-detail"><i class="fas fa-dollar-sign"></i> Amount: $<?= number_format($amount, 2) ?></div>
          <button type="submit" name="pay" class="btn-pay" style="margin-top:2rem">
            <i class="fas fa-check"></i> I Have Transferred — Confirm Booking
          </button>
        </div>
      </div>

      <!-- CASH / OFFICE -->
      <div id="cash-section" style="display:none">
        <div class="other-method">
          <i class="fas fa-money-bill-wave"></i>
          <h4>Pay at Office / Cash</h4>
          <p>Visit our office or contact us to arrange cash payment. Your booking will be held for 48 hours pending payment.</p>
          <div class="contact-detail"><i class="fas fa-map-marker-alt"></i> Dubai, UAE — Main Office</div>
          <div class="contact-detail"><i class="fas fa-phone"></i> +123-456-7890</div>
          <div class="contact-detail"><i class="fas fa-envelope"></i> hello@travel.com</div>
          <div class="contact-detail"><i class="fas fa-dollar-sign"></i> Amount Due: $<?= number_format($amount, 2) ?></div>
          <button type="submit" name="pay" class="btn-pay" style="margin-top:2rem;background:var(--accent)">
            <i class="fas fa-check"></i> Reserve — I'll Pay at Office
          </button>
        </div>
      </div>

    </form>
  </div>
</div>
<?php endif; ?>

<script>
function selectMethod(method) {
  document.querySelectorAll('.method-tab').forEach(t => t.classList.remove('selected'));
  document.getElementById('tab-'+method).classList.add('selected');
  document.getElementById('f-method').value = method;
  document.getElementById('card-section').style.display = method==='card' ? 'block' : 'none';
  document.getElementById('bank-section').style.display = method==='bank' ? 'block' : 'none';
  document.getElementById('cash-section').style.display = method==='cash' ? 'block' : 'none';
}
function formatCard(inp) {
  let v = inp.value.replace(/\D/g,'').substring(0,16);
  inp.value = v.replace(/(.{4})/g,'$1 ').trim();
  const display = v.padEnd(16,'•').replace(/(.{4})/g,'$1 ').trim();
  document.getElementById('preview-num').textContent = display;
  const logo = document.querySelector('.card-logo i');
  if(v.startsWith('4')) logo.className='fab fa-cc-visa';
  else if(v.startsWith('5')) logo.className='fab fa-cc-mastercard';
  else if(v.startsWith('3')) logo.className='fab fa-cc-amex';
  else logo.className='fas fa-credit-card';
}
function formatExpiry(inp) {
  let v = inp.value.replace(/\D/g,'');
  if(v.length >= 2) v = v.substring(0,2)+'/'+v.substring(2,4);
  inp.value = v;
  document.getElementById('preview-exp').textContent = v || 'MM/YY';
}
</script>
</body>
</html>