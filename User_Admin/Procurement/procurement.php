<?php
session_start();
include_once '../../connection.php'; // adjust the path to your db connection file

// Fetch deliveries from database
$query = "SELECT 
            p.procurement_id,
            i.item_name AS item,
            i.category,
            p.quantity AS qty,
            i.unit,
            p.status
          FROM bcp_sms4_procurement p
          JOIN bcp_sms4_items i ON p.item_id = i.item_id"; 

$result = $conn->query($query);

$deliveries = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $icon = "📦"; // default
        if (stripos($row['category'], 'electronics') !== false) $icon = "💻";
        elseif (stripos($row['category'], 'furniture') !== false) $icon = "🪑";
        elseif (stripos($row['category'], 'consumables') !== false) $icon = "🎨";
        elseif (stripos($row['category'], 'office') !== false) $icon = "✏️";
        elseif (stripos($row['category'], 'cleaning') !== false) $icon = "🧹";

        $deliveries[] = [
            "procurement_id" => $row['procurement_id'],  // add this
            "icon" => $icon,
            "item" => $row['item'],
            "category" => $row['category'],
            "qty" => $row['qty'],
            "unit" => $row['unit'],
            "status" => $row['status']
        ];

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="../../assets/img/bagong_silang_logo.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">
  <link href="../../assets/css/schedule_maintenance.css" rel="stylesheet">
  <link href="../../assets/css/procurement.css" rel="stylesheet">
</head>
    <body>

    <?php
        include "../../components/nav-bar.php";
    ?>
    <main id="main" class="main">

    <div class="pagetitle">
        <h1>Procurement Coordination</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Admin/dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Procurement Coordination</li>
                <li class="breadcrumb-item active">Item Delivery</li>
            </ol>
        </nav>
    </div>

    <div class="controls">
        <input type="text" id="search" placeholder="Search items...">
        <div>
            <button onclick="toggleView()">Switch View</button>
            <button onclick="generateReport()">Generate Report</button>
        </div>
    </div>

    <div id="tableView">
        <table id="deliveryTable">
            <thead>
                <tr>
                    <th data-col="icon">Icon</th>
                    <th data-col="item">Item Name</th>
                    <th data-col="category">Category</th>
                    <th data-col="qty">Quantity</th>
                    <th data-col="unit">Unit</th>
                    <th data-col="status">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($deliveries)): ?>
                    <?php foreach ($deliveries as $d): ?>
                        <tr>
                            <td><span class="icon <?php echo strtolower($d['category']); ?>"><?php echo $d['icon']; ?></span></td>
                            <td><?php echo htmlspecialchars($d['item']); ?></td>
                            <td><?php echo htmlspecialchars($d['category']); ?></td>
                            <td><?php echo htmlspecialchars($d['qty']); ?></td>
                            <td><?php echo htmlspecialchars($d['unit']); ?></td>
                            <td>
                                <form method="POST" action="assign_tag.php" style="display:flex; align-items:center; gap:5px;">
                                    <input type="hidden" name="procurement_id" value="<?php echo $d['procurement_id']; ?>">

                                    <select name="status" class="form-select form-select-sm">
                                        <option value="Pending"   <?php if($d['status']=="Pending") echo "selected"; ?>>Pending</option>
                                        <option value="Rejected"<?php if($d['status']=="Rejected") echo "selected"; ?>>In-Transit</option>
                                        <option value="Approved" <?php if($d['status']=="Approved") echo "selected"; ?>>Delivered</option>
                                        <option value="Completed" <?php if($d['status']=="Completed") echo "selected"; ?>>Completed</option>
                                    </select>

                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">No deliveries found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="pagination" id="pagination"></div>
    </div>

    <div id="gridView" style="display:none;">
        <div class="grid" id="gridContent"></div>
        <div class="pagination" id="gridPagination"></div>
    </div>
</main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="../../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../../assets/vendor/quill/quill.js"></script>
  <script src="../../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../../assets/vendor/php-email-form/validate.js"></script>
  <script src="../../assets/js/main.js"></script>
  <script src="../../assets/js/procurement.js"></script>
  </body>
</html>