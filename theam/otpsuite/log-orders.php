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
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

$perPage = 10;
$offset = ($page - 1) * $perPage;

$whereClauses = [];
$whereClauses[] = "user_id = " . intval($userdata['id']);

if ($search !== '') {
    $safe = mysqli_real_escape_string($conn, $search);
    $whereClauses[] = "(product_name LIKE '%$safe%')";
}

$whereSQL = implode(" AND ", $whereClauses);

$countSql = "SELECT COUNT(*) AS cnt FROM orders WHERE $whereSQL";
$resCount = mysqli_query($conn, $countSql);
$rowCount = mysqli_fetch_assoc($resCount);
$totalRows = (int)$rowCount['cnt'];
$totalPages = ceil($totalRows / $perPage);

$sql = "SELECT 
      p.name AS product_name, 
      o.created_at, 
      o.total_amount, 
      o.id AS order_id,
      o.user_id AS user_id,
      COUNT(oi.id) as num_items
  FROM orders o
  INNER JOIN order_items oi ON o.id = oi.order_id
  INNER JOIN products p ON oi.product_id = p.id
  WHERE $whereSQL
  GROUP BY o.id, p.id
  ORDER BY o.id DESC
  LIMIT $offset, $perPage";


$res = mysqli_query($conn, $sql);

$log_orders = [];
if ($res) {
  while ($r = mysqli_fetch_assoc($res)) {
    $log_orders[] = $r;
  }
}

// A helper for status badge
function statusBadge($status) {
  switch ($status) {
    case 1:
      return '<span class="badge badge-success">Success</span>';
    case 2:
      return '<span class="badge badge-warning">Pending</span>';
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
                <div class="pageTitle">Log Orders</div>
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
                            Log Orders
                        </h2>
                        <p class="text-muted mb-0 mt-1">
                            View and track all your log orders
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
                                placeholder="Search log orders..."
                                value="<?= htmlspecialchars($search) ?>"
                                />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-equalizer-line"></i>
                            Filter
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <?php if (empty($log_orders)): ?>
                    <div class="empty-state">
                        <h4>No log orders found</h4>
                        <p class="text-muted">Try adjusting your search or filter criteria</p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Amount</th>
                                <th>Ordered At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($log_orders as $lo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($lo['product_name']) ?></td>
                                    <td>₦<?= $lo['total_amount']? number_format($lo['total_amount'],0) : "" ?></td>
                                    <td>
                                    <?= date("M d, Y", strtotime($lo['created_at'])) ?>
                                    </td>
                                    <td>
                                    <a href="log-order-details?order_id=<?= $lo['order_id'] ?>" class="btn btn-primary btn-sm">
                                        View Details
                                    </a>
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