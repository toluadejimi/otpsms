<?php
$page_name = "Dashboard";
include 'include/header-main.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? intval($_GET['status']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

$perPage = 10;
$offset = ($page - 1) * $perPage;

$whereClauses = [];
$whereClauses[] = "user_id = " . intval($userdata['id']);

if ($search !== '') {
    $safe = mysqli_real_escape_string($conn, $search);
    $whereClauses[] = "(service_name LIKE '%$safe%')";
}

if (in_array($statusFilter, [1,2,3])) {
    $whereClauses[] = "status = $statusFilter";
}

$whereSQL = implode(" AND ", $whereClauses);

$countSql = "SELECT COUNT(*) AS cnt FROM active_number WHERE $whereSQL";
$resCount = mysqli_query($conn, $countSql);
$rowCount = mysqli_fetch_assoc($resCount);
$totalRows = (int)$rowCount['cnt'];
$totalPages = ceil($totalRows / $perPage);

$sql = "SELECT number_id, service_name, number, sms_text, service_price, status, buy_time
  FROM active_number
  WHERE $whereSQL
  ORDER BY buy_time DESC
  LIMIT $offset, $perPage";

$res = mysqli_query($conn, $sql);

$active_numbers = [];
if ($res) {
  while ($r = mysqli_fetch_assoc($res)) {
    $active_numbers[] = $r;
  }
}

// A helper for status badge
function statusBadge($status) {
  switch ($status) {
    case 1:
      return '<span class="badge badge-success">Received</span>';
    case 2:
      return '<span class="badge badge-warning">Waiting</span>';
    case 3:
      return '<span class="badge badge-danger">Failed</span>';
    default:
      return '<span class="badge badge-secondary">Unknown</span>';
  }
}
?>
<script src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/simple-datatables.js"></script>
<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md hydrated"></i>
                </a>
                <div class="pageTitle">Number History</div>
            </div>
            <div class="right">
            </div>
        </div>
    </div>

    <div id="appCapsule">
        <div class="transaction-section section px-0">
             <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="card-title">
                            <i class="ri-history-line me-2 text-primary fs-5"></i>
                            Number Purchase History
                        </h2>
                        <p class="text-muted mb-0 mt-1">
                            View and track all your number purchase histories
                        </p>
                    </div>
                    <form method="GET" class="d-flex search-form flex-wrap gap-2">
                        <div class="form-group boxed">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input
                                type="text"
                                name="search"
                                class="form-control border-start-0 ps-0"
                                placeholder="Search Purchases..."
                                value="<?= htmlspecialchars($search) ?>"
                                />
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <select name="status" class="form-control custom-select">
                                <option value="">All Statuses</option>
                                <option value="1" <?= $statusFilter === 1 ? 'selected' : '' ?>>Received</option>
                                <option value="2" <?= $statusFilter === 2 ? 'selected' : '' ?>>Waiting</option>
                                <option value="3" <?= $statusFilter === 3 ? 'selected' : '' ?>>Failed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-equalizer-line"></i>
                            Filter
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <?php if (empty($active_numbers)): ?>
                    <div class="empty-state">
                        <h4>No number purchase history found</h4>
                        <p class="text-muted">Try adjusting your search or filter criteria</p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Activation ID</th>
                                <th>Service</th>
                                <th>Phone no</th>
                                <th>Code</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($active_numbers as $an): ?>
                                <tr>
                                    <td class="table-ref-id"><?= htmlspecialchars($an['number_id']) ?></td>

                                    <td><?= htmlspecialchars($an['service_name']) ?></td>
                                    <td>+<?= htmlspecialchars($an['number']) ?></td>
                                    <td></td>
                                    <td class="table-amount">
                                    ₦<?= $an['service_price']? number_format($an['service_price'], 2) : "" ?>
                                    </td>
                                    <td>
                                    <?= statusBadge($an['status']) ?>
                                    </td>
                                    <td>
                                    <?= date("M d, Y", strtotime($an['buy_time'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                    <?php
                    include 'include/pagination.php';
                    ?>

                </div>
            </div>
        </div>
    </div>

    <?php
        include("include/bottom-menu.php")
    ?>
</div>
<?php
    include 'include/footer-main.php';
?>