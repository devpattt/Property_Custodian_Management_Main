<?php
include __DIR__ . '/../config.php';

$userRole = $_SESSION['user_type'] ?? 'guest';
$userFullname = $_SESSION['fullname'] ?? 'Guest User';
$username = $_SESSION['username'] ?? 'Guest';
$userId = $_SESSION['user_id'] ?? 'N/A';
?>
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="<?=BASE_URL?>assets/img/default_profile.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($userFullname); ?></span>
          </a>

          <!--ADMIN-->
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

            <?php if($userRole === 'admin'): ?>
              <li class="dropdown-header">
                <h6>Administrator</h6>
              </li>
            <?php elseif($userRole === 'teacher'): ?>
              <li class="dropdown-header">
                <h6>Teacher</h6>
              </li>
            <?php elseif($userRole === 'custodian'): ?>
              <li class="dropdown-header">
                <h6>Custodian</h6>
              </li>
            <?php else: ?>
            <li class="dropdown-header">
              <h6>Guest</h6>
            </li>
            <?php endif;?>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?=BASE_URL?>login/logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul>
        </li>
      </ul>
    </nav>
  </header>

  <?php
    $current_page = basename($_SERVER['PHP_SELF']);
  ?>

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <div class="flex items-center justify-center w-full h-16 bg-transparent">
            <img src="<?=BASE_URL?>assets/img/bagong_silang_logo.png" 
                alt="Logo" 
                style="width: 120px; height: auto; margin: 0 auto; display: block;">
        </div>
    <hr class="sidebar-divider">
    
      <!-- ================= ADMIN ================= -->
      <?php if($userRole === 'admin'): ?>
      <li class="nav-item <?php if($current_page == 'dashboard.php') echo 'active'; ?>">
              <a class="nav-link" href="<?= BASE_URL ?>User_Admin/dashboard.php">
                <i class="bi bi-grid"></i>
                <span>Reporting & Analytics</span>
              </a>
            </li>
            <hr class="sidebar-divider">

        <!-- Procurement Coordination -->
          <?php 
            $procurement_pages = [
              'procurement.php'
            ]; 
            $is_procurement_active = in_array($current_page, $procurement_pages);
          ?>
          <li class="nav-item">
            <a class="nav-link collapsed <?= $is_procurement_active ? 'active' : '' ?>" 
              data-bs-target="#procurement-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-layout-text-window-reverse"></i>
              <span>Procurement Coordination</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="procurement-nav" class="nav-content collapse <?= $is_procurement_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
              <li>
                <a href="<?= BASE_URL ?>User_Admin/Procurement/procurement.php" class="<?= $current_page == 'procurement.php' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Item Delivery</span>
                </a>
              </li>
            </ul>
          </li>

          <!-- Asset Registry & Tagging -->
          <?php 
            $asset_pages = ['consumable.php','non-consumable.php']; 
            $is_asset_active = in_array($current_page, $asset_pages);
          ?>
          <li class="nav-item">
            <a class="nav-link collapsed <?= $is_asset_active ? 'active' : '' ?>" 
              data-bs-target="#asset-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-menu-button-wide"></i>
              <span>Asset Registry & Tagging</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="asset-nav" class="nav-content collapse <?= $is_asset_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
              <li>
                <a href="<?= BASE_URL ?>User_Admin/asset_registry_module/consumable/consumable.php" 
                  class="<?= $current_page == 'consumable.php' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Consumable</span>
                </a>
              </li>
              <li>
                <a href="<?= BASE_URL ?>User_Admin/asset_registry_module/non-consumable/non-consumable.php" 
                  class="<?= $current_page == 'non-consumable.php' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Non-Consumable</span>
                </a>
              </li>
            </ul>
          </li>

            
          <!-- Property Issuance & Acknowledgment -->
          <?php 
            $issuance_pages = ['forms-elements.html','forms-layouts.html','forms-editors.html','forms-validation.html']; 
            $is_issuance_active = in_array($current_page, $issuance_pages);
          ?>
          <li class="nav-item">
            <a class="nav-link collapsed <?= $is_issuance_active ? 'active' : '' ?>" 
              data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-journal-text"></i>
              <span>Property Issuance & Acknowledgment</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse <?= $is_issuance_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
              <li>
                <a href="forms-elements.html" class="<?= $current_page == 'forms-elements.html' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Form Elements</span>
                </a>
              </li>
              <li>
                <a href="forms-layouts.html" class="<?= $current_page == 'forms-layouts.html' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Form Layouts</span>
                </a>
              </li>
              <li>
                <a href="forms-editors.html" class="<?= $current_page == 'forms-editors.html' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Form Editors</span>
                </a>
              </li>
              <li>
                <a href="forms-validation.html" class="<?= $current_page == 'forms-validation.html' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Form Validation</span>
                </a>
              </li>
            </ul>
          </li>

          <!-- Custodian Assignment & Transfer -->
          <?php 
            $custodian_pages = ['charts-chartjs.html','charts-apexcharts.html','charts-echarts.html']; 
            $is_custodian_active = in_array($current_page, $custodian_pages);
          ?>
          <li class="nav-item">
            <a class="nav-link collapsed <?= $is_custodian_active ? 'active' : '' ?>" 
              data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-bar-chart"></i>
              <span>Custodian Assignment & Transfer</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="charts-nav" class="nav-content collapse <?= $is_custodian_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
              <li>
                <a href="charts-chartjs.html" class="<?= $current_page == 'charts-chartjs.html' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Chart.js</span>
                </a>
              </li>
              <li>
                <a href="charts-apexcharts.html" class="<?= $current_page == 'charts-apexcharts.html' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>ApexCharts</span>
                </a>
              </li>
              <li>
                <a href="charts-echarts.html" class="<?= $current_page == 'charts-echarts.html' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>ECharts</span>
                </a>
              </li>
            </ul>
          </li>

          <!-- Supplies Inventory Management -->
          <?php 
            $supplies_pages = ['inventory.php']; 
            $is_supplies_active = in_array($current_page, $supplies_pages);
          ?>
          <li class="nav-item">
            <a class="nav-link collapsed <?= $is_supplies_active ? 'active' : '' ?>" 
              data-bs-target="#supplies-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-layout-text-window-reverse"></i>
              <span>Supplies Inventory Management</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="supplies-nav" class="nav-content collapse <?= $is_supplies_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
              <li>
                <a href="<?=BASE_URL?>User_Admin/supplies_inventory/inventory.php" class="<?= $current_page == 'inventory.php' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Inventory</span>
                </a>
              </li>
            </ul>
          </li>

          <!-- Preventive Maintenance Scheduling -->
          <?php 
            $maintenance_pages = [
              'schedule_maintenance.php',
              'records_of_maintenance.php'
            ]; 
            $is_maintenance_active = in_array($current_page, $maintenance_pages);
          ?>
          <li class="nav-item">
            <a class="nav-link collapsed <?= $is_maintenance_active ? 'active' : '' ?>" 
              data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-gem"></i>
              <span>Preventive Maintenance Scheduling</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="icons-nav" class="nav-content collapse <?= $is_maintenance_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
              <li>
                <a href="<?= BASE_URL ?>User_Admin/Preventive_Maintenance_Module/schedule_maintenance.php" 
                  class="<?= $current_page == 'schedule_maintenance.php' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Schedule Maintenance</span>
                </a>
              </li>
              <li>
                <a href="<?= BASE_URL ?>User_Admin/Preventive_Maintenance_Module/records_of_maintenance.php" 
                  class="<?= $current_page == 'records_of_maintenance.php' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Maintenance Records</span>
                </a>
              </li>
            </ul>
          </li>


          <!-- Lost, Damaged, or Unserviceable Items -->
          <?php 
            $lost_pages = [
              'reporting_management.php',
            ]; 
            $is_lost_active = in_array($current_page, $lost_pages);
          ?>
          <li class="nav-item">
            <a class="nav-link collapsed <?= $is_lost_active ? 'active' : '' ?>" 
              data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-layout-text-window-reverse"></i>
              <span>Lost, Damaged, or Unserviceable Items</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse <?= $is_lost_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
              <li>
                <a href="<?= BASE_URL ?>User_Admin/asset_reporting_module/reporting_management.php" 
                  class="<?= $current_page == 'reporting_management.php' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Manage Reports</span>
                </a>
              </li>
            </ul>
          </li>

          <!-- Property Audit & Physical Inventory -->
          <?php 
            $audit_pages = [
              'property_audit.php',
            ]; 
            $is_audit_active = in_array($current_page, $audit_pages);
          ?>
          <li class="nav-item">
            <a class="nav-link collapsed <?= $is_audit_active ? 'active' : '' ?>" 
              data-bs-target="#audit-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-layout-text-window-reverse"></i>
              <span>Property Audit & Physical Inventory</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="audit-nav" class="nav-content collapse <?= $is_audit_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
              <li>
                <a href="<?= BASE_URL ?>User_Admin/Property_Audit/property_audit.php" class="<?= $current_page == 'property_audit.php' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Property Audit</span>
                </a>
              </li>
            </ul>
          </li>

          <!-- User Roles & Access Control -->
          <?php 
            $user_roles_pages = [
              'tables-general.html',
              'tables-data.html'
            ]; 
            $is_user_roles_active = in_array($current_page, $user_roles_pages);
          ?>
          <li class="nav-item">
            <a class="nav-link collapsed <?= $is_user_roles_active ? 'active' : '' ?>" 
              data-bs-target="#user-roles-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-layout-text-window-reverse"></i>
              <span>User Roles & Access Control</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="user-roles-nav" class="nav-content collapse <?= $is_user_roles_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
              <li>
                <a href="tables-general.html" class="<?= $current_page == 'tables-general.html' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>General Tables</span>
                </a>
              </li>
              <li>
                <a href="tables-data.html" class="<?= $current_page == 'tables-data.html' ? 'active' : '' ?>">
                  <i class="bi bi-circle"></i><span>Data Tables</span>
                </a>
              </li>
            </ul>
          </li>
       </ul>
