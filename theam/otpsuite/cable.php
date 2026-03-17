<?php
$page_name = "Cable TV";
include 'include/header-main.php';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

<style>
.electricity-row {
    display: flex;
    align-items: center;
    padding-bottom: 10px;
    border-bottom: 1px solid #E8E8E8;
}
.subdrop-row{
    border: 1px solid #E5E7EB;
    padding: .75rem .75rem;
    border-radius: 10px;
}
.network-box {
    position: relative;
    cursor: pointer;
}
.network-box::after {
    content: "▾";
    font-size: 10px;
    margin-left: 6px;
    color: #6A7282;
}
.net-logo {
    height: 30px;
    width: 30px;
    border-radius: 100%;
}
.provider-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.divider {
    width: 1px;
    height: 28px;
    background: #E5E7EB;
    margin: 0 10px;
}
.plan-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.plan-item img {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
}
.smartcard-input {
    flex: 1;
    border: none;
    outline: none;
    padding: 5px 12px 0;
}
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
    width: 18px;
    border-radius: 100%;
}
.payment-method {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #FF6D00;
    border-radius: 12px;
    padding: 12px;
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
                <div class="pageTitle">Cable TV</div>
            </div>
        </div>
    </div>

    <div id="appCapsule">

        <input type="hidden" id="token" value="<?= $_SESSION['token'] ?>">

        <!-- WALLET -->
        <div class="small-wallet-card mb-4">
            <div class="detail">
                <strong>Wallet Balance</strong>
                <p>₦<?= number_format($userwallet['balance']) ?></p>
            </div>
            <div class="right">
                <a href="recharge" class="btn btn-primary">Fund Wallet</a>
            </div>
        </div>

        <!-- PROVIDER + SMARTCARD -->
        <div class="mb-3 electricity-row">
            <div class="network-box d-flex align-items-center gap-2" id="providerTrigger">
                <img id="providerLogo" src="img/cable-providers/dstv.jpg" class="net-logo">
                <span id="selectedProviderText">DSTV</span>
            </div>

            <div class="divider"></div>

            <input type="text"
                   id="smartcard"
                   class="smartcard-input"
                   placeholder="Smartcard / IUC Number">
        </div>

        <!-- PLAN SELECTOR -->
        <div class="mb-3 subdrop-row">
            <div class="network-box" id="planTrigger">
                <span id="selectedPlanText">Select Plan</span>
            </div>
        </div>

        <!-- PAY BUTTON -->
        <button class="btn btn-primary w-100" id="payBtn" disabled>
            Pay
        </button>

    </div>

    <?php include 'include/bottom-menu.php'; ?>

</div>


<!-- ===================== PROVIDERS MODAL ===================== -->
<div class="modal fade action-sheet" id="providersSheet" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Provider</h5>
            </div>
            <div class="modal-body">
                <ul class="action-button-list" id="providersList"></ul>
            </div>
        </div>
    </div>
</div>


<!-- ===================== PLANS MODAL ===================== -->
<div class="modal fade action-sheet" id="plansSheet" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Plan</h5>
            </div>
            <div class="modal-body">
                <ul class="action-button-list" id="plansList"></ul>
            </div>
        </div>
    </div>
</div>


<!-- ===================== CONFIRM MODAL ===================== -->
<div class="modal fade action-sheet" id="confirmSheet" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Subscription</h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">

                    <h2 class="text-center mb-3">
                        ₦<span id="confirmAmount"></span>
                    </h2>

                    <div class="summary-card mb-3">

                        <div class="summary-row">
                            <span>Provider</span>
                            <span>
                                <img id="confirmLogo" class="net-icon">
                                <span id="confirmProvider"></span>
                            </span>
                        </div>

                        <div class="summary-row">
                            <span>Plan</span>
                            <span id="confirmPlan"></span>
                        </div>

                        <div class="summary-row">
                            <span>Smartcard</span>
                            <span id="confirmSmartcard"></span>
                        </div>

                    </div>
                    <div class="payment-method mb-3">
                        <div class="pm-left">
                            <div class="icon-wrapper"><i class="ri-wallet-line"></i></div>
                            <div><small>Wallet</small><br><strong>₦<?= number_format($userwallet['balance'])?></strong></div>
                        </div>
                        <div class="default-check"><ion-icon name="checkmark-outline"></ion-icon></div>
                    </div>

                    <button class="btn btn-primary btn-lg w-100" id="confirmPay">
                        Confirm Payment
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script>

const notyf = new Notyf({ position: { x: 'right', y: 'top' } });

/* Providers Map */
const CABLE_PROVIDERS = {
    1: { name: "DSTV", logo: "dstv.jpg" },
    2: { name: "GOTV", logo: "gotv.png" },
    3: { name: "STARTIMES", logo: "startimes.png" }
};

let selectedProvider = { id: 1, name: "DSTV", logo: "dstv.jpg" };
let selectedPlan = null;


/* Load DSTV plans on page load */
$(document).ready(function () {
    loadPlans(1);
});


/* Open Provider Modal */
$("#providerTrigger").click(function () {

    let html = "";

    for (const id in CABLE_PROVIDERS) {

        const p = CABLE_PROVIDERS[id];

        html += `
            <li>
                <a href="#" 
                   class="btn btn-list text-start select-provider"
                   data-id="${id}"
                   data-name="${p.name}"
                   data-logo="${p.logo}">
                    <div class="provider-item">
                    <img src="img/cable-providers/${p.logo}"
                         style="width:30px;height:30px;border-radius:50%;margin-right:10px;">
                    ${p.name}
                    </div>
                </a>
            </li>
        `;
    }

    $("#providersList").html(html);
    $("#providersSheet").modal("show");
});


/* Select Provider */
$(document).on("click", ".select-provider", function () {

    selectedProvider = {
        id: $(this).data("id"),
        name: $(this).data("name"),
        logo: $(this).data("logo")
    };

    selectedPlan = null;

    $("#selectedProviderText").text(selectedProvider.name);
    $("#providerLogo").attr("src", "img/cable-providers/" + selectedProvider.logo);
    $("#selectedPlanText").text("Select Plan");

    $("#providersSheet").modal("hide");

    loadPlans(selectedProvider.id);
    validate();
});


/* Load Plans via AJAX */
function loadPlans(providerId) {

    $.get("api/cable/getPlans.php", { provider_id: providerId }, function (res) {

        const plans = JSON.parse(res);
        let html = "";

        plans.forEach(function (p) {

            html += `
                <li>
                    <a href="#"
                       class="btn btn-list text-start select-plan"
                       data-id="${p.id}"
                       data-name="${p.name}"
                       data-price="${p.price}">
                       
                       <div class="plan-item">
                           <img src="img/cable-providers/${selectedProvider.logo}">
                           <span>
                               ${p.name} - ₦${p.price}
                           </span>
                       </div>

                    </a>
                </li>
            `;
        });

        $("#plansList").html(html);
    });
}


/* Open Plans Modal */
$("#planTrigger").click(function () {
    $("#plansSheet").modal("show");
});


/* Select Plan */
$(document).on("click", ".select-plan", function () {

    const price = parseFloat($(this).data("price"));

    selectedPlan = {
        id: $(this).data("id"),
        name: $(this).data("name"),
        price: price
    };

    /* Format price exactly like modal */
    const formattedPrice = price.toLocaleString("en-NG", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    $("#selectedPlanText").text(
        `${selectedPlan.name} - ₦${formattedPrice}`
    );

    $("#plansSheet").modal("hide");

    validate();
});


/* Validation */
$("#smartcard").on("input", validate);

function validate() {

    const valid =
        selectedProvider &&
        selectedPlan &&
        $("#smartcard").val().length > 0;

    $("#payBtn").prop("disabled", !valid);
}


/* Open Confirmation Modal */
$("#payBtn").click(function () {

    $("#confirmProvider").text(selectedProvider.name);
    $("#confirmLogo").attr("src", "img/cable-providers/" + selectedProvider.logo);
    $("#confirmPlan").text(selectedPlan.name);
    $("#confirmSmartcard").text($("#smartcard").val());
    $("#confirmAmount").text(selectedPlan.price);

    $("#confirmSheet").modal("show");
});


/* Confirm Payment */
$("#confirmPay").click(function () {

    const $btn = $(this);

    $btn.prop("disabled", true).text("Processing...");

    $.post("api/cable/placeCableOrder.php", {
        token: $("#token").val(),
        provider_id: selectedProvider.id,
        plan_id: selectedPlan.id,
        smartcard: $("#smartcard").val()
    }, function (res) {

        const r = JSON.parse(res);

        if (r.status == 200) {

            notyf.success("Cable Subscription Successful");

            setTimeout(function () {

                window.location.href =
                    "cable-success.php?" +
                    "amount=" + selectedPlan.price +
                    "&provider=" + encodeURIComponent(selectedProvider.name) +
                    "&plan=" + encodeURIComponent(selectedPlan.name) +
                    "&smartcard=" + encodeURIComponent($("#smartcard").val());
            }, 500);

        } else {

            $btn.prop("disabled", false).text("Confirm Payment");
            notyf.error(r.message);
        }
    });
});

</script>

<?php include 'include/footer-main.php'; ?>