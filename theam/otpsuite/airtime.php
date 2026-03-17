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

.dark-mode .airtime-phone{
    color: #fff;
    background-color: transparent;
}

.dark-mode .quick-amount{
    color: #fff;
}

.dark-mode .summary-row span:last-child{
    color: #fff;
}

.dark-mode .payment-method{
    color: #fff;
}
</style>

<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md"></i>
                </a>
                <div class="pageTitle">Buy Airtime</div>
            </div>
        </div>
    </div>

    <div id="appCapsule">

        <input type="hidden" id="token" value="<?= $_SESSION['token']?>">

        <!-- WALLET -->
        <div class="small-wallet-card mb-5">
            <div class="detail">
                <strong>Wallet Balance</strong>
                <p>₦<?= $userwallet['balance']?></p>
            </div>
            <div class="right">
                <a href="recharge" class="btn btn-primary">Fund Wallet</a>
            </div>
        </div>

        <!-- NETWORK + PHONE -->
        <div class="mb-3">
            <div class="airtime-row">
                <div class="network-box" id="networkTrigger">
                    <img id="networkLogo" src="img/networks/mtn.jpg" class="net-logo">
                    <select id="networkSelect" class="network-select">
                        <option value="">Network</option>
                    </select>
                </div>


                <div class="divider"></div>

                <input type="tel" id="phone" class="airtime-phone"
                    placeholder="Recipient mobile number">
            </div>
        </div>

        <!-- QUICK AMOUNTS -->
        <div class="mb-2">
            <h5>Quick Topup</h5>
            <div class="d-flex flex-wrap gap-2 mb-2">
                <button class="btn quick-amount" data-amount="100">₦100</button>
                <button class="btn quick-amount" data-amount="200">₦200</button>
                <button class="btn quick-amount" data-amount="500">₦500</button>
                <button class="btn quick-amount" data-amount="1000">₦1000</button>
                <button class="btn quick-amount" data-amount="2000">₦2000</button>
            </div>
        </div>

        <!-- AMOUNT -->
        <div class="form-group basic mb-1">
            <div class="input-group">
                <span class="input-group-text">₦</span>
                <input type="number" class="form-control" id="amount" placeholder="Enter Amount">
            </div>
            <div class="input-info">Minimum ₦100</div>
        </div>

        <!-- PAY -->
        <button class="btn btn-primary w-100" id="payBtn" disabled>
            Pay
        </button>

    </div>

    <?php include 'include/bottom-menu.php'; ?>
</div>

<!-- ACTION SHEET -->
<div class="modal fade action-sheet" id="confirmSheet" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirm Payment</h5>
            </div>

            <div class="modal-body">
                <div class="action-sheet-content">

                    <h2 class="text-center mb-3">
                        ₦<span id="confirmAmount"></span>
                    </h2>

                    <div class="summary-card mb-3">
                        <div class="summary-row">
                            <span>Product</span>
                            <span>
                                <img id="confirmLogo" class="net-icon">
                                Airtime
                            </span>
                        </div>

                        <div class="summary-row">
                            <span>Amount</span>
                            <span>₦<span id="confirmAmount2"></span></span>
                        </div>

                        <div class="summary-row">
                            <span>Recipient Mobile</span>
                            <span id="confirmPhone"></span>
                        </div>
                    </div>

                    <div class="payment-method mb-3">
                        <div class="pm-left">
                            <div class="icon-wrapper">
                                <i class="ri-wallet-line"></i>
                            </div>
                            <div>
                                <small>Wallet</small><br>
                                <strong>₦<?= number_format($userwallet['balance'])?></strong>
                            </div>
                        </div>

                        <div class="default-check">
                            <ion-icon name="checkmark-outline"></ion-icon>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-lg w-100" id="confirmPay">
                        Confirm Payment
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
const notyf = new Notyf({position:{x:'right',y:'top'}});

/* STATIC NETWORK MAP */
const NETWORKS = {
  1:{name:"MTN",logo:"mtn.jpg",prefixes:["0803","0806","0702","0703","0706","0813","0816","0810","0814","0903","0906"]},
  2:{name:"AIRTEL",logo:"airtel.jpg",prefixes:["0802","0808","0708","0812","0902","0907","0901"]},
  3:{name:"GLO",logo:"glo.jpg",prefixes:["0805","0807","0705","0811","0815","0905"]},
  4:{name:"9MOBILE",logo:"9mobile.jpg",prefixes:["0809","0817","0818","0909","0908"]}
};

/* Load networks */
let options = `<option value="">Network</option>`;
for (const id in NETWORKS) {
  options += `<option value="${id}">${NETWORKS[id].name}</option>`;
}
$("#networkSelect").html(options);

/* Auto-detect network */
$("#phone").on("input", function () {
    const prefix = $(this).val().substring(0,4);
    let found = false;
    for (const id in NETWORKS) {
        if (NETWORKS[id].prefixes.includes(prefix)) {
            $("#networkSelect").val(id);
            $("#networkLogo").attr("src","img/networks/"+NETWORKS[id].logo);
            found = true;
            break;
        }
    }
    if(!found){
        $("#networkSelect").val("");
        $("#networkLogo").attr("src","img/networks/mtn.jpg");
    }
    validate();
});


/* Change network */
$("#networkSelect").on("change", function () {
    const id = this.value;
    if (NETWORKS[id]) {
        $("#networkLogo").attr("src","img/networks/"+NETWORKS[id].logo);
    } else {
        $("#networkLogo").attr("src","img/networks/default.png");
    }
    validate();
});

/* Amount buttons */
$(".quick-amount").click(function() {
    const amount = $(this).data("amount");

    $("#amount").val(amount);
    validate();

    $(".quick-amount").removeClass("active");
    $(this).addClass("active");

    // Update Pay button text
    $("#payBtn").text(`Pay ₦${amount}`);
});


$("#amount").on("input", validate);

function validate(){
    const ok =
        $("#networkSelect").val() &&
        $("#phone").val().length >= 10 &&
        $("#amount").val() >= 100;

    $("#payBtn")
        .prop("disabled", !ok)
        .text(ok ? `Pay ₦${$("#amount").val()}` : "Pay");
}

/* Open action sheet */
$("#payBtn").click(function(){
    $("#confirmAmount,#confirmAmount2").text($("#amount").val());
    $("#confirmPhone").text($("#phone").val());

    const id = $("#networkSelect").val();
    $("#confirmLogo").attr("src","img/networks/"+NETWORKS[id].logo);

    $("#confirmSheet").modal("show");
});

/* Confirm payment */
$("#confirmPay").click(function(){
    const $btn = $(this);

    // Disable button and show loading
    $btn.prop("disabled", true).text("Processing...");

    $.post("api/airtime/placeAirtimeOrder.php",{
        token: $("#token").val(),
        network_id: $("#networkSelect").val(),
        phone: $("#phone").val(),
        amount: $("#amount").val()
    },res=>{
        const r = JSON.parse(res);        
        if(r.status == 200){
            notyf.success("Airtime Purchase Successful");
            setTimeout(() => {
                location.href="airtime-success?amount="+$("#amount").val();
            }, 500);
        }else{
            $btn.prop("disabled", false).text("Confirm Payment");
            notyf.error(r.message);
        }
    });
});
</script>

<?php include 'include/footer-main.php'; ?>
