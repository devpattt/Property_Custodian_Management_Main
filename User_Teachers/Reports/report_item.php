<?php
session_start();
include "../../connection.php"; // <-- make sure this points to your DB connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Report Item</title>
  <link href="../../assets/img/bagong_silang_logo.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">
</head>

<body>
  <?php include "../../components/nav-bar.php"; ?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Report Item</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Teachers/dashboard_teacher.php">Home</a></li>
          <li class="breadcrumb-item active">Report Item</li>
        </ol>
      </nav>
    </div>

    <section class="section dashboard">
      <form method="post" action="<?=BASE_URL?>User_Teachers/Reports/save_report.php" enctype="multipart/form-data" class="report-form">
        <h2>Report Lost/Damaged Item</h2>

      <div class="form-group">
        <label for="asset_id">Asset Tag</label>
        <select id="asset_id" name="asset_id" required>
          <option value="">-- Select Asset --</option>
          <?php
            // Fetch all assets that are in use or available for reporting
            $assets = $conn->query("
              SELECT a.asset_id, a.property_tag, i.item_name
              FROM bcp_sms4_asset a
              JOIN bcp_sms4_items i ON a.item_id = i.item_id
              WHERE a.status != 'Disposed'
              ORDER BY a.property_tag ASC
            ");
            if ($assets && $assets->num_rows > 0) {
              while ($row = $assets->fetch_assoc()) {
                echo "<option value='{$row['asset_id']}'>{$row['property_tag']} ({$row['item_name']})</option>";
              }
            }
          ?>
        </select>
      </div>


        <!-- Report Type -->
        <div class="form-group">
          <label for="report_type">Report Type:</label>
          <select id="report_type" name="report_type" required>
            <option value="Lost">Lost</option>
            <option value="Damaged">Damaged</option>
            <option value="Repair/Replacement">Repair/Replacement</option>
          </select>
        </div>

        <!-- Description -->
        <div class="form-group">
          <label for="description">Description:</label>
          <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <!-- Photo Upload -->
        <div class="form-group">
          <label for="photo">Upload Photo (Optional):</label>
          <input type="file" id="photo" name="photo" accept="image/*">
        </div>

        <button type="submit" class="btn-submit">Submit Report</button>
      </form>
    </section>
  </main>

  <style>
    .report-form {
      max-width: 500px;
      margin: 30px auto;
      padding: 20px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
      font-family: Arial, sans-serif;
    }
    .report-form h2 { text-align: center; margin-bottom: 20px; color: #333; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 6px; color: #444; }
    .form-group select,
    .form-group textarea,
    .form-group input[type="file"] {
      width: 100%; padding: 10px; border: 1px solid #ccc;
      border-radius: 8px; font-size: 14px;
    }
    .form-group textarea { resize: vertical; }
    .btn-submit {
      width: 100%; padding: 12px; background: #007bff; color: white;
      border: none; border-radius: 8px; font-size: 16px; cursor: pointer;
    }
    .btn-submit:hover { background: #0056b3; }
  </style>

  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/js/main.js"></script>
</body>
</html>
