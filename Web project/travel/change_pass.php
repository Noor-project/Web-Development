<?php
$connection = mysqli_connect('localhost','root','','booking_db');
$new_password = 'Admin@Travel2025';
$hashed = password_hash($new_password, PASSWORD_DEFAULT);
mysqli_query($connection, "UPDATE admin SET password='$hashed' WHERE email='admin@travel.com'");
echo "Password changed! New password: Admin@Travel2025";
echo "<br>Delete this file now!";
?>