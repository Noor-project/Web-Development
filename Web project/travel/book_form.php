<?php
session_start();
$connection = mysqli_connect('localhost','root','','booking_db');

if(isset($_POST['send'])){
   $user_id          = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
   $name             = $_POST['name'] ?? '';
   $email            = $_POST['email'] ?? '';
   $phone            = $_POST['phone'] ?? '';
   $address          = $_POST['address'] ?? '';
   $location         = $_POST['location'] ?? '';
   $guests           = (int)($_POST['guests'] ?? 1);
   $arrivals         = $_POST['arrivals'] ?? '';
   $leaving          = $_POST['leaving'] ?? '';
   $trip_type        = $_POST['triptype'] ?? '';
   $accommodation    = $_POST['accommodation'] ?? '';
   $meal             = $_POST['meal'] ?? '';
   $transport        = $_POST['transport'] ?? '';
   $budget           = (float)($_POST['budget'] ?? 0);
   $special_requests = $_POST['special_requests'] ?? '';

   // Calculate nights and total
   $nights = 1;
   if($arrivals && $leaving){
      $nights = max(1, round((strtotime($leaving) - strtotime($arrivals)) / 86400));
   }
   $total_amount = $guests * $nights * $budget;

   $sql = "INSERT INTO bookings
      (user_id, name, email, phone, address, location, guests,
       arrival, leaving, trip_type, accommodation, meal,
       transport, budget, special_requests, status)
      VALUES (
      '$user_id','$name','$email','$phone','$address','$location',
      '$guests','$arrivals','$leaving','$trip_type','$accommodation',
      '$meal','$transport','$budget','$special_requests','pending'
      )";

   if(mysqli_query($connection, $sql)){
      $booking_id = mysqli_insert_id($connection);
      // Redirect to payment page with booking details
      header("location:payment.php?booking_id=$booking_id&amount=$total_amount&name=".urlencode($name)."&email=".urlencode($email));
      exit();
   } else {
      die("Error: " . mysqli_error($connection));
   }
} else {
   header('location:book.php');
   exit();
}
?>