<?php
$page_name = "Dashboard";
include 'include/header-main.php';

function limitString($string, $limit) {
    if (strlen($string) <= $limit) {
        return $string;
    } else {
        // Use mb_substr for multibyte safe substring
        return rtrim(mb_substr($string, 0, $limit)) . '...';
    }
}


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

if ($statusFilter !== '') {
  $safeStatus = mysqli_real_escape_string($conn, $statusFilter);
  $whereClauses[] = "status = '$safeStatus'";
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

$perPage = 10;
$offset = ($page - 1) * $perPage;

$whereClauses = [];
$whereCountClauses = [];

$whereClauses[] = "user_id = " . intval($userdata['id']);

if ($search !== '') {
  $safe = mysqli_real_escape_string($conn, $search);
  $whereClauses[] = "(name LIKE '%$safe%')";
}

if (in_array($statusFilter, ["Pending","Processing","Completed","Partial","Canceled","Refunded"])) {
    $whereClauses[] = "status = '$statusFilter'";
}

$whereSQL = implode(" AND ", $whereClauses);

$countSql = "SELECT COUNT(*) AS cnt FROM boosting_orders WHERE $whereSQL";

$resCount = mysqli_query($conn, $countSql);
$rowCount = mysqli_fetch_assoc($resCount);
$totalRows = (int)$rowCount['cnt'];
$totalPages = ceil($totalRows / $perPage);

$sql = "SELECT *, price as order_price
  FROM boosting_orders
  WHERE $whereSQL
  ORDER BY added_on DESC
  LIMIT $offset, $perPage";


$res = mysqli_query($conn, $sql);

$boosting_orders = [];
if ($res) {
  while ($r = mysqli_fetch_assoc($res)) {
    $boosting_orders[] = $r;
  }
}

// A helper for status badge
function statusBadge($status) {
  switch (strtolower($status)) {
    case 'pending':
      return '<span class="badge badge-secondary">Pending</span>';
    case 'processing':
      return '<span class="badge badge-secondary">Processing</span>';
    case 'completed':
      return '<span class="badge badge-success">Completed</span>';
    case 'partial':
      return '<span class="badge badge-warning">Partial</span>';
    case 'canceled':
      return '<span class="badge badge-danger">Canceled</span>';
    case 'refunded':
      return '<span class="badge badge-warning">Refunded</span>';
    default:
      return '<span class="badge badge-secondary"Unknown</span>';
  }
}
?>
<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md hydrated"></i>
                </a>
                <div class="pageTitle">Boosing Orders</div>
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
                            Boosting Orders
                        </h2>
                        <p class="text-muted mb-0 mt-1">
                            View and track all your boosting orders
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
                                placeholder="Search Orders..."
                                value="<?= htmlspecialchars($search) ?>"
                                />
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <select name="status" class="form-control custom-select">
                                <option value="">All Statuses</option>
                                <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Processing" <?= $statusFilter === 'Processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="Completed" <?= $statusFilter === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="Partial" <?= $statusFilter === 'Partial' ? 'selected' : '' ?>>Partial</option>
                                <option value="Canceled" <?= $statusFilter === 'Canceled' ? 'selected' : '' ?>>Canceled</option>
                                <option value="Refunded" <?= $statusFilter === 'Refunded' ? 'selected' : '' ?>>Refunded</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-equalizer-line"></i>
                            Filter
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <?php if (empty($boosting_orders)): ?>
                    <div class="empty-state">
                        <h4>No boosting orders found</h4>
                        <p class="text-muted">Try adjusting your search or filter criteria</p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Details</th>
                                <th>Price</th>
                                <th>Start Counter</th>
                                <th>Remains</th>
                                <th>Order At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($boosting_orders as $bo): ?>
                                <tr>
                                    <td class="table-ref-id">
                                    <?= htmlspecialchars($bo['id']) ?>
                                    </td>

                                    <td>
                                    <p><?= limitString($bo['service_name'], 30) ?></p>
                                    <b>Link: <?= limitString($bo['link'], 30) ?></b><br>
                                    <b>Quantity: <?= limitString($bo['quantity'], 30) ?></b>
                                    </td>
                                    <td class="table-amount">
                                    ₦<?= $bo['order_price']? number_format($bo['order_price'], 2) : "" ?>
                                    </td>
                                    <td>
                                    <?= $bo['start_counter'] ?? "0" ?>
                                    </td>
                                    <td>
                                    <?= $bo['remains'] ?? "0" ?>
                                    </td>
                                    <td>
                                    <?= date("M d, Y", strtotime($bo['added_on'])) ?>
                                    </td>
                                    <td>
                                    <?= statusBadge($bo['status']) ?>
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