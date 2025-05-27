<?php
session_name("admin_session");
session_start();
include ('../includes/config.php');
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM blogs WHERE id = $id");
header("Location: manage_blogs.php");
?>
