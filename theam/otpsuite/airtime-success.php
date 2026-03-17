<?php
$page_name = "Airtime";
include 'include/header-main.php';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

<style>
/* Airtime input */
.airtime-row {
    display: flex;
    align-items: center;
    padding-bottom: 10px;
    border-bottom: 1px solid #E8E8E8; 
}

.network-box {
    position: relative;
    cursor: pointer;
}

.network-box::after {
    content: "▾";
    font-size: 10px;
    margin-right: 10px;
    color: #6A7282;
}

.net-logo {
    height: 30px;
    width: 30px;
    border-radius: 100%;
}

.network-select {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}

.airtime-phone {
    flex: 1;
    border: none;
    outline: none;
    padding: 5px 12px 0;
}

.divider {
    width: 1px;
    height: 28px;
    background: #E5E7EB;
}

/* Summary */
.summary-card {
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 12px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 14px;
}

.summary-row span:first-child {
    color: #6A7282;
}

.net-icon {
    height: 18px;
    margin-right: 4px;
    border-radius: 100%;
}

.payment-method {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 12px;
    border: 1px solid #FF6D00;
    color: #000;
}

.payment-method .pm-left .icon-wrapper{
  width: 32px;
  height: 32px;
  border-radius: 100%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #FF6D00;
  background-color: #FF6D0033;
  font-size: 16px;
}

.payment-method .pm-left .icon-wrapper{
  width: 32px;
  height: 32px;
  border-radius: 100%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #FF6D00;
  background-color: #FF6D0033;
  font-size: 16px;
}

.payment-method .default-check{
    width: 20px;
    height: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #FFF;
    border-radius: 100%;
    background-color: #FF6D00;
}

.pm-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.pm-icon {
    width: 36px;
    height: 36px;
    background: #F3F4F6;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-amount{
    background: #9CA3AF33;
    border: 1px solid #FF6D001A
}

.quick-amount.active{
    background: #FF6D00;
    border: 1px solid #FF6D001A;
    color: #fff;
}
.input-group{
    border: 1px solid #E5E7EB;
    padding: 0 10px;
    border-radius: 10px;
}
.action-sheet-content h2{
    font-size: 24px;
}
</style>

<div id="app">
    <div id="appCapsule">
        <div class="d-flex align-items-center justify-content-center" style="min-height: 70vh;">
            <div class="text-center w-100">
                <ion-icon name="checkmark-circle-outline" class="text-success" style="font-size: 80px"></ion-icon>

                <h3 class="mt-2 fw-bold">Transaction Successful</h3>

                <p>
                    Airtime purchase of ₦<?= $_GET['amount']?> was successful
                </p>

                <div class="d-flex align-items-center gap-2">
                    <a href="airtime-history" class="btn btn-primary w-100">
                        View History
                    </a>

                    <button onclick="history.back()" class="btn btn-outline-secondary w-100">
                        Back
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'include/footer-main.php'; ?>
