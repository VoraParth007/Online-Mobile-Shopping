<?php
session_name("user_session"); 
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    die("❌ Unauthorized access.");
}

if (!isset($_GET['order_id'])) {
    die("❌ Invalid Order ID.");
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];

// ✅ Fetch order data
$stmt = $conn->prepare("SELECT orders.*, users.username, users.email, users.phone, users.address, users.city, users.state, users.pincode 
                        FROM orders 
                        INNER JOIN users ON orders.user_id = users.id 
                        WHERE orders.id = ? AND orders.user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("❌ Order not found or unauthorized access.");
}

// ✅ Fetch order items
$order_items_stmt = $conn->prepare("SELECT od.*, p.product_name 
                                    FROM order_details od 
                                    INNER JOIN products p ON od.product_id = p.id 
                                    WHERE od.order_id = ?");
$order_items_stmt->bind_param("i", $order_id);
$order_items_stmt->execute();
$order_items_result = $order_items_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $order['id']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- ✅ Include jsPDF & html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        body { margin: 40px; }
        .invoice-box { padding: 30px; border: 1px solid #eee; background: #fff; }
    </style>
</head>
<body>

<div id="invoiceContent" class="invoice-box">
    <h2>🧾 Invoice</h2>
    <p><strong>Invoice #:</strong> <?= $order['id']; ?></p>
    <p><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>

    <hr>

    <h5>👤 Customer Details</h5>
    <p><strong>Name:</strong> <?= htmlspecialchars($order['username']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']); ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']); ?></p>
    <p><strong>Address:</strong><br>
        <?= htmlspecialchars($order['address']); ?><br>
        <?= htmlspecialchars($order['city']); ?>, <?= htmlspecialchars($order['state']); ?> - <?= $order['pincode']; ?>
    </p>

    <hr>

    <h5>📦 Order Summary</h5>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            while ($item = $order_items_result->fetch_assoc()): 
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($item['product_name']); ?></td>
                <td><?= $item['quantity']; ?></td>
                <td>₹<?= number_format($item['price'], 2); ?></td>
                <td>₹<?= number_format($item['total_price'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h4 class="text-end">Subtotal: ₹<?= number_format($order['total_price'] - $order['delivery_charge'], 2); ?></h4>
    <h5 class="text-end">Delivery Charge: ₹<?= number_format($order['delivery_charge'], 2); ?></h5>
    <h4 class="text-end fw-bold">Total: ₹<?= number_format($order['total_price'], 2); ?></h4>
    <p><strong>Payment Method:</strong> <?= $order['payment_method']; ?></p>
    <p><strong>Status:</strong> <?= $order['status']; ?></p>

    <hr>
    <p class="text-center text-muted">Thank you for shopping with us!</p>
</div>

<!-- ✅ Auto Generate PDF and Download -->
<script>
    window.onload = async function () {
        const { jsPDF } = window.jspdf;
        const element = document.getElementById('invoiceContent');

        html2canvas(element).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            pdf.save('Invoice_<?= $order['id']; ?>.pdf');
        });
    };
</script>

</body>
</html>
