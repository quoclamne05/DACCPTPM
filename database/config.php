<?php
//database configurations
define("DB_HOST","localhost");
define("DB_UNAME","root");  // Default XAMPP/Laragon MySQL username
define("DB_PASS","");       // Default XAMPP/Laragon MySQL password is empty
define("DB_DNAME","tracnghiem");
$conn=mysqli_connect(DB_HOST,DB_UNAME,DB_PASS,DB_DNAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
