<?php
session_name("admin_session");
session_start();
include('../includes/config.php'); // Database Connection
date_default_timezone_set("Asia/Kolkata");

// Filters
$filter = $_GET['filter'] ?? 'month'; // default filter
$month_filter = $_GET['month'] ?? date('Y-m');

// Sales Totals
$sales_today = $conn->query("SELECT SUM(total_price) AS total FROM sales_reports WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['total'] ?? 0;
$sales_month = $conn->query("SELECT SUM(total_price) AS total FROM sales_reports WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")->fetch_assoc()['total'] ?? 0;
$sales_year = $conn->query("SELECT SUM(total_price) AS total FROM sales_reports WHERE YEAR(created_at) = YEAR(CURRENT_DATE())")->fetch_assoc()['total'] ?? 0;
$total_sales = $conn->query("SELECT SUM(total_price) AS total_sales FROM sales_reports")->fetch_assoc()['total_sales'] ?? 0;

// Filtered Query
$where = "";
switch ($filter) {
    case 'today':
        $where = "WHERE DATE(sr.created_at) = CURDATE()";
        break;
    case 'year':
        $where = "WHERE YEAR(sr.created_at) = YEAR(CURRENT_DATE())";
        break;
    case 'month':
        $where = "WHERE DATE_FORMAT(sr.created_at, '%Y-%m') = '$month_filter'";
        break;
}

// Sales Data Query
$sql = "SELECT sr.*, u.username AS user_name FROM sales_reports sr 
        JOIN users u ON sr.user_id = u.id 
        $where
        ORDER BY sr.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Export Plugins -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <style>
        body { background-color: #f9f9f9; font-family: 'Segoe UI', sans-serif; }
        .stats-card { background: linear-gradient(135deg, #36d1dc, #5b86e5); color: white; padding: 1rem; border-radius: 10px; }
        .stats-card h5 { margin-bottom: 0.5rem; }
        .table thead { background: #0d6efd; color: white; }
        .export-btns button { margin-right: 10px; }
    </style>
</head>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .stats-card {
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        color: #fff;
        background-color: #343a40;
        text-align: center;
        height: 100%;
    }

    .stats-card h5 {
        font-size: 1rem;
        margin-bottom: 5px;
    }

    .stats-card h4 {
        font-size: 1.6rem;
        margin: 0;
    }

    .stats-card i {
        font-size: 1.8rem;
        margin-bottom: 10px;
        display: block;
    }

    .export-btns button {
        margin-left: 5px;
    }

    /* Responsive Tweaks */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 15px;
        }

        .export-btns {
            text-align: center !important;
            margin-top: 10px;
        }

        .export-btns button {
            display: block;
            width: 100%;
            margin-bottom: 8px;
        }

        .form-select,
        .form-control {
            width: 100%;
        }

        .container {
            padding-left: 10px;
            padding-right: 10px;
        }

        .card-title {
            font-size: 1.2rem;
        }

        .btn-outline-primary {
            position: static !important;
            display: block;
            margin-bottom: 15px;
            text-align: center;
        }

        h2.text-center {
            font-size: 1.5rem;
        }

        canvas {
            max-width: 100%;
        }
    }
</style>


<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center w-100">📊 Sales Reports</h2>
        <a href="dashboard.php" class="btn btn-outline-primary position-absolute end-0"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-2">
            <div class="stats-card bg-secondary">
                <i class="bi bi-calendar-day-fill"></i>
                <h5>Today</h5>
                <h4>₹<?= number_format($sales_today, 2) ?></h4>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card bg-info">
                <i class="bi bi-calendar-week-fill"></i>
                <h5>This Month</h5>
                <h4>₹<?= number_format($sales_month, 2) ?></h4>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card bg-primary">
                <i class="bi bi-calendar-fill"></i>
                <h5>This Year</h5>
                <h4>₹<?= number_format($sales_year, 2) ?></h4>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stats-card bg-success">
                <i class="bi bi-currency-rupee"></i>
                <h5>Total Sales</h5>
                <h4>₹<?= number_format($total_sales, 2) ?></h4>
            </div>
        </div>
    </div>

    <!-- Filter Options -->
    <form method="GET" class="row g-2 mb-3 align-items-center">
        <div class="col-md-3">
            <select name="filter" class="form-select" onchange="this.form.submit()">
                <option value="month" <?= $filter == 'month' ? 'selected' : '' ?>>Custom Month</option>
                <option value="today" <?= $filter == 'today' ? 'selected' : '' ?>>Today</option>
                <option value="year" <?= $filter == 'year' ? 'selected' : '' ?>>This Year</option>
            </select>
        </div>
        <?php if ($filter == 'month'): ?>
        <div class="col-md-3">
            <input type="month" name="month" value="<?= $month_filter ?>" class="form-control" onchange="this.form.submit()">
        </div>
        <?php endif; ?>
        <div class="col-md-6 text-end export-btns">
            <button type="button" class="btn btn-outline-success" onclick="exportToExcel()"><i class="bi bi-file-earmark-excel"></i> Export Excel</button>
            <button type="button" class="btn btn-outline-danger" onclick="exportToPDF()"><i class="bi bi-file-earmark-pdf"></i> Export PDF</button>
        </div>
    </form>

    <!-- Sales Table -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-hover" id="salesTable">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>User Name</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): 
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= $row['user_name'] ?></td>
                        <td>₹<?= number_format($row['total_price'], 2) ?></td>
                        <td><?= $row['payment_method'] ?></td>
                        <td><?= date('d-m-Y H:i:s', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="5" class="text-center text-danger">No Sales Found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Chart Section -->
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title text-center mb-3"><i class="bi bi-bar-chart-line-fill text-primary"></i> Sales Comparison (Today / Month / Year)</h5>
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Today', 'This Month', 'This Year'],
            datasets: [{
                label: 'Sales (₹)',
                data: [<?= $sales_today ?>, <?= $sales_month ?>, <?= $sales_year ?>],
                backgroundColor: ['#0dcaf0', '#0d6efd', '#198754'],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value;
                        }
                    }
                }
            }
        }
    });

    function exportToExcel() {
        const table = document.getElementById("salesTable");
        const wb = XLSX.utils.table_to_book(table, { sheet: "Sales Report" });
        XLSX.writeFile(wb, "Sales_Report.xlsx");
    }

    async function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text("Sales Report", 14, 15);
        const table = document.getElementById("salesTable");
        let data = [];
        for (let row of table.rows) {
            let rowData = [];
            for (let cell of row.cells) {
                rowData.push(cell.innerText);
            }
            data.push(rowData);
        }
        doc.autoTable({
            head: [data[0]],
            body: data.slice(1),
            startY: 25,
            theme: 'grid'
        });
        doc.save("Sales_Report.pdf");
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
