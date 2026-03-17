<?php
$page_name = "Electricity";
include 'include/header-main.php';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

<style>
.electricity-row{
    display:flex;
    align-items:center;
    padding-bottom:10px;
    border-bottom:1px solid #E8E8E8;
}
.subdrop-row{
    border: 1px solid #E5E7EB;
    padding: .75rem .75rem;
    border-radius: 10px;
}
.network-box{
    position:relative;
    cursor:pointer;
}
.network-box::after{
    content:"▾";
    font-size:10px;
    margin-right:10px;
    color:#6A7282;
}
.net-logo{
    height:30px;
    width:30px;
    border-radius:100%;
}
.network-select{
    position:absolute;
    inset:0;
    opacity:0;
    cursor:pointer;
}
.meter-number{
    flex:1;
    border:none;
    outline:none;
    padding:5px 12px 0;
}
.provider-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.divider{
    width:1px;
    height:28px;
    background:#E5E7EB;
}
.quick-amount{
    background: #FF6D001A;
    border: 1px solid #FF6D004D;
    cursor:pointer;
}
.quick-amount.active{
    background: #FF6D00;
    color:#fff;
    border: 1px solid #FF6D004D
}
.summary-card{
    border:1px solid #E5E7EB;
    border-radius:12px;
    padding:12px;
}
.summary-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:8px;
    font-size:14px;
}
.summary-row span:first-child{color:#6A7282}
.net-icon{height:18px;width:18px;border-radius:100%;}
.payment-method{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border:1px solid #FF6D00;
    border-radius:12px;
    color:#000;
    padding:12px;
}
.pm-left{
    display:flex;
    align-items:center;
    gap:10px;
}
.icon-wrapper{
    width:32px;
    height:32px;
    border-radius:100%;
    background:#FF6D0033;
    color:#FF6D00;
    display:flex;
    align-items:center;
    justify-content:center;
}
.default-check{
    width:20px;
    height:20px;
    border-radius:100%;
    background:#FF6D00;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
}
.input-group{
    border:1px solid #E5E7EB;
    padding:0 10px;
    border-radius:10px;
}
.dark-mode .meter-number,.dark-mode .quick-amount,.dark-mode .summary-row span:last-child,.dark-mode .payment-method{
    color:#fff;
}
</style>

<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md"></i>
                </a>
                <div class="pageTitle">Buy Electricity</div>
            </div>
        </div>
    </div>

    <div id="appCapsule">

        <input type="hidden" id="token" value="<?= $_SESSION['token']?>">

        <!-- WALLET -->
        <div class="small-wallet-card mb-4">
            <div class="detail">
                <strong>Wallet Balance</strong>
                <p>₦<?= number_format($userwallet['balance'])?></p>
            </div>
            <div class="right">
                <a href="recharge" class="btn btn-primary">Fund Wallet</a>
            </div>
        </div>

        <!-- PROVIDER + METER TYPE + NUMBER -->
        <div class="mb-3 electricity-row">
            <div class="network-box" id="providerTrigger">
                <img id="providerLogo" src="img/electricity-providers/ikeja-electric.png" class="net-logo">
                <span id="selectedProviderText">Select Provider</span>
            </div>

            <div class="divider"></div>

            <input type="text" id="meterNumber" class="meter-number" placeholder="Meter Number">
        </div>

         <!-- QUICK AMOUNTS -->
        <div class="mb-2">
            <h5>Quick Top Up</h5>
            <div class="d-flex flex-wrap gap-2 mb-2">
                <button class="btn quick-amount" data-amount="500">₦500</button>
                <button class="btn quick-amount" data-amount="1000">₦1000</button>
                <button class="btn quick-amount" data-amount="2000">₦2000</button>
            </div>
        </div>

        <!-- AMOUNT -->
        <div class="form-group basic mb-2">
            <div class="input-group">
                <span class="input-group-text">₦</span>
                <input type="number" class="form-control" id="amount" placeholder="Enter Amount">
            </div>
            <div class="input-info">Minimum ₦100</div>
        </div>

        <div class="mb-3 subdrop-row">
            <div class="network-box" id="meterTypeTrigger">
                <span id="selectedMeterType">Select Meter Type</span>
            </div>
        </div>

        <!-- PAY -->
        <button class="btn btn-primary w-100" id="payBtn" disabled>Pay</button>

    </div>

    <?php include 'include/bottom-menu.php'; ?>
</div>

<!-- PROVIDER SELECTION MODAL -->
<div class="modal fade action-sheet" id="providersSheet" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Select Provider</h5></div>
            <div class="modal-body">
                <ul class="action-button-list" id="providersList"></ul>
            </div>
        </div>
    </div>
</div>

<!-- METER TYPE MODAL -->
<div class="modal fade action-sheet" id="meterTypeSheet" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Select Meter Type</h5></div>
            <div class="modal-body">
                <ul class="action-button-list">
                    <li><a href="#" class="btn btn-list text-start select-meter-type" data-type="Prepaid">Prepaid</a></li>
                    <li><a href="#" class="btn btn-list text-start select-meter-type" data-type="Postpaid">Postpaid</a></li>
                    <li class="action-divider"></li>
                    <li><a href="#" class="btn btn-list text-danger" data-bs-dismiss="modal">Cancel</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- CONFIRMATION MODAL -->
<div class="modal fade action-sheet" id="confirmSheet" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Confirm Payment</h5></div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <h2 class="text-center mb-3">₦<span id="confirmAmount"></span></h2>
                    <div class="summary-card mb-3">
                        <div class="summary-row">
                            <span>Provider</span>
                            <span><img id="confirmLogo" class="net-icon"> <span id="confirmProvider"></span></span>
                        </div>
                        <div class="summary-row">
                            <span>Meter Type</span>
                            <span id="confirmMeterType"></span>
                        </div>
                        <div class="summary-row">
                            <span>Meter Number</span>
                            <span id="confirmMeterNumber"></span>
                        </div>
                        <div class="summary-row">
                            <span>Amount</span>
                            <span>₦<span id="confirmAmount2"></span></span>
                        </div>
                    </div>
                    <div class="payment-method mb-3">
                        <div class="pm-left">
                            <div class="icon-wrapper"><i class="ri-wallet-line"></i></div>
                            <div><small>Wallet</small><br><strong>₦<?= number_format($userwallet['balance'])?></strong></div>
                        </div>
                        <div class="default-check"><ion-icon name="checkmark-outline"></ion-icon></div>
                    </div>
                    <button class="btn btn-primary btn-lg w-100" id="confirmPay">Confirm Payment</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const notyf = new Notyf({position:{x:'right',y:'top'}});

/* PROVIDERS MAP */
const ELECTRICITY_PROVIDERS = {
  1:{name:"Ikeja Electricity",logo:"ikeja-electric.png"},
  2:{name:"Eko Electricity",logo:"eko-electricity.png"},
  3:{name:"Kano Electricity",logo:"kano-electricity.jpg"},
  4:{name:"Port Harcourt Electricity",logo:"port-harcourt-electricity.png"},
  5:{name:"Joss Electricity",logo:"jos-electricity.jpg"},
  6:{name:"Ibadan Electricity",logo:"ibadan-electricity.png"},
  7:{name:"Kaduna Electric",logo:"kaduna-electricity.jpg"},
  8:{name:"Abuja Electricity",logo:"abuja-electricity.png"},
  9:{name:"Benin Electric",logo:"benin-electricity.png"},
  10:{name:"Enugu Electric EEDC",logo:"enugu-electric.png"}
};

let selectedProvider = null;
let selectedMeterType = null;

/* Set default provider to first in the list */
$(document).ready(()=>{
    const firstId = Object.keys(ELECTRICITY_PROVIDERS)[0];
    const firstProvider = ELECTRICITY_PROVIDERS[firstId];
    selectedProvider = {id:firstId, name:firstProvider.name, logo:firstProvider.logo};
    $("#selectedProviderText").text(firstProvider.name);
    $("#providerLogo").attr("src","img/electricity-providers/"+firstProvider.logo);
});

/* Load providers modal */
$("#providerTrigger").click(()=>{
    let html="";
    for(const id in ELECTRICITY_PROVIDERS){
        const p = ELECTRICITY_PROVIDERS[id];
        html+=`<li><a href="#" class="btn btn-list text-start select-provider" data-id="${id}" data-name="${p.name}" data-logo="${p.logo}">
            <div class="provider-item">
            <img src="img/electricity-providers/${p.logo}" style="width:30px;height:30px;border-radius:50%;margin-right:10px;">${p.name}
            </div>
            </a></li>`;
    }
    $("#providersList").html(html);
    $("#providersSheet").modal("show");
});

/* Select provider */
$(document).on("click",".select-provider",function(){
    selectedProvider = {id:$(this).data("id"),name:$(this).data("name"),logo:$(this).data("logo")};
    $("#selectedProviderText").text(selectedProvider.name);
    $("#providerLogo").attr("src","img/electricity-providers/"+selectedProvider.logo);
    $("#providersSheet").modal("hide");
    validate();
});

/* Meter type modal */
$("#meterTypeTrigger").click(()=>$("#meterTypeSheet").modal("show"));
$(document).on("click",".select-meter-type",function(){
    selectedMeterType = $(this).data("type");
    $("#selectedMeterType").text(selectedMeterType);
    $("#meterTypeSheet").modal("hide");
    validate();
});

/* Quick amount pills */
$(".quick-amount").click(function(){
    const amount=$(this).data("amount");
    $("#amount").val(amount);
    $(".quick-amount").removeClass("active");
    $(this).addClass("active");
    validate();
});

/* Validate fields */
$("#amount,#meterNumber").on("input",validate);
function validate(){
    const ok = selectedProvider && selectedMeterType && $("#meterNumber").val().length>0 && $("#amount").val()>=100;
    $("#payBtn").prop("disabled",!ok).text(ok?`Pay ₦${$("#amount").val()}`:"Pay");
}

/* Open confirm modal */
$("#payBtn").click(()=>{
    $("#confirmAmount,#confirmAmount2").text($("#amount").val());
    $("#confirmProvider").text(selectedProvider.name);
    $("#confirmLogo").attr("src","img/electricity-providers/"+selectedProvider.logo);
    $("#confirmMeterType").text(selectedMeterType);
    $("#confirmMeterNumber").text($("#meterNumber").val());
    $("#confirmSheet").modal("show");
});

/* Confirm payment AJAX */
$("#confirmPay").click(function(){
    const $btn=$(this);
    $btn.prop("disabled",true).text("Processing...");
    $.post("api/electricity/placeElectricityOrder.php",{
        token:$("#token").val(),
        provider_id:selectedProvider.id,
        meter_type:selectedMeterType,
        meter_number:$("#meterNumber").val(),
        amount:$("#amount").val()
    },res=>{
        const r=JSON.parse(res);
        if(r.status==200){
            notyf.success("Electricity Payment Successful");
            setTimeout(function () {
                window.location.href =
                "electricity-success.php?" +
                "amount=" + amount +
                "&provider=" + encodeURIComponent(selectedProvider.name) +
                "&meter=" + encodeURIComponent($("#meterNumber").val()) +
                "&type=" + encodeURIComponent(selectedMeterType) +
                "&token=" + encodeURIComponent(r.token ?? "");
            } ,500);
        }else{
            $btn.prop("disabled",false).text("Confirm Payment");
            notyf.error(r.message);
        }
    });
});
</script>

<?php include 'include/footer-main.php'; ?>