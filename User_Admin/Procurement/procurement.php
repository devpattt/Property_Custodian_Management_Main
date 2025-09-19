<?php
session_start();
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
        include '../../components/nav-bar.php'
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
    <?php
    $deliveries = [
        ["icon"=>"💻","item"=>"Dell Laptop","category"=>"Electronics","qty"=>10,"unit"=>"pcs","supplier"=>"ABC Computers","status"=>"Received"],
        ["icon"=>"🪑","item"=>"Office Chair","category"=>"Furniture","qty"=>25,"unit"=>"pcs","supplier"=>"XYZ Furnitures","status"=>"Pending"],
        ["icon"=>"🖨️","item"=>"HP Printer","category"=>"Electronics","qty"=>2,"unit"=>"pcs","supplier"=>"Tech Supplies","status"=>"Rejected"],
        ["icon"=>"🎨","item"=>"Wall Paint","category"=>"Consumables","qty"=>15,"unit"=>"cans","supplier"=>"Color World","status"=>"Received"],
        ["icon"=>"✏️","item"=>"Markers","category"=>"Consumables","qty"=>50,"unit"=>"pcs","supplier"=>"Office Supplies Inc.","status"=>"Pending"],
        ["icon"=>"🖥️","item"=>"Desktop PC","category"=>"Electronics","qty"=>5,"unit"=>"pcs","supplier"=>"Tech Solutions","status"=>"Received"],
        ["icon"=>"📚","item"=>"Bookshelf","category"=>"Furniture","qty"=>3,"unit"=>"pcs","supplier"=>"WoodWorks","status"=>"Pending"],
        ["icon"=>"🧹","item"=>"Cleaning Mop","category"=>"Consumables","qty"=>20,"unit"=>"pcs","supplier"=>"CleanIt","status"=>"Received"]
    ];
    ?>

    </head>
    <body>

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
            <th data-col="supplier">Supplier</th>
            <th data-col="status">Status</th>
            <th data-col="actions">Actions</th> 
        </tr>
        </thead>
        <tbody>
        <?php foreach ($deliveries as $d): ?>
        <tr>
            <td><span class="icon <?php echo strtolower($d['category']); ?>"><?php echo $d['icon']; ?></span></td>
            <td><?php echo $d['item']; ?></td>
            <td><?php echo $d['category']; ?></td>
            <td><?php echo $d['qty']; ?></td>
            <td><?php echo $d['unit']; ?></td>
            <td><?php echo $d['supplier']; ?></td>
            <td><span class="status <?php echo $d['status']; ?>"><?php echo $d['status']; ?></span></td>
            <td>
            <?php if ($d['status'] == 'Received'): ?>
                <?php
                $consumableCategories = ['consumable', 'consumables', 'office supplies', 'stationery', 'cleaning supplies', 'medical supplies', 'electronics'];
                $isConsumable = false;
                foreach ($consumableCategories as $cat) {
                    if (stripos($d['category'], $cat) !== false) {
                        $isConsumable = true;
                        break;
                    }
                }

                $targetPage = $isConsumable ? 
                    '<?=BASE_URL?asset_registry_module/consumable/list_assets.php' : 
                    '<?=BASE_URL?asset_registry_module/non-consumable/list_assets.php';
                
                $params = http_build_query([
                    'assign' => 'true',
                    'delivery_id' => $d['id'] ?? 0,
                    'name' => $d['item'],
                    'category' => $d['category'],
                    'qty' => $d['qty']
                ]);
                ?>
                <a href="<?php echo $targetPage . '?' . $params; ?>" class="btn btn-success btn-sm">
                    Assign & Tag
                </a>
            <?php else: ?>
                <span class="text-muted">No Action</span>
            <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination" id="pagination"></div>
    </div>


    <div id="gridView" style="display:none;">
    <div class="grid" id="gridContent"></div>
    <div class="pagination" id="gridPagination"></div>
    </div>
    </body>
    </html>
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