<?php endif; ?>

 <!-- ================= TEACHER ================= -->
<?php if($userRole === 'teacher'): ?>

    <li class="nav-item <?php if($current_page == 'dashboard_teacher.php') echo 'active'; ?>">
        <a class="nav-link" href="<?= BASE_URL ?>User_Teachers/dashboard_teacher.php">
            <i class="bi bi-grid"></i>
            <span>Reporting & Analytics</span>
        </a>
    </li>

    <hr class="sidebar-divider">

        <?php
            $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <li class="nav-item">
          <a class="nav-link collapsed <?php if(in_array($current_page, ['report_item.php','track_reports.php'])) echo 'active'; ?>" 
            data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i>
            <span>Report</span>
            <i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse <?php if(in_array($current_page, ['report_item.php','track_reports.php'])) echo 'show'; ?>" data-bs-parent="#sidebar-nav">
            <li>
              <a href="<?= BASE_URL ?>User_Teachers/report_item.php" 
                class="<?php if($current_page == 'report_item.php') echo 'active'; ?>">
                <i class="bi bi-circle"></i><span>Report Item</span>
              </a>
            </li>
            <li>
              <a href="<?= BASE_URL ?>User_Teachers/track_reports.php" 
                class="<?php if($current_page == 'track_reports.php') echo 'active'; ?>">
                <i class="bi bi-circle"></i><span>Track Status</span>
              </a>
            </li>
            
          </ul>
        </li>

