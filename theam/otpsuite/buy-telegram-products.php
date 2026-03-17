<?php
$page_name = "Dashboard";
include 'include/header-main.php';
?>
<link rel='stylesheet' type='text/css' href='<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/css/nice-select2.css'>
<style>
/* --- Loader & Spinner --- */
.number-count-loader { width: 20px; padding:8px; aspect-ratio:1; border-radius:50%; background:#FF6D00; --_m: conic-gradient(#0000 10%,#000), linear-gradient(#000 0 0) content-box; -webkit-mask: var(--_m); mask: var(--_m); -webkit-mask-composite: source-out; mask-composite: subtract; animation: l3 1s infinite linear;}
@keyframes l3 {to{transform: rotate(1turn);}}
.form-group p { font-size:12px; }
.loadernum, .services-loader { /* keep existing loader styles */ }
/* --- Recipient Card --- */
#recipientInfo { border:1px solid #d3d3d3; border-radius:10px; padding:10px; margin-bottom:15px; display:flex; align-items:center; gap:10px; background:#f8f9fa; }
/* --- Price --- */
.price-display { font-weight:bold; font-size:1rem; }
#premiumLoader {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 8px;
}
#premiumLoader .number-count-loader {
    width: 24px;
    height: 24px;
}
</style>

<div id="app">
  <div class="container-fluid p-0">
    <div class="appHeader">
      <div class="left">
        <a href="#" class="headerButton goBack">
          <i class="ri-arrow-left-line icon md hydrated"></i>
        </a>
        <div class="pageTitle">Get Your Telegram Bluetick</div>
      </div>
      <div class="right"></div>
    </div>
  </div>

  <div id="appCapsule">
    <!-- Wallet Card -->
    <div class="small-wallet-card mb-2">
      <div class="detail">
        <strong>Wallet Balance</strong>
        <p>₦<span id="current_balance"><?php echo $userwallet['balance'];?></span></p>
      </div>
      <div class="right">
        <a href="recharge" class="w-100 btn btn-primary ls-1">Fund Wallet</a>
      </div>
    </div>

    <input type="hidden" id="token" value="<?php echo $_SESSION['token']; ?>">
    
    <div class="form-group boxed">
        <div class="input-wrapper">
            <label class="label" for="tg_username">Telegram Username</label>
            <input type="text" class="form-control" id="tg_username" placeholder="Enter Telegram username">
            <i class="clear-input">
                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
            </i>
        </div>
        <p class="my-1">Enter username and select package to validate telegram username</p>
    </div>

    <!-- Recipient Info Card -->
    <div id="recipientInfo" class="d-none">
      <img id="recipientPhoto" src="" alt="Recipient" class="rounded-circle" width="50" height="50">
      <div>
        <div id="recipientName" class="fw-bold"></div>
        <div id="recipientMyself" class="text-muted" style="font-size:12px;"></div>
      </div>
    </div>

    <!-- Tabs -->
    <!--<ul class="nav nav-tabs lined mb-3" id="giftTabs" role="tablist">-->
    <!--  <li class="nav-item">-->
    <!--    <a class="nav-link active" id="premium-tab" data-bs-toggle="tab" href="#premium" role="tab">Premium</a>-->
    <!--  </li>-->
    <!--   <li class="nav-item">-->
    <!--    <a class="nav-link" id="stars-tab" data-bs-toggle="tab" href="#stars" role="tab">Stars</a>-->
    <!--  </li>-->
    <!--</ul>-->

    <!--<div class="tab-content">-->
      <!-- Premium Tab -->
    <!--  <div class="tab-pane active" id="premium" role="tabpanel">-->
       <div class="form-group boxed mb-3">
            <div class="input-wrapper" id="premiumWrapper">
                <label class="label" for="premium_months">Select Telegram Package</label>
                <div id="premiumContainer">
                    <select class="form-control custom-select" id="premium_months">
                        <option value="">-- Select package --</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-2">Price: ₦<span class="price-display" id="premiumPrice">0</span></div>
        <button class="btn btn-primary w-100" id="buyPremium" disabled>Place Order</button>
      <!--</div>-->
      
      <!-- Stars Tab -->
      <!--<div class="tab-pane fade show" id="stars" role="tabpanel">-->
      <!--  <div class="form-group boxed mb-3">-->
      <!--      <div class="input-wrapper">-->
      <!--          <label class="label" for="star_quantity">Star Quantity</label>-->
      <!--          <input type="text" class="form-control" id="star_quantity" placeholder="50 - 1,000,000" min="50" max="1000000">-->
      <!--          <i class="clear-input">-->
      <!--              <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>-->
      <!--          </i>-->
      <!--      </div>-->
      <!--  </div>-->
        
      <!--  <div class="mb-2">Price: ₦<span class="price-display" id="starPrice">0</span></div>-->
      <!--  <button class="btn btn-primary w-100" id="buyStars" disabled>Place Order</button>-->
      <!--</div>-->
    </div>
  </div>

  <?php include("include/bottom-menu.php"); ?>
