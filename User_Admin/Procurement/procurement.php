<?php
session_start();
include_once '../../connection.php'; 

// Updated query to include supplier from items table
$query = "SELECT 
            p.procurement_id,
            i.item_name AS item,
            i.category,
            p.quantity AS qty,
            i.unit,
            COALESCE(i.supplier_name, 'Not Assigned') AS supplier_name,
            p.status
          FROM bcp_sms4_procurement p
          JOIN bcp_sms4_items i ON p.item_id = i.item_id
          ORDER BY 
            CASE WHEN p.status = 'Completed' THEN 2 ELSE 1 END,
            p.procurement_id DESC";

$result = $conn->query($query);

$deliveries = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $icon = "📦"; 
        if (stripos($row['category'], 'electronics') !== false) $icon = "💻";
        elseif (stripos($row['category'], 'furniture') !== false) $icon = "🪑";
        elseif (stripos($row['category'], 'consumables') !== false) $icon = "🎨";
        elseif (stripos($row['category'], 'office') !== false) $icon = "✏️";
        elseif (stripos($row['category'], 'cleaning') !== false) $icon = "🧹";

        $deliveries[] = [
            "procurement_id" => $row['procurement_id'],  
            "icon" => $icon,
            "item" => $row['item'],
            "category" => $row['category'],
            "qty" => $row['qty'],
            "unit" => $row['unit'],
            "supplier" => $row['supplier_name'],
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
  <title>Procurement Coordination</title>
  <link href="../../assets/img/bagong_silang_logo.png" rel="icon">
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">
  <link href="../../assets/css/procurement.css" rel="stylesheet">
</head>
<body>

<?php include "../../components/nav-bar.php"; ?>

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

  <!-- Controls -->
  <div class="controls d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
      <input type="text" id="search" class="form-control" placeholder="Search items...">
      <select id="statusFilter" class="form-select w-auto">
        <option value="All">All Status</option>
        <option value="Pending">Pending</option>
        <option value="Approved">In-Transit</option>
        <option value="Rejected">Delivered</option>
        <option value="Completed">Completed</option>
      </select>
    </div>
    <button 
        class="btn btn-success" 
        data-bs-toggle="modal" 
        data-bs-target="#reportModal"> 
        Generate Report
    </button>
  </div>
  <?php include 'report_modal.php';?>

  <!-- Table View -->
  <div id="tableView">
    <table id="deliveryTable" class="table">
      <thead>
        <tr>
          <th>Icon</th>
          <th>Item Name</th>
          <th>Category</th>
          <th>Quantity</th>
          <th>Unit</th>
          <th>Supplier</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($deliveries)): ?>
          <?php foreach ($deliveries as $d): ?>
            <tr>
              <td><span class="icon"><?= $d['icon']; ?></span></td>
              <td><?= htmlspecialchars($d['item']); ?></td>
              <td><?= htmlspecialchars($d['category']); ?></td>
              <td><?= htmlspecialchars($d['qty']); ?></td>
              <td><?= htmlspecialchars($d['unit']); ?></td>
              <td>
                <?php if ($d['supplier'] === 'Not Assigned'): ?>
                  <span class="badge bg-secondary"><?= $d['supplier']; ?></span>
                <?php else: ?>
                  <?= htmlspecialchars($d['supplier']); ?>
                <?php endif; ?>
              </td>
              <td>
                <form method="POST" action="assign_tag.php" class="d-flex align-items-center gap-2">
                  <input type="hidden" name="procurement_id" value="<?= $d['procurement_id']; ?>">
                  <select name="status" class="form-select form-select-sm">
                    <option value="Pending"   <?= $d['status']=="Pending" ? "selected" : "" ?>>Pending</option>
                    <option value="Approved"  <?= $d['status']=="Approved" ? "selected" : "" ?>>In-Transit</option>
                    <option value="Rejected"  <?= $d['status']=="Rejected" ? "selected" : "" ?>>Delivered</option>
                    <option value="Completed" <?= $d['status']=="Completed" ? "selected" : "" ?>>Completed</option>
                  </select>
                  <button 
                    type="submit" 
                    class="btn btn-sm btn-primary update-btn"
                    data-original="<?= $d['status']; ?>"
                    <?= $d['status']=="Completed" ? "disabled" : "" ?>>
                    Update
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7">No deliveries found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</main>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Update</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to update the status of this item?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmUpdate">Confirm</button>
      </button>
      </div>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="position-fixed top-0 end-0" style="z-index:1100; margin-top:63px; margin-right:15px;">
  <div id="updateToast" class="toast align-items-center text-bg-success border-0" role="alert" data-bs-delay="3000">
    <div class="d-flex">
      <div class="toast-body">Update Successfully!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
  <i class="bi bi-arrow-up-short"></i>
</a>
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
<script src="../../assets/js/procurement-update.js"></script>

</body>
</html>