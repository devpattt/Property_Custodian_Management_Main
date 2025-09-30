<?php
session_start();
include "../../../../connection.php"; 
// include "get_rows.php"; 
// include "edit_button.php"; 
// include "edit_modal.php";   

$query = "
SELECT 
    i.id,
    i.reference_no,
    it.item_name,
    CASE 
        WHEN i.asset_id IS NOT NULL THEN 'Asset'
        WHEN i.consumable_id IS NOT NULL THEN 'Consumable'
        ELSE it.category
    END AS category,
    i.quantity,
    i.assigned_date,
    i.end_date,
    i.remarks,
    t.fullname AS teacher_name,
    d.dept_name AS department,   -- ✅ correct department name
    a.fullname AS admin_name,
    ass.property_tag AS asset_tag
FROM bcp_sms4_issuance i
JOIN bcp_sms4_items it ON i.item_id = it.item_id
JOIN bcp_sms4_admins t ON i.teacher_id = t.id
JOIN bcp_sms4_departments d ON i.department_id = d.id   -- ✅ FIXED join
JOIN bcp_sms4_admins a ON i.issued_by = a.id
LEFT JOIN bcp_sms4_asset ass ON i.asset_id = ass.asset_id
ORDER BY i.assigned_date DESC;

";


$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="../../../../assets/img/bagong_silang_logo.png" rel="icon">
  <link href="../../../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link href="../../../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../../../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../../../../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../../../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../../../../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../../../../assets/css/style.css" rel="stylesheet">
  <link href="../../../../assets/css/schedule_maintenance.css" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../../assets/js/assign_trans/history/history.js"></script>
    <script src="../../../../assets/js/assign_trans/consumable/active.js"></script>

<body>
  <?php
    include '../../../../components/nav-bar.php';
  ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Property Issuance and Acknowledgement</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Admin/dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Property Issuance and Acknowledgement</li>
          <li class="breadcrumb-item active">Non-Consumable</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <br>            
              <p>
                <em>
                  Below is a list of all active custodians and their assigned equipment.  
                  You can <b>search, sort, and filter</b> the records to quickly find details  
                  about custodians, departments, or equipment.  
                  <br>Note: If both <b>Box</b> and <b>Quantity</b> reach 0, the row will automatically be deleted.
                </em>
              </p>
              <div class="d-flex justify-content-between align-items-center mb-3">
              </div>
              <div class="table-responsive">
                <table id="activeTable" class="table datatable table-bordered table-striped">
                  <thead class="table-dark">
                    <tr>
                      <th>Reference No</th>
                      <th>Item</th>
                      <th>Category</th>
                      <th>Asset Tag</th>
                      <th>Quantity</th>
                      <th>Teacher</th>
                      <th>Department</th>
                      <th>Issued By</th>
                      <th data-type="date" data-format="YYYY/MM/DD">Issued Date</th>
                      <th data-type="date" data-format="YYYY/MM/DD">Return Date</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                      <td><?= htmlspecialchars($row['reference_no']) ?></td>
                      <td><?= htmlspecialchars($row['item_name']) ?></td>
                      <td>
                        <?php if ($row['category'] === 'Asset'): ?>
                          <span class="badge bg-primary">Asset</span>
                        <?php else: ?>
                          <span class="badge bg-info text-dark">Consumable</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if ($row['category'] === 'Asset'): ?>
                          <?= htmlspecialchars($row['asset_tag'] ?? '-') ?>
                        <?php else: ?>
                          <span class="text-muted">—</span>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($row['quantity']) ?></td>
                      <td><?= htmlspecialchars($row['teacher_name']) ?></td>
                      <td><?= htmlspecialchars($row['department']) ?></td>
                      <td><?= htmlspecialchars($row['admin_name']) ?></td>
                      <td><?= date('Y/m/d', strtotime($row['assigned_date'])) ?></td>
                      <td>
                        <?= $row['end_date'] ? date('Y/m/d', strtotime($row['end_date'])) : '<span class="text-muted">—</span>' ?>
                      </td>
                      <td>
                        <?= htmlspecialchars($row['remarks'] ?? '-') ?>
                        <!-- ✅ Transfer button -->
                      <button class="btn btn-warning btn-sm mt-1" 
                              data-bs-toggle="modal" 
                              data-bs-target="#transferModal" 
                              data-issuance="<?= $row['id'] ?>">
                        Transfer
                      </button>
                      <button class="btn btn-info btn-sm mt-1 view-history-btn" 
                              data-issuance="<?= $row['id'] ?>" 
                              data-bs-toggle="modal" 
                              data-bs-target="#historyModal">
                        View History
                      </button>
                      </td>
                    </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="11" class="text-center">No active custodians found</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

      <script>
      $(document).ready(function() {
        $('#activeTable').DataTable({
          dom: '<"top d-flex justify-content-between"l>rt<"bottom d-flex justify-content-between"ip>', 
          initComplete: function() {
            $('div.dataTables_length').after(`
              <div class="ms-3">
                <button type="button" class="btn btn-success" 
                  data-bs-toggle="modal" 
                  data-bs-target="#assignModal">
                  <i class="bi bi-plus-circle"></i> Assign Asset
                </button>
              </div>
            `);
          }
        });
      });
      </script>

    <script>
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').DataTable({
                "pageLength": 10,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "columnDefs": [
                    {
                        "targets": 4, 
                        "type": "date"
                    },
                    {
                        "targets": 5, 
                        "orderable": true
                    }
                ]
            });
        }
    });
    </script>
  </main>
