<?php
$page_name = "Data Orders";
include 'include/header-main.php';

function limitString($string, $limit) {
    if (strlen($string) <= $limit) {
        return $string;
    }
    return rtrim(mb_substr($string, 0, $limit)) . '...';
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

$whereClauses = [];
$whereClauses[] = "d.user_id = " . intval($userdata['id']);

if ($search !== '') {
    $safe = mysqli_real_escape_string($conn, $search);
    $whereClauses[] = "(d.phone LIKE '%$safe%' OR n.name LIKE '%$safe%' OR d.api_reference LIKE '%$safe%')";
}

if (in_array($statusFilter, ["Pending", "Successful", "Failed"])) {
    $whereClauses[] = "d.status = '" . (
        $statusFilter === 'Pending' ? 0 :
        ($statusFilter === 'Successful' ? 1 : 2)
    ) . "'";
}

$whereSQL = implode(" AND ", $whereClauses);

/* Pagination */
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$countSql = "
    SELECT COUNT(*) AS cnt
    FROM data_orders d
    INNER JOIN networks n ON n.id = d.network_id
    WHERE $whereSQL
";

$resCount = mysqli_query($conn, $countSql);
$rowCount = mysqli_fetch_assoc($resCount);
$totalRows = (int)$rowCount['cnt'];
$totalPages = ceil($totalRows / $perPage);

$sql = "
    SELECT 
        d.*,
        n.name AS network_name,
        dp.plan_name
    FROM data_orders d
    INNER JOIN networks n ON n.id = d.network_id
    INNER JOIN data_plans dp ON dp.id = d.data_plan_id
    WHERE $whereSQL
    ORDER BY d.id DESC
    LIMIT $offset, $perPage
";

$res = mysqli_query($conn, $sql);

$data_orders = [];
while ($row = mysqli_fetch_assoc($res)) {
    $data_orders[] = $row;
}

function statusBadge($status) {
    switch ((int)$status) {
        case 0:
            return '<span class="badge badge-secondary">Pending</span>';
        case 1:
            return '<span class="badge badge-success">Successful</span>';
        case 2:
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
                <div class="pageTitle">Data Orders</div>
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
                            Data Orders
                        </h2>
                        <p class="text-muted mb-0 mt-1">
                            View and track all your data purchases
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
                                    placeholder="Search orders..."
                                    value="<?= htmlspecialchars($search) ?>"
                                />
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <select name="status" class="form-control custom-select">
                                <option value="">All Statuses</option>
                                <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Successful" <?= $statusFilter === 'Successful' ? 'selected' : '' ?>>Successful</option>
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
                    <?php if (empty($data_orders)): ?>
                        <div class="empty-state">
                            <h4>No data orders found</h4>
                            <p class="text-muted">Try adjusting your search or filter criteria</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Details</th>
                                        <th>Amount</th>
                                        <th>Reference</th>
                                        <th>Order Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data_orders as $do): ?>
                                        <tr>
                                            <td><?= $do['id'] ?></td>
                                            <td>
                                                <b>Network:</b> <?= htmlspecialchars($do['network_name']) ?><br>
                                                <b>Plan:</b> <?= htmlspecialchars($do['plan_name']) ?><br>
                                                <b>Phone:</b> <?= htmlspecialchars($do['phone']) ?>
                                            </td>
                                            <td>₦<?= number_format($do['amount'], 2) ?></td>
                                            <td><?= htmlspecialchars($do['api_reference']) ?></td>
                                            <td><?= date("M d, Y - h:i A", strtotime($do['created_at'])) ?></td>
                                            <td><?= statusBadge($do['status']) ?></td>
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

    <?php include("include/bottom-menu.php"); ?>
</div>

<?php include 'include/footer-main.php'; ?>
