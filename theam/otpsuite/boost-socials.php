<?php
$page_name = "Boost Socials";
include 'include/header-main.php';
?>
<link rel="stylesheet" href="<?= WEBSITE_URL ?>/theam/otpsuite/assets/css/nice-select2.css">

<style>
.server-card, .social-card {
  cursor: pointer;
  display: flex;
  flex-direction: column;
  align-items: center;
  border-radius: 10px;
}
.server-card.active, .social-card.active {
  outline: 1px solid #FF6D00;
  outline-offset: 3px;
  color: #FF6D00;
}
.social-card img{
  max-height:45px;
}
.qty-box input{
  width:90px;
  text-align:center;
}
#servicePreview {
  background: #F9FAFB;
  border-radius: 10px;
  border: 1px solid #E5E7EB;
}

#servicePreview .card-body {
  padding: 14px 16px;
}

/* Service name */
.svc-title {
  display: block;
  font-size: 15px;
  font-weight: 600;
  color: #111827;
  margin-bottom: 12px;
}

/* Grid layout */
.svc-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px 16px;
}

/* Each item */
.svc-item {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

/* Label */
.svc-label {
  font-size: 12px;
  color: #6A7282;
}

/* Value */
.svc-value {
  font-size: 14px;
  font-weight: 500;
  color: #000000;
}

/* Optional subtle divider feel */
.svc-item:not(:nth-child(-n+2)) {
  padding-top: 8px;
  border-top: 1px dashed #E5E7EB;
}

.price-preview u{
  text-decoration-style:dotted;
}

.total-estimate strong:last-child{
  font-size: 18px;
  font-weight: bold;
  color: #000;
}

body.dark-mode .total-estimate strong:last-child{
  color: #fff;
}
</style>

<div id="app">
  <div id="appCapsule">
    <div class="container-fluid p-0">
      <div class="appHeader">
          <div class="left">
              <a href="#" class="headerButton goBack">
                  <i class="ri-arrow-left-line icon md hydrated"></i>
              </a>
              <div class="pageTitle">Boost Socials</div>
          </div>
          <div class="right">
          </div>
      </div>
    </div>

    <input type="hidden" id="token" value="<?= $_SESSION['token']?>">

    <!-- WALLET -->
    <div class="small-wallet-card mb-3">
      <div class="detail">
        <strong>Wallet Balance</strong>
        <p>₦<?= $userwallet['balance']?></p>
      </div>
      <div class="right">
        <a href="recharge" class="btn btn-primary">Fund Wallet</a>
      </div>
    </div>

    <!-- SERVER -->
    <div class="boosting-server-card card mb-3">
      <div class="card-body">
        <h6>Boosting Server</h6>
        <div class="d-flex gap-3 flex-wrap justify-content-between" id="serverRadios"></div>
      </div>
    </div>

    <!-- SOCIAL MEDIA -->
    <div class="mb-3">
      <h6>Social Media</h6>
      <div class="row g-2" id="socialMediaGrid">
      </div>
    </div>

    <!-- CATEGORY -->
    <div class="form-group mb-3">
      <select id="categorySelect" disabled>
        <option>Select Category</option>
      </select>
    </div>

    <!-- SERVICE -->
    <div class="form-group mb-3">
      <select id="serviceSelect" disabled>
        <option>Select Service</option>
      </select>
    </div>

    <!-- EMPTY ORDER SUMMARY -->
    <div id="orderEmptyState" class="card mb-3">
      <div class="card-body d-flex flex-column justify-content-center align-items-center text-center" style="min-height:180px">
        <ion-icon name="book-outline" class="fs-1 md mb-2"></ion-icon>
        <p class="text-muted mb-0">
          Select a service to see details
        </p>
      </div>
    </div>


    <!-- SERVICE PREVIEW -->
    <div class="card mb-3 d-none" id="servicePreview">
    <div class="card-body price-preview">
      <strong id="svcName" class="svc-title"></strong>

      <div class="svc-grid">
        <div class="svc-item">
          <span class="svc-label">Price / 1000</span>
          <span class="svc-value">₦<span id="svcPrice">—</span></span>
        </div>

        <div class="svc-item">
          <span class="svc-label">Min</span>
          <span class="svc-value"><span id="svcMin">—</span></span>
        </div>

        <div class="svc-item">
          <span class="svc-label">Max</span>
          <span class="svc-value"><span id="svcMax">—</span></span>
        </div>

        <div class="svc-item">
          <span class="svc-label">Refill</span>
          <span class="svc-value"><span id="svcRefill">—</span></span>
        </div>
      </div>
    </div>
</div>


    <div id="orderDetails" class="d-none">
      <!-- LINK -->
      <div class="form-group mb-3">
        <label>Link</label>
        <input type="text" id="link" class="form-control" placeholder="https://example.com">
      </div>

      <!-- QUANTITY -->
      <div class="d-flex justify-content-center align-items-center gap-2 qty-box mb-3">
        <button class="btn btn-light" id="qtyMinus">−</button>
        <input type="number" id="quantity" class="form-control">
        <button class="btn btn-light" id="qtyPlus">+</button>
      </div>

      <!-- DRIPFEED -->
      <div class="mb-3 d-none" id="dripBox">
        <label><input type="checkbox" id="dripToggle"> Dripfeed</label>
        <div id="dripFields" class="mt-2 d-none">
          <input type="number" id="runs" class="form-control mb-2" placeholder="Runs">
          <input type="number" id="interval" class="form-control" placeholder="Interval (minutes)">
        </div>
      </div>

      <!-- CONFIRM -->
      <div class="form-check mb-3">
        <input class="form-check-input" type="radio" id="confirmOrder">
        <label class="form-check-label" for="confirmOrder">I confirm the account is public and details are correct</label>
      </div>

      <!-- FINAL SUMMARY -->
      <div class="border-top pt-2 mb-3 d-flex justify-content-between total-estimate">
        <strong>Total</strong>
        <strong>₦<span id="totalPrice">0</span></strong>
      </div>
    </div>

    <button class="btn btn-primary w-100" id="placeOrder">Place Order</button>

  </div>
</div>

<script src="<?= WEBSITE_URL ?>/theam/otpsuite/assets/js/nice-select2.js"></script>
<script>
$(function(){

const toast = new Notyf({position:{x:'right',y:'top'}});
let selectedService=null;
let minQty=0;

const catNS = NiceSelect.bind(document.getElementById("categorySelect"), {placeholder: 'Select Category'});
const svcNS = NiceSelect.bind(document.getElementById("serviceSelect"), {placeholder: 'Select Service'});


function resetOrderState() {
  selectedService = null;

  $("#orderDetails").addClass("d-none");
  $("#orderEmptyState").removeClass("d-none");

  $("#placeOrder").prop("disabled", true);
  $("#servicePreview").addClass("d-none");
  $("#totalPrice").text("0");
}


/* SERVERS */
$.getJSON("api/boosting/getProviders.php", function (list) {
  list.forEach((s, i) => {
    $("#serverRadios").append(`
      <div class="form-check form-check-inline">
        <input 
          class="form-check-input server-radio" 
          type="radio" 
          name="boostingServer"
          id="server_${s.id}" 
          data-id="${s.id}"
          ${i === 0 ? "checked" : ""}
        >
        <label class="form-check-label" for="server_${s.id}">
          Server ${i + 1}
        </label>
      </div>
    `);
  });

  // Load first server by default
  loadSocialMedia(list[0].id);
});


$("#serverRadios").on("change", "input[name='boostingServer']", function () {
  const serverId = $(this).data("id");

  if (!serverId) return;

  resetAll();

  $("#socialMediaGrid").empty();

  loadSocialMedia(serverId);
});


/* SOCIAL MEDIA */
function loadSocialMedia(serverId){
  $.getJSON("api/boosting/getSocialMedia.php",function(list){
    let fav='',rest='';
    list.forEach(s=>{
      let card=`
        <div class="col-3">
          <div class="social-card text-center" data-id="${s.id}">
            ${s.name === "others"? 
              `<div class="icon-wrapper">
                <i class="ri-more-fill"></i>
              </div>` 
              : 
              `<img src="img/social/${s.image}">`
            }
            <small>${s.name}</small>
          </div>
        </div>`;
      s.is_starred==1?fav+=card:rest+=card;
    });
    $("#socialMediaGrid").html(fav);
    $("#socialMediaGrid").append(`
      <div class="col-3 d-none" id="showMoreSocials">
        <div class="social-card more-social text-center">
          <div class="icon-wrapper">
            <i class="ri-more-fill"></i>
          </div>
          <small>More</small>
        </div>
      </div>
    `);
    $("#showMoreSocials").toggleClass("d-none",!rest).off().on("click",()=>{
      $("#socialMediaGrid").append(rest);
      $("#showMoreSocials").addClass("d-none");
    });
  });
}

$("#socialMediaGrid").on("click", ".social-card", function () {
  $(".social-card").removeClass("active");
  $(this).addClass("active");

  const serverId = $("input[name='boostingServer']:checked").data("id");
  const socialId = $(this).data("id");

  if (!serverId) {
    toast.error("Please select a server");
    return;
  }

  resetAll();
  loadCategories(serverId, socialId);
});


/* CATEGORIES */
function loadCategories(serverId,socialId){
  $("#categorySelect").prop("disabled",true);
  $.getJSON("api/boosting/getCategories.php",{provider_id:serverId,social_media_id:socialId},function(cats){
    let h='<option value="">Select Category</option>';
    cats.forEach(c=>h+=`<option value="${c.id}">${c.name}</option>`);
    $("#categorySelect").html(h).prop("disabled",false);
    catNS.update();
  });
}

/* SERVICES */
$("#categorySelect").on("change",function(){
  $("#serviceSelect").prop("disabled",true);
  $.getJSON("api/boosting/getServices.php",{category_id:this.value},function(svcs){
    let h='<option value="">Select Service</option>';
    svcs.forEach(s=>{
      h+=`<option value="${s.id}" data-svc='${JSON.stringify(s)}'>${s.name}</option>`;
    });
    $("#serviceSelect").html(h).prop("disabled",false);
    svcNS.update();
  });
});

$("#serviceSelect").on("change", function () {
  selectedService = $(this).find(":selected").data("svc");

  if (!selectedService) {
    resetOrderState();
    return;
  }

  // SHOW REAL ORDER UI
  $("#orderEmptyState").addClass("d-none");
  $("#orderDetails").removeClass("d-none");
  $("#placeOrder").prop("disabled", false);

  $("#servicePreview").removeClass("d-none");
  $("#svcName").text(selectedService.name);
  $("#svcPrice").text(selectedService.price);
  $("#svcMin").text(selectedService.min);
  $("#svcMax").text(selectedService.max);
  $("#svcRefill").text(selectedService.refill == 1 ? 'Yes' : 'No');

  minQty = selectedService.min;
  $("#quantity").val(minQty);

  selectedService.dripfeed == 1
    ? $("#dripBox").removeClass("d-none")
    : $("#dripBox").addClass("d-none");

  calcTotal();
});


/* LINK VALIDATION */
$("#link").on("input",function(){
  $(this).toggleClass("is-valid",/^https?:\/\//i.test(this.value));
});

/* QUANTITY */
$("#qtyPlus").click(()=>$("#quantity").val(+$("#quantity").val()+1).trigger("input"));
$("#qtyMinus").click(()=>{
  let v=+$("#quantity").val();
  if(v>minQty) $("#quantity").val(v-1).trigger("input");
});

/* TOTAL */
$("#quantity,#runs").on("input",calcTotal);
$("#dripToggle").on("change",()=>$("#dripFields").toggleClass("d-none",!$("#dripToggle").is(":checked")));

function calcTotal(){
  if(!selectedService) return;
  let qty=+$("#quantity").val();
  let runs=$("#dripToggle").is(":checked")?+($("#runs").val()||1):1;
  $("#totalPrice").text(((qty*runs)/1000*selectedService.price).toFixed(2));
}

/* ORDER */
$("#placeOrder").click(function(){
  if(!selectedService) return toast.error("Select service");
  if(!$("#confirmOrder").is(":checked")) return toast.error("Please confirm order");
  if(!$("#link").val()) return toast.error("Enter link");

  let btn=$(this).prop("disabled",true).html("Placing...");
  $.post("api/boosting/placeOrder.php",{
    token:$("#token").val(),
    service_id:selectedService.id,
    quantity:+$("#quantity").val(),
    total_quantity:+$("#quantity").val(),
    link:$("#link").val(),
    is_drip_feed:$("#dripToggle").is(":checked")?1:0,
    runs:$("#runs").val(),
    interval:$("#interval").val()
  },res=>{
    let r=JSON.parse(res);
    r.status==200?(toast.success(r.message),location.href="boosting-orders"):
    (toast.error(r.message),btn.prop("disabled",false).text("Place Order"));
  });
});

function resetAll(){
  $("#categorySelect,#serviceSelect").prop("disabled",true).val("");
  $("#servicePreview").addClass("d-none");
}

});
</script>

<?php include 'include/footer-main.php'; ?>
