<?php
session_name("admin_session");
session_start();
include('../includes/config.php');
$category_id = $_POST['category_id'];

$brands = mysqli_query($conn, "SELECT * FROM brands WHERE category_id = $category_id");
echo "<option value=''>Select Brand</option>";
while ($brand = mysqli_fetch_assoc($brands)) {
    echo "<option value='{$brand['id']}'>{$brand['brand_name']}</option>";
}
?>
