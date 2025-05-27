<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $state = $_POST['state'];
    $total_amount = $_POST['total_amount'];

    $query = "INSERT INTO orders (user_id, fullname, email, phone, address, city, pincode, state, total_amount) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isssssssd", $user_id, $fullname, $email, $phone, $address, $city, $pincode, $state, $total_amount);
    
    if (mysqli_stmt_execute($stmt)) {
        $order_id = mysqli_insert_id($conn);
        echo json_encode(["status" => "success", "order_id" => $order_id]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
