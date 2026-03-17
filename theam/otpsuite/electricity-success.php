<?php
$page_name = "Electricity Success";
include 'include/header-main.php';

$amount = isset($_GET['amount']) ? number_format((float)$_GET['amount'], 2) : "0.00";
$provider = isset($_GET['provider']) ? htmlspecialchars($_GET['provider']) : '';
$meter = isset($_GET['meter']) ? htmlspecialchars($_GET['meter']) : '';
$type = isset($_GET['type']) ? strtoupper(htmlspecialchars($_GET['type'])) : '';
$token = isset($_GET['token']) ? htmlspecialchars($_GET['token']) : '';
?>

<div id="app">
    <div id="appCapsule">
        <div class="d-flex align-items-center justify-content-center" style="min-height: 70vh;">
            <div class="text-center w-100">

                <ion-icon name="checkmark-circle-outline"
                          class="text-success"
                          style="font-size: 80px"></ion-icon>

                <h3 class="mt-2 fw-bold">Payment Successful</h3>

                <p>
                    Your <?= $provider ?> electricity payment
                    of ₦<?= $amount ?> was successful.
                </p>

                <p class="text-muted small">
                    Meter: <?= $meter ?> (<?= $type ?>)
                </p>

                <?php if ($token): ?>
                    <div class="alert alert-light mt-2">
                        <strong>Token:</strong><br>
                        <?= $token ?>
                    </div>
                <?php endif; ?>

                <div class="d-flex align-items-center gap-2">
                    <a href="electricity-history" class="btn btn-primary w-100">
                        View History
                    </a>

                    <a href="electricity" class="btn btn-outline-secondary w-100">
                        Done
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'include/footer-main.php'; ?>