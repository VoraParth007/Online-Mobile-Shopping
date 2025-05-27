<?php
// ✅ LOCALHOST CONFIGURATION (For development)
$host = "localhost";
$user = "root";
$password = "";
$database = "mobile_shop";

// 🌐 LIVE SERVER CONFIGURATION (InfinityFree Hosting)
// $host = "sql105.infinityfree.com";
// $user = "if0_39050962";
// $password = "sPMKt56jvjaL";
// $database = "if0_39050962_mobileshop";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