<div class="modal fade" id="transferModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="transfer_custodian.php" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reassign to Teacher</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="issuance_id" id="issuance_id">
          
          <div class="mb-3">
            <label for="to_teacher_id" class="form-label">New Teacher</label>
            <select name="to_teacher_id" class="form-select" required>
              <?php
              $teachers = $conn->query("SELECT id, fullname FROM bcp_sms4_admins WHERE user_type = 'teacher'");
              while ($t = $teachers->fetch_assoc()) {
                  echo "<option value='{$t['id']}'>{$t['fullname']}</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" placeholder="Optional notes for reassignment"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Confirm Reassignment</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Custodian History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Custodian Transfer History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="historyTable">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>From Teacher</th>
                <th>To Teacher</th>
                <th>Transfer Date</th>
                <th>Transferred By</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
              <!-- History rows will be appended here via AJAX -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="../../../../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../../../../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../../../../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../../../../assets/vendor/quill/quill.js"></script>
  <script src="../../../../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../../../../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../../../../assets/vendor/php-email-form/validate.js"></script>
  <script src="../../../../assets/js/main.js"></script>
  </body>
</html>

<script>
  var transferModal = document.getElementById('transferModal');
  transferModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var issuanceId = button.getAttribute('data-issuance');
    transferModal.querySelector('#issuance_id').value = issuanceId;
  });

  $(document).ready(function() {
    $('#historyModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var issuanceId = button.data('issuance');

        // Clear previous rows
        $('#historyTable tbody').html('');

        // Fetch history via AJAX
        $.ajax({
            url: 'fetch_custodian_history.php',
            method: 'POST',
            data: { issuance_id: issuanceId },
            dataType: 'json',
            success: function(response) {
                if (response.length > 0) {
                    $.each(response, function(index, row) {
                        $('#historyTable tbody').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${row.from_teacher}</td>
                                <td>${row.to_teacher}</td>
                                <td>${row.transfer_date}</td>
                                <td>${row.transferred_by}</td>
                                <td>${row.remarks || '-'}</td>
                            </tr>
                        `);
                    });
                } else {
                    $('#historyTable tbody').append('<tr><td colspan="6" class="text-center">No transfer history found</td></tr>');
                }
            },
            error: function(err) {
                console.error(err);
            }
        });
    });
});
</script>

