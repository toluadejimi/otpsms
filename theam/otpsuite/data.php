<?php
$page_name = "Data";
include 'include/header-main.php';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

<style>
.airtime-row{
    display:flex;
    align-items:center;
    padding-bottom:10px;
    border-bottom:1px solid #E8E8E8;
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
.airtime-phone{
    flex:1;
    border:none;
    outline:none;
    padding:5px 12px 0;
}
.divider{
    width:1px;
    height:28px;
    background:#E5E7EB;
}

.plan-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.plan-item img {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    flex-shrink: 0;
}

.plan-picker{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:14px;
    border:1px solid #E5E7EB;
    border-radius:12px;
    cursor:pointer;
}
.plan-picker.disabled{
    opacity:.5;
    pointer-events:none;
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
.net-icon{
    height:18px;
    width:18px;
    border-radius:100%;
}

.payment-method{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border:1px solid #FF6D00;
    border-radius:12px;
    color: #000;
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
                <div class="pageTitle">Buy Data</div>
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

        <!-- NETWORK + PHONE -->
        <div class="mb-3">
            <div class="airtime-row">
                <div class="network-box">
                    <img id="networkLogo" src="img/networks/mtn.jpg" class="net-logo">
                    <select id="networkSelect" class="network-select"></select>
                </div>

                <div class="divider"></div>

                <input type="tel" id="phone" class="airtime-phone" placeholder="Recipient mobile number">
            </div>
        </div>

        <!-- PLAN PICKER -->
        <div class="mb-3">
            <div class="plan-picker disabled" id="openPlans">
                <span id="selectedPlanText">Enter phone number first</span>
                <i class="ri-arrow-down-s-line"></i>
            </div>
        </div>

        <!-- PAY -->
        <button class="btn btn-primary w-100" id="payBtn" disabled>Pay</button>

    </div>

    <?php include 'include/bottom-menu.php'; ?>
</div>

<!-- PLANS ACTION SHEET -->
<div class="modal fade action-sheet" id="plansSheet" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Data Plan</h5>
            </div>
            <div class="modal-body">
                <ul class="action-button-list" id="plansList"></ul>
            </div>
        </div>
    </div>
</div>

<!-- CONFIRM SHEET -->
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
                                Data
                            </span>
                        </div>
                        <div class="summary-row">
                            <span>Plan</span>
                            <span id="confirmPlan"></span>
                        </div>
                        <div class="summary-row">
                            <span>Recipient</span>
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
let selectedPlan = null;

const NETWORKS = {
  1:{name:"MTN",logo:"mtn.jpg",prefixes:["0803","0806","0703","0702","0706","0813","0816","0810","0814","0903","0906"]},
  2:{name:"AIRTEL",logo:"airtel.jpg",prefixes:["0802","0808","0708","0812","0902","0907","0901"]},
  3:{name:"GLO",logo:"glo.jpg",prefixes:["0805","0807","0705","0811","0815","0905"]},
  4:{name:"9MOBILE",logo:"9mobile.jpg",prefixes:["0809","0817","0818","0909","0908"]}
};

/* Populate networks */
let opts = `<option value="">Network</option>`;
for(const id in NETWORKS){
  opts += `<option value="${id}">${NETWORKS[id].name}</option>`;
}
$("#networkSelect").html(opts);

/* Auto detect network */
$("#phone").on("input", function () {
    const prefix = this.value.substring(0, 4);
    let found = false;

    for (const id in NETWORKS) {
        if (NETWORKS[id].prefixes.includes(prefix)) {

            const current = $("#networkSelect").val();

            $("#networkSelect").val(id);
            $("#networkLogo").attr("src", "img/networks/" + NETWORKS[id].logo);

            if ($("#phone").val().length >= 10) {
                fetchPlans(id);
            }

            found = true;
            break;
        }
    }

    if (!found) resetPlans();
});


/* Network change */
$("#networkSelect").on("change",function(){
    const id = this.value;
    if(!NETWORKS[id]) return resetPlans();

    $("#networkLogo").attr("src","img/networks/"+NETWORKS[id].logo);

    if($("#phone").val().length >= 10){
        console.log("Fetch the plans");
        fetchPlans(id);
    }
});

/* Fetch plans */
function fetchPlans(networkId){
    const net = NETWORKS[networkId];

    $("#plansList").html(`
        <li class="text-center py-3 text-muted">
            Loading plans...
        </li>
    `);

    $.getJSON("api/data/getDataPlans.php",{network_id:networkId},plans=>{

        // No plans available
        if(!plans || plans.length === 0){
            $("#plansList").html(`
                <li class="text-center py-4">
                    <strong>No Data Plans Available</strong><br>
                    <small class="text-muted">
                        Data plans are currently unavailable for this network.
                    </small>
                </li>
                <li class="action-divider"></li>
                <li>
                    <a href="#" class="btn btn-list text-danger" data-bs-dismiss="modal">
                        Cancel
                    </a>
                </li>
            `);
            return;
        }

        let html = "";

        plans.forEach(p=>{
            html += `
            <li>
                <a href="#" class="btn btn-list text-start select-plan"
                   data-id="${p.id}"
                   data-name="${p.network} ${p.name} ${p.type} (${p.validity})"
                   data-price="${p.price}">
                   
                   <div class="plan-item">
                       <img src="img/networks/${net.logo}">
                       <span>
                         ${p.network} ${p.name} ${p.type} (${p.validity}) - ₦${p.price}
                       </span>
                   </div>

                </a>
            </li>`;
        });

        html += `
            <li class="action-divider"></li>
            <li>
                <a href="#" class="btn btn-list text-danger" data-bs-dismiss="modal">
                    Cancel
                </a>
            </li>
        `;

        $("#plansList").html(html);
        $("#openPlans").removeClass("disabled");
        $("#selectedPlanText").text("Select Data Plan");
    });
}



/* Open plans */
$("#openPlans").click(()=>$("#plansSheet").modal("show"));

/* Select plan */
$(document).on("click",".select-plan",function(){
    selectedPlan = {
        id:$(this).data("id"),
        name:$(this).data("name"),
        price:$(this).data("price")
    };
    $("#selectedPlanText").text(selectedPlan.name);
    $("#payBtn").prop("disabled",false).text(`Pay ₦${selectedPlan.price}`);
    $("#plansSheet").modal("hide");
});

/* Reset */
function resetPlans(){
    selectedPlan = null;
    $("#openPlans").addClass("disabled");
    $("#selectedPlanText").text("Enter phone number first");
    $("#payBtn").prop("disabled",true).text("Pay");
}

/* Open confirm */
$("#payBtn").click(()=>{
    $("#confirmAmount").text(selectedPlan.price);
    $("#confirmPlan").text(selectedPlan.name);
    $("#confirmPhone").text($("#phone").val());
    $("#confirmLogo").attr("src","img/networks/"+NETWORKS[$("#networkSelect").val()].logo);
    $("#confirmSheet").modal("show");
});

/* Confirm payment */
$("#confirmPay").click(function (){
    const $btn = $(this);

    // Disable button and show loading
    $btn.prop("disabled", true).text("Processing...");
    
    $.post("api/data/placeDataOrder.php",{
        token:$("#token").val(),
        network_id:$("#networkSelect").val(),
        plan_id:selectedPlan.id,
        phone:$("#phone").val()
    },res=>{
        const r = JSON.parse(res);
        if(r.status==200){
            location.href = `data-success?amount=${selectedPlan.price}&plan=${encodeURIComponent(selectedPlan.name)}&phone=${$("#phone").val()}`;
        }else{
            $btn.prop("disabled", false).text("Confirm Payment");
            notyf.error(r.message);
        }
    });
});
</script>

<?php include 'include/footer-main.php'; ?>
