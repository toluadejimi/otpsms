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


// $search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$order_id = $_GET['order_id'];

if ($page < 1) $page = 1;

$perPage = 10;
$offset = ($page - 1) * $perPage;

$whereClauses = [];
$whereClauses[] = "o.user_id = " . intval($userdata['id']);
$whereClauses[] = "oi.order_id = " . intval($order_id);

// if ($search !== '') {
//     $safe = mysqli_real_escape_string($conn, $search);
//     $whereClauses[] = "(product_name LIKE '%$safe%')";
// }

$whereSQL = implode(" AND ", $whereClauses);

$countSql = "SELECT COUNT(*) AS cnt FROM order_items oi INNER JOIN orders o ON oi.order_id = o.id WHERE $whereSQL";
$resCount = mysqli_query($conn, $countSql);
$rowCount = mysqli_fetch_assoc($resCount);
$totalRows = (int)$rowCount['cnt'];
$totalPages = ceil($totalRows / $perPage);

$order_id = $_GET['order_id'];

$sql = "SELECT 
        oi.id AS order_item_id, 
        p.name AS product_name,
        pd.details AS product_details
    FROM 
        order_items oi
    INNER JOIN
        orders o ON oi.order_id = o.id
    INNER JOIN 
        products p ON oi.product_id = p.id
    INNER JOIN 
        product_details pd ON oi.product_detail_id = pd.id
    WHERE $whereSQL
    LIMIT $offset, $perPage";

$res = mysqli_query($conn, $sql);

$log_order_details = [];
if ($res) {
  while ($r = mysqli_fetch_assoc($res)) {
    $log_order_details[] = $r;
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
                <div class="pageTitle">Log Order Details</div>
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
                            Log Orders Details
                        </h2>
                        <p class="text-muted mb-0 mt-1">
                            Name: <?php echo $product_ordered['name'] ?><br>
                            Desc: <?php echo $product_ordered['description'] ?>
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($log_order_details)): ?>
                    <div class="empty-state">
                        <h4>No log orders found</h4>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Details</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($log_order_details as $lod): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($lod['order_item_id']) ?></td>
                                        <td>
                                            <textarea col="5" row="5" class="form-control" disabled><?= $lod['product_details'] ?></textarea>
                                        </td>
                                        <td>
                                        <button onclick='copyTextFromButton(this)' data-log="<?= $lod['product_details'] ?>" class="btn btn-primary btn-sm">
                                            <i class="ri-file-copy-line"></i> Copy
                                        </button>
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
<script>
    const toast = new Notyf({
        position: {x:'right',y:'top'}
    });

    function copyTextFromButton(btn) {
        const text = btn.getAttribute("data-log");
    
        const textarea = document.createElement("textarea");
        textarea.value = text;
        textarea.style.position = "absolute";
        textarea.style.left = "-9999px";
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
    

        toast.success("Logs Copied: " + text);
    }
</script>
<?php
    include 'include/footer-main.php';
?>