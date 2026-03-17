<?php
$page_name = "Cable Success";
include 'include/header-main.php';

$amount = isset($_GET['amount']) ? number_format((float)$_GET['amount'], 2) : "0.00";
$provider = isset($_GET['provider']) ? htmlspecialchars($_GET['provider']) : '';
$plan = isset($_GET['plan']) ? htmlspecialchars($_GET['plan']) : '';
$smartcard = isset($_GET['smartcard']) ? htmlspecialchars($_GET['smartcard']) : '';
?>

<div id="app">
    <div id="appCapsule">
        <div class="d-flex align-items-center justify-content-center" style="min-height: 70vh;">
            <div class="text-center w-100">

                <ion-icon name="checkmark-circle-outline"
                          class="text-success"
                          style="font-size: 80px"></ion-icon>

                <h3 class="mt-2 fw-bold">Subscription Successful</h3>

                <p>
                    Your <?= $provider ?> subscription (<?= $plan ?>)
                    of ₦<?= $amount ?> was successful.
                </p>

                <p class="text-muted small">
                    Smartcard: <?= $smartcard ?>
                </p>

                <div class="d-flex align-items-center gap-2">
                    <a href="cable-history" class="btn btn-primary w-100">
                        View History
                    </a>

                    <a href="cable-tv" class="btn btn-outline-secondary w-100">
                        Done
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'include/footer-main.php'; ?>