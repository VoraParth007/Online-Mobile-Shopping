<?php
session_name("admin_session");
session_start();
include('../includes/config.php');

// Fetch subscribers
$query = "SELECT * FROM subscribers ORDER BY subscribed_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribers Management</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --light-gray: #f0f2f5;
            --medium-gray: #e2e8f0;
            --text-dark: #1e293b;
            --text-medium: #4b5563;
            --text-light: #64748b;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, var(--light-gray), #e6e9f0);
            color: var(--text-dark);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            padding-bottom: 2rem;
        }
        
        .dashboard-header {
            background: var(--card-bg);
            border-bottom: 1px solid var(--card-border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow);
        }
        
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .header-title {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-dark);
        }
        
        .header-icon {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }
        
        .card-header {
            background: var(--light);
            border-bottom: 1px solid var(--card-border);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            margin: 0;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--medium-gray);
        }
        
        .subscribers-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }
        
        .subscribers-table thead {
            background: var(--primary-light);
            position: sticky;
            top: 0;
        }
        
        .subscribers-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark);
            border-bottom: 2px solid var(--card-border);
        }
        
        .subscribers-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--card-border);
            color: var(--text-medium);
        }
        
        .subscribers-table tbody tr {
            transition: background 0.2s;
        }
        
        .subscribers-table tbody tr:hover {
            background: var(--primary-light);
        }
        
        .email-cell {
            position: relative;
        }
        
        .email-cell::after {
            content: "📧";
            position: absolute;
            left: -25px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            opacity: 0.7;
        }
        
        .date-cell {
            position: relative;
        }
        
        .date-cell::after {
            content: "🕒";
            position: absolute;
            left: -25px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            opacity: 0.7;
        }
        
        .stats-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .stats-content h5 {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }
        
        .stats-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-dark);
        }
        
        .btn-export {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-excel {
            background: linear-gradient(135deg, #21a366, #138d75);
            border: none;
            color: white;
        }
        
        .btn-pdf {
            background: linear-gradient(135deg, #e52d27, #b31217);
            border: none;
            color: white;
        }
        
        .btn-excel:hover, .btn-pdf:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15);
        }
        
        .back-btn {
            background: var(--primary-light);
            border: 1px solid var(--primary);
            color: var(--primary);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }
        
        .back-btn:hover {
            background: var(--primary);
            color: white;
        }
        
        .no-data {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            flex-direction: column;
            color: var(--text-light);
            padding: 2rem;
            text-align: center;
        }
        
        .no-data i {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            color: var(--medium-gray);
            opacity: 0.7;
        }
        
        /* Mobile Optimizations */
        .mobile-stats-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 1.5rem;
        }
        
        .mobile-stats-card {
            flex: 1 0 calc(50% - 12px);
            min-width: 140px;
        }
        
        .mobile-stats-card .stats-card {
            padding: 1.25rem;
        }
        
        .mobile-stats-card .stats-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
        
        .mobile-stats-card h5 {
            font-size: 0.9rem;
        }
        
        .mobile-stats-card h2 {
            font-size: 1.7rem;
        }
        
        .mobile-table-container {
            border: none;
            border-radius: 0;
        }
        
        .mobile-table th, 
        .mobile-table td {
            padding: 0.85rem;
            font-size: 0.92rem;
        }
        
        .mobile-table thead {
            position: static;
        }
        
        .mobile-card-header {
            padding: 1rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .mobile-card-body {
            padding: 1rem;
        }
        
        .email-cell::after, .date-cell::after {
            display: none;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header-title h1 {
                font-size: 1.6rem;
            }
            
            .header-icon {
                width: 42px;
                height: 42px;
                font-size: 1.3rem;
            }
            
            .mobile-stats-row {
                display: flex;
            }
            
            .desktop-stats-row {
                display: none;
            }
            
            .mobile-table-container {
                border: none;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .mobile-table {
                min-width: 100%;
            }
            
            .card-title {
                font-size: 1.1rem;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-stats-row {
                display: none;
            }
            
            .desktop-stats-row {
                display: flex;
            }
        }
        
        .badge {
            padding: 0.5em 0.75em;
            border-radius: 0.5rem;
            font-weight: 500;
        }
        
        .stats-row {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <div class="main-container">
            <div class="header-content">
                <div class="header-title">
                    <div class="header-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h1>Subscribers Management</h1>
                </div>
                <a href="dashboard.php" class="btn back-btn">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="main-container mt-4">
        <!-- Stats Section - Mobile Optimized -->
        <div class="mobile-stats-row">
            <div class="mobile-stats-card">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <h5>TOTAL SUBSCRIBERS</h5>
                        <h2><?= $result ? $result->num_rows : 0 ?></h2>
                    </div>
                </div>
            </div>
            <div class="mobile-stats-card">
                <div class="stats-card">
                    <div class="stats-icon" style="background: linear-gradient(135deg, #4cc9f0, #3a86ff);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-content">
                        <h5>ACTIVE THIS MONTH</h5>
                        <h2>
                            <?php
                            $current_month = date('m');
                            $active_month_query = "SELECT COUNT(*) AS count FROM subscribers WHERE MONTH(subscribed_at) = $current_month";
                            $active_result = $conn->query($active_month_query);
                            $active_count = $active_result ? $active_result->fetch_assoc()['count'] : 0;
                            echo $active_count;
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section - Desktop -->
        <div class="row stats-row desktop-stats-row">
            <div class="col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <h5>TOTAL SUBSCRIBERS</h5>
                        <h2><?= $result ? $result->num_rows : 0 ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon" style="background: linear-gradient(135deg, #4cc9f0, #3a86ff);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-content">
                        <h5>ACTIVE THIS MONTH</h5>
                        <h2>
                            <?php
                            $current_month = date('m');
                            $active_month_query = "SELECT COUNT(*) AS count FROM subscribers WHERE MONTH(subscribed_at) = $current_month";
                            $active_result = $conn->query($active_month_query);
                            $active_count = $active_result ? $active_result->fetch_assoc()['count'] : 0;
                            echo $active_count;
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscribers List Card -->
        <div class="card">
            <div class="card-header mobile-card-header">
                <div>
                    <h3 class="card-title">
                        <i class="fas fa-list me-2"></i>Subscribers List
                    </h3>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-export btn-excel" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button type="button" class="btn btn-export btn-pdf" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                </div>
            </div>
            <div class="card-body mobile-card-body">
                <div class="table-container mobile-table-container">
                    <?php if ($result && $result->num_rows > 0): ?>
                    <table class="subscribers-table mobile-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email Address</th>
                                <th>Subscription Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td class="email-cell"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="date-cell"><?= date('d M Y, H:i', strtotime($row['subscribed_at'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-database"></i>
                        <h4>No Subscribers Found</h4>
                        <p class="mt-2">Try checking back later or promote your newsletter</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Export to Excel function
        function exportToExcel() {
            const table = document.querySelector(".subscribers-table");
            if (!table) {
                alert("No data to export");
                return;
            }
            
            const wb = XLSX.utils.table_to_book(table, { sheet: "Subscribers" });
            XLSX.writeFile(wb, "Subscribers_List_<?= date('Ymd_His') ?>.xlsx");
            
            // Show notification
            showExportNotification('Excel');
        }

        // Export to PDF function
        function exportToPDF() {
            const table = document.querySelector(".subscribers-table");
            if (!table) {
                alert("No data to export");
                return;
            }
            
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Add title
            doc.setFontSize(18);
            doc.text("Subscribers List", 14, 15);
            
            // Add date
            doc.setFontSize(10);
            doc.text(`Generated: ${new Date().toLocaleString()}`, 14, 22);
            
            // Add table
            doc.autoTable({
                html: table,
                startY: 28,
                theme: 'grid',
                headStyles: {
                    fillColor: [67, 97, 238],
                    textColor: 255
                },
                styles: {
                    font: 'helvetica',
                    fontSize: 10,
                    cellPadding: 3,
                    valign: 'middle'
                }
            });
            
            doc.save("Subscribers_List_<?= date('Ymd_His') ?>.pdf");
            
            // Show notification
            showExportNotification('PDF');
        }
        
        // Show export notification
        function showExportNotification(type) {
            const notification = document.createElement('div');
            notification.innerHTML = `
                <div style="position: fixed; bottom: 20px; right: 20px; background: #ffffff; color: #1e293b; padding: 15px 20px; border-radius: 12px; border-left: 4px solid #4361ee; box-shadow: 0 4px 15px rgba(0,0,0,0.1); z-index: 1000; display: flex; align-items: center; gap: 10px; animation: slideIn 0.3s ease-out;">
                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.2rem;"></i>
                    <span>${type} export completed successfully!</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
