<?php
$host = "sql105.infinityfree.com";
$user = "if0_39050962"; // Change if using a different username
$password = "sPMKt56jvjaL"; // Change if using a password
$database = "if0_39050962_M_SHOP	";

$conn = mysqli_connect($host, $user, $password, $database);




if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
