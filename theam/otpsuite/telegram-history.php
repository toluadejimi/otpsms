<?php
$page_name = "Telegram Orders";
include 'include/header-main.php';

function limitString($string, $limit) {
    if (strlen($string) <= $limit) {
        return $string;
    } else {
        return rtrim(mb_substr($string, 0, $limit)) . '...';
    }
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

$whereClauses = [];
$whereClauses[] = "t.user_id = " . intval($userdata['id']);

if ($search !== '') {
    $safe = mysqli_real_escape_string($conn, $search);
    $whereClauses[] = "(t.username LIKE '%$safe%' 
                        OR t.local_order_id LIKE '%$safe%' 
                        OR t.provider_order_id LIKE '%$safe%')";
}

if (in_array($statusFilter, ["Initiated","Pending","Completed","Failed"])) {
    $map = [
        "Initiated" => "initiated",
        "Pending"   => "pending",
        "Completed" => "completed",
        "Failed"    => "failed"
    ];
    $whereClauses[] = "t.status = '" . $map[$statusFilter] . "'";
}

$whereSQL = implode(" AND ", $whereClauses);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$countSql = "SELECT COUNT(*) AS cnt 
             FROM tg_orders t
             WHERE $whereSQL";

$resCount = mysqli_query($conn, $countSql);
$rowCount = mysqli_fetch_assoc($resCount);
$totalRows = (int)$rowCount['cnt'];
$totalPages = ceil($totalRows / $perPage);

$sql = "SELECT t.*
        FROM tg_orders t
        WHERE $whereSQL
        ORDER BY t.id DESC
        LIMIT $offset, $perPage";

$res = mysqli_query($conn, $sql);

$tg_orders = [];
if ($res) {
    while ($r = mysqli_fetch_assoc($res)) {
        $tg_orders[] = $r;
    }
}

function statusBadge($status) {
    switch ($status) {
        case 'initiated':
            return '<span class="badge badge-secondary">Initiated</span>';
        case 'pending':
            return '<span class="badge badge-warning">Pending</span>';
        case 'completed':
            return '<span class="badge badge-success">Completed</span>';
        case 'failed':
            return '<span class="badge badge-danger">Failed</span>';
        default:
            return '<span class="badge badge-secondary">Unknown</span>';
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
                <div class="pageTitle">Telegram Purchase History</div>
            </div>
            <div class="right"></div>
        </div>
    </div>

    <div id="appCapsule">
        <div class="transaction-section section px-0">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="card-title">
                            <i class="ri-history-line me-2 text-primary fs-5"></i>
                            Telegram Purchase History
                        </h2>
                        <p class="text-muted mb-0 mt-1">
                            View and track all your Telegram Purchases
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
                                <!--<option value="Initiated" <?= $statusFilter === 'Initiated' ? 'selected' : '' ?>>Initiated</option>-->
                                <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Completed" <?= $statusFilter === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="Failed" <?= $statusFilter === 'Failed' ? 'selected' : '' ?>>Failed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-equalizer-line"></i>
                            Filter
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <?php if (empty($tg_orders)): ?>
                    <div class="empty-state">
                        <h4>No telegram orders found</h4>
                        <p class="text-muted">Try adjusting your search or filter criteria</p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Type / Username</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Reference</th>
                                    <th>Order Date</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($tg_orders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['local_order_id']) ?></td>
                                
                                    <td>
                                        <b>Type:</b> <?= ucfirst($order['order_type']) ?><br>
                                        <b>Username:</b> @<?= htmlspecialchars($order['username']) ?>
                                    </td>
                                
                                    <td>
                                        <?php if ($order['order_type'] === 'star'): ?>
                                            <?= number_format($order['quantity']) ?> Stars
                                        <?php else: ?>
                                            <?= intval($order['months']) ?> Month(s)
                                        <?php endif; ?>
                                    </td>
                                
                                    <td>₦<?= number_format($order['user_charged_amount'], 0, '.', ',') ?></td>
                                
                                    <td>
                                        <?= htmlspecialchars(limitString($order['provider_order_id'] ?? $order['local_order_id'], 15)) ?>
                                    </td>
                                
                                    <td><?= date("M d, Y - h:i A", strtotime($order['created_at'])) ?></td>
                                
                                    <td><?= statusBadge($order['status']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                    <?php include 'include/pagination.php'; ?>

                </div>
            </div>
        </div>
    </div>

    <?php include("include/bottom-menu.php") ?>
</div>
<?php include 'include/footer-main.php'; ?>