</div>

<script>
const notyf = new Notyf({ position: {x:'right',y:'top'} });

const usernameInput = document.getElementById('tg_username');
const recipientCard = document.getElementById('recipientInfo');
const recipientName = document.getElementById('recipientName');
const recipientPhoto = document.getElementById('recipientPhoto');
const recipientMyself = document.getElementById('recipientMyself');

const buyPremiumBtn = document.getElementById('buyPremium');
const premiumContainer = document.getElementById('premiumContainer');
const premiumPriceEl = document.getElementById('premiumPrice');
const token = document.getElementById('token').value;

let recipientHash = '';
let premiumPackages = {};
let selectedMonths = null;

/* -------------------------
   SHOW SPINNER WHILE LOADING PACKAGES
-------------------------- */

premiumContainer.innerHTML =
  '<div id="premiumLoader"><div class="number-count-loader"></div></div>';

fetch('api/telegram/getPackages.php')
.then(res => res.json())
.then(data => {

  premiumContainer.innerHTML = '';

  const select = document.createElement('select');
  select.className = 'form-control custom-select';
  select.id = 'premium_months';

  const defaultOpt = document.createElement('option');
  defaultOpt.value = '';
  defaultOpt.textContent = '-- Select package --';
  select.appendChild(defaultOpt);

  premiumContainer.appendChild(select);

  if(data.status === 200 && data.packages){

    data.packages.forEach(pkg => {
      const opt = document.createElement('option');
      opt.value = pkg.months;
      opt.textContent = pkg.months + ' month(s) - ₦' + pkg.price_ngn;
      select.appendChild(opt);

      premiumPackages[pkg.months] = pkg.price_ngn;
    });

  } else {
    notyf.error('Failed to load packages');
  }

  /* Attach change listener AFTER rendering */
  select.addEventListener('change', () => {
      selectedMonths = select.value;
      if(selectedMonths && premiumPackages[selectedMonths]){
          premiumPriceEl.textContent = premiumPackages[selectedMonths];
          validateRecipient();
      } else {
          premiumPriceEl.textContent = 0;
          buyPremiumBtn.disabled = true;
      }
  });

});


/* -------------------------
   VALIDATE RECIPIENT
-------------------------- */

async function validateRecipient(){

  const username = usernameInput.value.trim();
  if(!username || !selectedMonths){
      recipientCard.classList.add('d-none');
      buyPremiumBtn.disabled = true;
      return;
  }

  buyPremiumBtn.disabled = true;
  buyPremiumBtn.textContent = 'Validating...';

  try{

      const res = await fetch(
        `api/telegram/validateRecipient.php?token=${token}&type=premium&username=${username}&months=${selectedMonths}`
      );

      const data = await res.json();

      if(data.status === 200){

          recipientCard.classList.remove('d-none');
          recipientName.textContent = data.name;

          // IMPORTANT PHOTO EXTRACTION (you said I remove this 😅)
          const match = data.photo?.match(/src=["']([^"']+)["']/);
          recipientPhoto.src = match ? match[1] : '';

          recipientMyself.textContent = data.myself ? '(This is you)' : '';
          recipientHash = data.recipient;

          buyPremiumBtn.disabled = false;
          buyPremiumBtn.textContent = 'Place Order';

      } else {

          recipientCard.classList.add('d-none');
          buyPremiumBtn.disabled = true;
          buyPremiumBtn.textContent = 'Place Order';
          notyf.error(data.message || 'Recipient validation failed');

      }

  } catch(e){
      console.error(e);
      recipientCard.classList.add('d-none');
      buyPremiumBtn.disabled = true;
      buyPremiumBtn.textContent = 'Place Order';
      notyf.error('Validation error');
  }
}

usernameInput.addEventListener('blur', validateRecipient);


/* -------------------------
   PLACE ORDER (SECURE)
-------------------------- */

async function placeOrder(){

  if(!recipientHash || !selectedMonths) return;

  buyPremiumBtn.disabled = true;
  buyPremiumBtn.textContent = 'Processing...';

  try{

      const res = await fetch('api/telegram/placeOrder.php',{
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body:JSON.stringify({
              token: token,
              type: 'premium',
              username: usernameInput.value.trim(),
              recipient_hash: recipientHash,
              months: selectedMonths
          })
      });

      const data = await res.json();

      if(data.status === 200){

          notyf.success(`Order placed! Charged ₦${data.charged_ngn}`);

          // update wallet balance immediately
          document.getElementById('current_balance').textContent =
              parseFloat(data.new_balance).toFixed(2);

          setTimeout(() => {
              window.location = "telegram-history";
          }, 1000);

      } else {
          notyf.error(data.message || 'Order failed');
      }

  } catch(e){
      console.error(e);
      notyf.error('Order error');
  }

  buyPremiumBtn.disabled = false;
  buyPremiumBtn.textContent = 'Place Order';
}

buyPremiumBtn.addEventListener('click', placeOrder);

</script>

<?php include 'include/footer-main.php'; ?>