<?php endif; ?>

<!-- ================= CUSTODIAN ================= -->
<?php if($userRole === 'custodian'):?>
    <?php 
      $currentPage = basename($_SERVER['PHP_SELF']); 
    ?>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage === 'dashboard_custodians.php' ? 'active' : '' ?>" 
           href="<?=BASE_URL?>User_Custodians/dashboard_custodians.php">
            <i class="bi bi-grid"></i>
            <span>Reporting & Analytics</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <li class="nav-item">
      <a class="nav-link <?= ($currentPage === 'custodian_reports.php' || $currentPage === 'custodian_inventory.php') ? '' : 'collapsed' ?>" 
         data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i>
        <span>Request</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav" class="nav-content collapse <?= ($currentPage === 'custodian_reports.php' || $currentPage === 'custodian_inventory.php') ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?=BASE_URL?>User_Custodians/custodian_reports.php" 
             class="<?= $currentPage === 'custodian_reports.php' ? 'active' : '' ?>">
            <i class="bi bi-circle"></i><span>View Reports</span>
          </a>
        </li>
        <li>
          <a href="<?=BASE_URL?>User_Custodians/custodian_inventory.php" 
             class="<?= $currentPage === 'custodian_inventory.php' ? 'active' : '' ?>">
            <i class="bi bi-circle"></i><span>Inventory</span>
          </a>
        </li>
      </ul>
    </li>
<?php endif; ?>
</aside>