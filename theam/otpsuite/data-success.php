<?php
$page_name = "Success";
include 'include/header-main.php';

$amount = $_GET['amount'] ?? '';
$plan   = $_GET['plan'] ?? '';
$phone  = $_GET['phone'] ?? '';
?>
<style>
.success-wrapper{
    text-align:center;
    padding:40px 20px;
}
.success-icon{
    width:80px;
    height:80px;
    border-radius:50%;
    background:#22C55E33;
    color:#22C55E;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:36px;
    margin:0 auto 20px;
}
.success-card{
    border:1px solid #E5E7EB;
    border-radius:12px;
    padding:14px;
    margin-top:20px;
    text-align:left;
}
.success-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:8px;
    font-size:14px;
}
.success-row span:first-child{
    color:#6A7282;
}
.success-actions{
    margin-top:30px;
    display:flex;
    gap:10px;
}
.success-actions .btn{
    flex:1;
}
</style>

<div id="app">
    <div id="appCapsule">

        <div class="success-wrapper">
            <div class="success-icon">
                <ion-icon name="checkmark-outline"></ion-icon>
            </div>

            <h2 class="mb-1">Transaction Successful</h2>

            <p class="text-muted">
                Data purchase<?= $amount ? " of ₦{$amount}" : "" ?> was successful.
            </p>

            <?php if($plan || $phone): ?>
            <div class="success-card">
                <?php if($plan): ?>
                <div class="success-row">
                    <span>Plan</span>
                    <span><?= htmlspecialchars($plan) ?></span>
                </div>
                <?php endif; ?>

                <?php if($amount): ?>
                <div class="success-row">
                    <span>Amount</span>
                    <span>₦<?= htmlspecialchars($amount) ?></span>
                </div>
                <?php endif; ?>

                <?php if($phone): ?>
                <div class="success-row">
                    <span>Recipient</span>
                    <span><?= htmlspecialchars($phone) ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="success-actions">
                <a href="data-orders" class="btn btn-primary">
                    View History
                </a>
                <button class="btn btn-outline-secondary" onclick="history.back()">
                    Back
                </button>
            </div>
        </div>

    </div>
</div>

<?php include 'include/footer-main.php'; ?>
