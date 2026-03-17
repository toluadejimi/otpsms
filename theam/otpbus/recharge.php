<?php 
$page_name = "Recharge";
include 'include/header-main.php'; 
$enabled_gateway_names = [];
if (isset($conn)) {
    $gwq = $conn->query("SELECT name FROM payment_gateways WHERE status = 1");
    if ($gwq) {
        while ($r = $gwq->fetch_assoc()) {
            $enabled_gateway_names[] = strtolower(trim((string)($r['name'] ?? '')));
        }
    }
}
?>
<script>
        $(document).ready(function() {
            $('#slide-dashboard').removeClass("active");
            $("#slide-recharge").addClass("active");
            // Handle return from SprintPay
            var params = new URLSearchParams(window.location.search);
            if (params.get('payment') === 'success' && params.get('ref')) {
                if (typeof notyf !== 'undefined') notyf.success('Payment successful! Your wallet will be credited shortly.');
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>    
<script>
  // Enabled gateways from admin (payment_gateways.status=1)
  var enabledGateways = <?php echo json_encode($enabled_gateway_names); ?>;
</script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-3.2.6.min.css" rel="stylesheet">
<style>
.typewriter {
  --blue: #5C86FF;
  --blue-dark: #275EFE;
  --key: #fff;
  --paper: #EEF0FD;
  --text: #D3D4EC;
  --tool: #FBC56C;
  --duration: 3s;
  position: relative;
  -webkit-animation: bounce05 var(--duration) linear infinite;
  animation: bounce05 var(--duration) linear infinite;
}

.typewriter .slide {
  width: 92px;
  height: 20px;
  border-radius: 3px;
  margin-left: 14px;
  transform: translateX(14px);
  background: linear-gradient(var(--blue), var(--blue-dark));
  -webkit-animation: slide05 var(--duration) ease infinite;
  animation: slide05 var(--duration) ease infinite;
}

.typewriter .slide:before, .typewriter .slide:after,
.typewriter .slide i:before {
  content: "";
  position: absolute;
  background: var(--tool);
}

.typewriter .slide:before {
  width: 2px;
  height: 8px;
  top: 6px;
  left: 100%;
}

.typewriter .slide:after {
  left: 94px;
  top: 3px;
  height: 14px;
  width: 6px;
  border-radius: 3px;
}

.typewriter .slide i {
  display: block;
  position: absolute;
  right: 100%;
  width: 6px;
  height: 4px;
  top: 4px;
  background: var(--tool);
}

.typewriter .slide i:before {
  right: 100%;
  top: -2px;
  width: 4px;
  border-radius: 2px;
  height: 14px;
}

.typewriter .paper {
  position: absolute;
  left: 24px;
  top: -26px;
  width: 40px;
  height: 46px;
  border-radius: 5px;
  background: var(--paper);
  transform: translateY(46px);
  -webkit-animation: paper05 var(--duration) linear infinite;
  animation: paper05 var(--duration) linear infinite;
}

.typewriter .paper:before {
  content: "";
  position: absolute;
  left: 6px;
  right: 6px;
  top: 7px;
  border-radius: 2px;
  height: 4px;
  transform: scaleY(0.8);
  background: var(--text);
  box-shadow: 0 12px 0 var(--text), 0 24px 0 var(--text), 0 36px 0 var(--text);
}

.typewriter .keyboard {
  width: 120px;
  height: 56px;
  margin-top: -10px;
  z-index: 1;
  position: relative;
}

.typewriter .keyboard:before, .typewriter .keyboard:after {
  content: "";
  position: absolute;
}

.typewriter .keyboard:before {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 7px;
  background: linear-gradient(135deg, var(--blue), var(--blue-dark));
  transform: perspective(10px) rotateX(2deg);
  transform-origin: 50% 100%;
}

.typewriter .keyboard:after {
  left: 2px;
  top: 25px;
  width: 11px;
  height: 4px;
  border-radius: 2px;
  box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
  -webkit-animation: keyboard05 var(--duration) linear infinite;
  animation: keyboard05 var(--duration) linear infinite;
}

@keyframes bounce05 {
  85%, 92%, 100% {
    transform: translateY(0);
  }

  89% {
    transform: translateY(-4px);
  }

  95% {
    transform: translateY(2px);
  }
}

@keyframes slide05 {
  5% {
    transform: translateX(14px);
  }

  15%, 30% {
    transform: translateX(6px);
  }

  40%, 55% {
    transform: translateX(0);
  }

  65%, 70% {
    transform: translateX(-4px);
  }

  80%, 89% {
    transform: translateX(-12px);
  }

  100% {
    transform: translateX(14px);
  }
}

@keyframes paper05 {
  5% {
    transform: translateY(46px);
  }

  20%, 30% {
    transform: translateY(34px);
  }

  40%, 55% {
    transform: translateY(22px);
  }

  65%, 70% {
    transform: translateY(10px);
  }

  80%, 85% {
    transform: translateY(0);
  }

  92%, 100% {
    transform: translateY(46px);
  }
}

@keyframes keyboard05 {
  5%, 12%, 21%, 30%, 39%, 48%, 57%, 66%, 75%, 84% {
    box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
  }

  9% {
    box-shadow: 15px 2px 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
  }

  18% {
    box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 2px 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
  }

  27% {
    box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 12px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
  }

  36% {
    box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 12px 0 var(--key), 60px 12px 0 var(--key), 68px 12px 0 var(--key), 83px 10px 0 var(--key);
  }

  45% {
    box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 2px 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
  }

  54% {
    box-shadow: 15px 0 0 var(--key), 30px 2px 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
  }

  63% {
    box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 12px 0 var(--key);
  }

  72% {
    box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 2px 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
  }

  81% {
    box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 12px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
  }
}
.pointer{
cursor : pointer;
}
</style>
    <wc-toast id="tt" position="top-right"> </wc-toast>
    
<script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/apexcharts.js"></script>

           <div class="panel p-3 mb-4 flex items-center text-primary overflow-x-auto whitespace-nowrap">
            <div class="ring-2 ring-primary/30 rounded-full bg-primary text-white p-1.5 ltr:mr-3 rtl:ml-3">

                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5">
                    <path d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z" stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <span class="ltr:mr-3 rtl:ml-3 float-right">Join Telegram For Latest Updates</span><a href="<?php echo $site_data['support_url'];?>" target="_blank"><button type="button" class="btn btn-primary btn-sm">Join Now</button></a>
        </div>
            <!-- Searchable -->
                <input type="hidden" name="tokens" id="tokens" value="<?php echo $_SESSION['token']; ?>">
                <div id="cards_page" class="panel p-4 "> 
                <div class="flex justify-center" style="padding:50px">
               <div class="typewriter">
    <div class="slide"><i></i></div>
    <div class="paper"></div>
    <div class="keyboard"></div>
</div>
    </div>
       </div>
         

   
<script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
            

<script>
back();
function back(){
document.getElementById("cards_page").innerHTML = `   
           <div class="text-center">
  <p class="font-bold font-2xl" >Recharge Methods</p>
  </div>

<!--<div class="mt-3 rounded border-double rounded-lg dark:border-[#4B0082] border-2 border-indigo-200">
    <div class="card flex items-center justify-between py-1">
        <div class="flex space-y-2">
            <div class="flex-shrink-0" style="margin-left:5px">
                <img src="https://cdn.iconscout.com/icon/free/png-256/free-bhim-3-69845.png" alt="BharatPe" class="mt-2 me-3 h-8 w-8" />
            </div>
            <div class="flex-grow-1" style="margin-left:5px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Upi Deposit</p>
                <p class="text-sm mb-0" style="font-size: 14px;">BharatPe QR</p>
            </div>
        </div>
        <span class="btn btn-primary" style="margin: 0rem 0.3rem 0rem 0rem;">
        <div class="flex-shrink-0 pointer " onclick="bharatpe()">
            <i class="fa fa-arrow-right text-2xl "></i>
        </div>
        </span>
    </div>
</div>

<div class="mt-3 rounded border-double rounded-lg dark:border-[#4B0082] border-2 border-indigo-200">
    <div class="card flex items-center justify-between py-1">
        <div class="flex space-y-2">
            <div class="flex-shrink-0" style="margin-left:5px">
                <img src="https://i.ibb.co/hZtsc8W/trx.png" alt="trx" class="mt-2 me-3 h-8 w-8" />
            </div>
            <div class="flex-grow-1" style="margin-left:5px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Tron | Trx</p>
                <p class="text-sm mb-0" style="font-size: 14px;">Nᴇᴛᴡᴏʀᴋ: ᴛʀᴄ20</p>
            </div>
        </div>
        <span class="btn btn-primary" style="margin: 0rem 0.3rem 0rem 0rem;">
        <div class="flex-shrink-0 pointer " onclick="trx()">
            <i class="fa fa-arrow-right text-2xl "></i>
        </div>
        </span>
    </div>
</div>
<div class="mt-3 rounded border-double rounded-lg dark:border-[#4B0082] border-2 border-indigo-200">
    <div class="card flex items-center justify-between py-1">
        <div class="flex space-y-2">
            <div class="flex-shrink-0" style="margin-left:5px">
                <img src="https://i.ibb.co/x1MqxSw/usdt.png" alt="usdt" class="mt-2 me-3 h-8 w-8" />
            </div>
            <div class="flex-grow-1" style="margin-left:5px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Tether | Usdt</p>
                <p class="text-sm mb-0" style="font-size: 14px;">Nᴇᴛᴡᴏʀᴋ: ꜱᴏʟ</p>
            </div>
        </div>
        <span class="btn btn-primary" style="margin: 0rem 0.3rem 0rem 0rem;">
        <div class="flex-shrink-0 pointer" onclick="usdt()">
            <i class="fa fa-arrow-right text-2xl "></i>
        </div>
        </span>
    </div>
</div>
<div class="mt-3 rounded border-double rounded-lg dark:border-[#4B0082] border-2 border-indigo-200">
    <div class="card flex items-center justify-between py-1">
        <div class="flex space-y-2">
            <div class="flex-shrink-0" style="margin-left:5px">
                <img src="https://cdn-icons-png.flaticon.com/512/6664/6664427.png" alt="PROMO" class="mt-2 me-3 h-8 w-8" />
            </div>
            <div class="flex-grow-1" style="margin-left:5px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Promocode</p>
                <p class="text-sm mb-0" style="font-size: 14px;">Gɪᴠᴇᴀᴡᴀʏ Sᴏᴏɴ</p>
            </div>
        </div>
        <span class="btn btn-primary" style="margin: 0rem 0.3rem 0rem 0rem;">
        <div class="flex-shrink-0 pointer" onclick="promo()">
            <i class="fa fa-arrow-right text-2xl "></i>
        </div>
        </span>
    </div>
</div>-->
${(Array.isArray(enabledGateways) && enabledGateways.includes('sprintpay')) ? `
<div class="mt-3 rounded border-double rounded-lg dark:border-[#4B0082] border-2 border-indigo-200">
    <div class="card flex items-center justify-between py-1">
        <div class="flex space-y-2">
            <div class="flex-shrink-0" style="margin-left:5px">
                <img src="img/sprintpay.png" alt="SprintPay" class="mt-2 me-3 h-8 w-8" onerror="this.src='img/svg/155-credit-card.svg';" />
            </div>
            <div class="flex-grow-1" style="margin-left:5px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Pay with SprintPay</p>
                <p class="text-sm mb-0" style="font-size: 14px;">Enter amount and pay on SprintPay (redirect)</p>
            </div>
        </div>
        <span class="btn btn-primary" style="margin: 0rem 0.3rem 0rem 0rem;">
        <div class="flex-shrink-0 pointer" onclick="sprintpayRedirect()">
            <i class="fa fa-arrow-right text-2xl "></i>
        </div>
        </span>
    </div>
</div>
<div class="mt-3 rounded border-double rounded-lg dark:border-[#4B0082] border-2 border-indigo-200">
    <div class="card flex items-center justify-between py-1">
        <div class="flex space-y-2">
            <div class="flex-shrink-0" style="margin-left:5px">
                <img src="img/sprintpay.png" alt="SprintPay Virtual Account" class="mt-2 me-3 h-8 w-8" onerror="this.src='img/svg/155-credit-card.svg';this.alt='Virtual Account';" />
            </div>
            <div class="flex-grow-1" style="margin-left:5px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Virtual Account</p>
                <p class="text-sm mb-0" style="font-size: 14px;">Pay using your SprintPay virtual account</p>
            </div>
        </div>
        <span class="btn btn-primary" style="margin: 0rem 0.3rem 0rem 0rem;">
        <div class="flex-shrink-0 pointer" onclick="virtualaccount()">
            <i class="fa fa-arrow-right text-2xl "></i>
        </div>
        </span>
    </div>
</div>
` : ``}
${(Array.isArray(enabledGateways) && (enabledGateways.includes('paymentpoint') || enabledGateways.includes('paypoint'))) ? `
<div class="mt-3 rounded border-double rounded-lg dark:border-[#4B0082] border-2 border-indigo-200">
    <div class="card flex items-center justify-between py-1">
        <div class="flex space-y-2">
            <div class="flex-shrink-0" style="margin-left:5px">
                <img src="img/paymentpoint.png" alt="PaymentPoint" class="mt-2 me-3 h-8 w-8" onerror="this.src='img/svg/155-credit-card.svg';" />
            </div>
            <div class="flex-grow-1" style="margin-left:5px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">PaymentPoint Virtual Account</p>
                <p class="text-sm mb-0" style="font-size: 14px;">Pay using your PaymentPoint virtual account</p>
            </div>
        </div>
        <span class="btn btn-primary" style="margin: 0rem 0.3rem 0rem 0rem;">
        <div class="flex-shrink-0 pointer" onclick="paymentpointAccount()">
            <i class="fa fa-arrow-right text-2xl "></i>
        </div>
        </span>
    </div>
</div>
` : ``}
`;
        //    at = end.split(":");
}

function paymentpointAccount(){
    Notiflix.Block.Dots('#cards_page', 'Loading...');
    $.ajax({
        type: "POST",
        url: "api/paymentpoint/get_bank_account.php",
        data: { token: document.getElementById("tokens").value },
        success: function(resp) {
            Notiflix.Block.Remove('#cards_page');
            var json = typeof resp === 'string' ? JSON.parse(resp) : resp;
            if (json.status === "1") {
                showAccountCard(json.data, 'PaymentPoint', 'img/paymentpoint.png');
            } else {
                $.ajax({
                    type: "POST",
                    url: "api/paymentpoint/generate_bank_account.php",
                    data: { token: document.getElementById("tokens").value },
                    success: function(r2) {
                        var j2 = typeof r2 === 'string' ? JSON.parse(r2) : r2;
                        if (j2.status === "1") {
                            showAccountCard(j2.data, 'PaymentPoint', 'img/paymentpoint.png');
                        } else {
                            if (typeof notyf !== 'undefined') notyf.error(j2.msg || 'PaymentPoint account not available.');
                            back();
                        }
                    },
                    error: function() {
                        if (typeof notyf !== 'undefined') notyf.error('Something went wrong.');
                        back();
                    }
                });
            }
        },
        error: function() {
            Notiflix.Block.Remove('#cards_page');
            if (typeof notyf !== 'undefined') notyf.error('Something went wrong.');
            back();
        }
    });
}

function showAccountCard(account, label, logo){
    document.getElementById("cards_page").innerHTML = `
<div class="card shadow-base2">
    <div class="card-text space-y-2">
        <div class="mb-2 flex p-2">
            <div class="flex-shrink-0 mt-1 p-1 pointer " onclick="back()">
                <i class="fa fa-arrow-left text-2xl"></i>
            </div>
            <div class="flex-grow-1" style="margin-left:10px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">${label} Account</p>
                <p class="text-sm mb-2" style="font-size: 12px;">Transfer to the account below to fund your wallet</p>
            </div>
        </div>
        <center>
            <img src="${logo}" height="60" onerror="this.style.display='none'">
        </center>
        <div class="p-2">
            <div class="mb-2" style="font-size:12px;">Account Number</div>
            <div class="flex items-center justify-between">
                <div style="font-weight:900;font-size:20px;">${account.account_number || ''}</div>
                <button class="btn btn-sm btn-outline-primary" onclick="copy('${account.account_number || ''}')">Copy</button>
            </div>
            <div class="mt-3" style="font-size:12px;">Account Name</div>
            <div style="font-weight:700;">${account.account_name || ''}</div>
            <div class="mt-3" style="font-size:12px;">Bank</div>
            <div style="font-weight:700;">${account.bank_name || label}</div>
        </div>
    </div>
</div>`;
}
function bharatpe(){
document.getElementById("cards_page").innerHTML = `    
<div class="card shadow-base2">
    <div class="card-text space-y-2">
        <div class="mb-2 flex p-2">
            <div class="flex-shrink-0 mt-1 p-1 pointer " onclick="back()">
                <i class="fa fa-arrow-left text-2xl"></i>
            </div>
            <div id="data22" class="flex-grow-1" style="margin-left:10px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Upi</p>
                <p class="text-sm mb-2" style="font-size: 12px;">You Can Pay Using All Upi Payment App</p>
            </div>
        </div>
        <center>
            <img src="<?php echo $site_data['upi_qr'];?>" height="200" width="200" style="margin-top: 0;">
            <p style="color: red;">Minimum Recharge Amount is ₦<?php echo $site_data['upi_min_recharge'];?></p>
        </center>
        <div style="position: relative; margin-top: 8px; text-align: left;"> <!-- Adjusted for left alignment -->
            <div class="flex">
                <input id="iconRight" type="text" value="<?php echo $site_data['upi_id'];?>" class="form-input ltr:rounded-r-none rtl:rounded-l-none" readonly/>
                <div onclick="copy('<?php echo $site_data['upi_id'];?>')" class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border border-transparent dark:border-[#17263c] dark:bg-[#1b2e4b]">
                    <i class="fa fa-clipboard text-2xl"></i>
                </div>
            </div>
            <div class="input-area">
                <center>
                    <label for="name" class="form-label">ENTER UTR NUMBER HERE</label>
                </center>
                <input id="ref_id" type="number" style="height:50px" class="form-input text-center" placeholder="UTR NUMBER">
            </div>
            <br>
            <center>
                <button id="recharges" onclick="add_upi();" class=" btn w-full btn-primary">
                      Add Money
                  </button>
            </center>
        </div>
    </div>
</div>


`;
        //    at = end.split(":");
}
function promo(){
document.getElementById("cards_page").innerHTML = `
<div class="card shadow-base2">
    <div class="card-text space-y-2">
        <div class="mb-2 flex p-2">
            <div class="flex-shrink-0 mt-1 p-1 pointer" onclick="back()">
                <i class="fa fa-arrow-left text-2xl"></i>
            </div>
            <div id="data22" class="flex-grow-1" style="margin-left:10px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">PROMOCODE</p>
                <p class="text-sm mb-2" style="font-size: 12px;">JOIN CHANNEL TO WIN PROMOCODE</p>
            </div>
        </div>
        <center>
            <img src="https://cdn-icons-png.flaticon.com/512/4152/4152021.png" height="200" width="200" style="margin-top: 0;">
        </center>
     
            <div class="input-area">
                <center>
                    <label for="name" class="form-label">ENTER PROMOCODE HERE</label>
                </center>
                <input id="redeem_id" type="text" style="height:50px" class="form-input text-center" placeholder="XXXX-XXXX-XXXX">
            </div>
            <br>
            <center>
                <button id="recharges1" onclick="add_promo();" class=" btn w-full btn-primary">
                      Redeem 
                  </button>
            </center>
        </div>
    </div>
</div>



`;
        //    at = end.split(":");
}
function sprintpayRedirect() {
    document.getElementById("cards_page").innerHTML = `
        <div class="card shadow-base2">
            <div class="mb-2 flex p-2">
                <div class="flex-shrink-0 mt-1 p-1 pointer" onclick="back()">
                    <i class="fa fa-arrow-left text-2xl"></i>
                </div>
                <div class="flex-grow-1" style="margin-left:10px">
                    <p class="mb-0" style="font-size: 15px; font-weight: bold;">Pay with SprintPay</p>
                    <p class="text-sm mb-2" style="font-size: 12px;">Enter amount and you will be redirected to SprintPay</p>
                </div>
            </div>
            <div class="text-center p-4">
                <img src="img/sprintpay.png" height="120" onerror="this.style.display='none'"><br>
                <p style="color: red">Minimum amount is ₦100</p>
                <div class="input-area mt-3">
                    <label class="form-label">Amount (₦)</label>
                    <input id="sprintpay_amount" type="number" class="form-input text-center" placeholder="e.g. 1000" min="100" value="1000" style="height:50px">
                </div>
                <br>
                <button id="sprintpay_btn" onclick="doSprintPayRedirect()" class="btn w-full btn-primary">Pay with SprintPay</button>
            </div>
        </div>`;
}
function doSprintPayRedirect() {
    var amount = parseInt(document.getElementById('sprintpay_amount').value, 10);
    if (isNaN(amount) || amount < 100) {
        notyf.error('Please enter at least ₦100');
        return;
    }
    var btn = document.getElementById('sprintpay_btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Redirecting...';
    $.ajax({
        type: "POST",
        url: "api/sprintpay/redirect_pay_url.php",
        data: { token: $("#tokens").val(), amount: amount },
        success: function(data) {
            var json = typeof data === 'string' ? JSON.parse(data) : data;
            if (json.status === '1' && json.pay_url) {
                window.location.href = json.pay_url;
            } else {
                notyf.error(json.msg || 'Could not get payment link');
                btn.disabled = false;
                btn.innerHTML = 'Pay with SprintPay';
            }
        },
        error: function() {
            notyf.error('An error occurred.');
            btn.disabled = false;
            btn.innerHTML = 'Pay with SprintPay';
        }
    });
}
function virtualaccount(){
        document.getElementById("cards_page").innerHTML = `
        <div class="card shadow-base2">
            <div class="mb-2 flex p-2">
                <div class="flex-shrink-0 mt-1 p-1 pointer " onclick="back()">
                    <i class="fa fa-arrow-left text-2xl"></i>
                </div>
                <div id="data22" class="flex-grow-1" style="margin-left:10px">
                    <p class="mb-0" style="font-size: 15px; font-weight: bold;">SprintPay Virtual Account</p>
                    <p class="text-sm mb-2" style="font-size: 12px;">Pay using your SprintPay virtual account</p>
                </div>
            </div>
            <?php if($userbankaccount !== null): ?>
                <div class="text-center">
                    <img src="img/sprintpay.png" height="200" width="200" style="display:inline-block; margin-top: 16px;" onerror="this.style.display='none'"><br>
                    <p style="color: red"> Minimum Transfer Amount is ₦100 </p>
                    <p>You can now make a direct transfer to your SprintPay virtual account and your funds will be added instantly.</p>
                    <?php
                        $acc = (is_array($userbankaccount) && isset($userbankaccount[0])) ? $userbankaccount[0] : $userbankaccount;
                        echo "<p><strong>Account Number:</strong> ". htmlspecialchars($acc['account_number']) ."</p>
                        <p><strong>Bank Name:</strong> ". htmlspecialchars($acc['bank_name']) ."</p>
                        <p><strong>Account Name:</strong> ". htmlspecialchars($acc['account_name']) ."</p>";
                    ?>
                </div>
            <?php else: ?>
                <div style="position: relative; margin-top: 8px; text-align: left;"> <!-- Adjusted for left alignment -->
                    <div class="text-center">
                        <img src="img/sprintpay.png" height="200" width="200" style="display:inline-block; margin-top: 16px;" onerror="this.style.display='none'"><br>
                        <p style="color: red"> Minimum Transfer Amount is ₦100 </p>
                         <div id="virtualBankView">
                            <p>Generate your SprintPay virtual account to top up your wallet.</p>
                            <p>Click the button below to generate</p>
                        </div>
                    </div>
                    <br>
                    <center>
                        <button id="generateAccountBtn" onclick="generateAccount();" class=" btn w-full btn-primary">
                            Generate Account
                        </button>
                    </center>
                </div>
            <?php endif; ?>
        </div>
        `;
    }
function trx(){
 
Notiflix.Block.standard('#cards_page');
$.ajax({
            type: "GET",
            url: "api/recharge/crypto/getPrice",
             error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                Notiflix.Block.remove('#cards_page');
              },
            success: function (data) {
     //      Notiflix.Block.remove('#cards_page');            
                var json = JSON.parse(data);
               var token = $("#tokens").val();
             var params = {
            token: token,
        };           
$.ajax({                
            type: "GET",
            data: params,    
            url: "api/recharge/crypto/getAddress/trx",
             error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                Notiflix.Block.remove('#cards_page');
              },
            success: function (data2) {
           Notiflix.Block.remove('#cards_page');            
                var json2 = JSON.parse(data2);              
      if(json2.ok !== true){ 
      document.getElementById("cards_page").innerHTML = `   
<div class="card shadow-base2">
    <div class="card-text space-y-2">
        <div class="mb-2 flex p-2">
            <div class="flex-shrink-0 mt-1 p-1 pointer" onclick="back()">
                <i class="fa fa-arrow-left text-2xl"></i>
            </div>
            <div id="data22" class="flex-grow-1" style="margin-left:10px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Tron | Trx</p>
                <p class="text-sm mb-2" style="font-size: 12px;">Nᴇᴛᴡᴏʀᴋ: ᴛʀᴄ20</p>
            </div>
           
        </div>
        <center>
            <img src="https://cdn-icons-png.flaticon.com/512/6192/6192061.png" height="150" width="150" style="margin-top: 0;">              
       </center>
        <div style="position: relative; margin-top: 8px; text-align: left;"> <!-- Adjusted for left alignment -->
            <div class="flex">
                <input id="iconRight" type="text" value="${json2.message}" class="form-input text-center ltr:rounded-r-none rtl:rounded-l-none" readonly/>
                  </div>
                   <div class="mt-2 text-center">
                        <span class="badge rounded-pill bg-primary">1 TRX = ₦ ${json.trx}</span>
                    </div>
                        <p style="color: red;" class="text-center mt-2">Minimum Deposit is <?php echo $site_data['trx_minimum'];?> TRX ( TRC20 )</p>       
              <br>
            <center>
                <button id="trx_btn" onclick="trx_address();" class=" btn w-full btn-primary">
                      Generate Address
                  </button>
            </center>
        </div>
    </div>
</div>


  `;
}else{           
document.getElementById("cards_page").innerHTML = `    <div class="col-lg-6">
     <div class="card shadow-base2">
    <div class="card-text space-y-2">
        <div class="mb-2 flex p-2">
            <div class="flex-shrink-0 mt-1 p-1 pointer" onclick="back()">
                <i class="fa fa-arrow-left text-2xl"></i>
            </div>
            <div id="data22" class="flex-grow-1" style="margin-left:10px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Trx</p>
                <p class="text-sm mb-2" style="font-size: 12px;">Trc20</p>
            </div>
           
        </div>
        <center>
            <img src="https://cdn-icons-png.flaticon.com/512/6192/6192061.png" height="150" width="150" style="margin-top: 0;">  
              <div class="mb-3 mt-4 text-center">
                  <span id="trx_time" style="padding: 5px 10px; border-radius: 20px; background: #e1e3e5; font-size:16px; "><i class="bx bxs-alarm" style="margin-bottom:2px"></i> 20:00</span> 
                    </div>            
       </center>
        <div style="position: relative; margin-top: 8px; text-align: left;"> <!-- Adjusted for left alignment -->
            <div class="flex">
                <input id="iconRight" onclick="copy('${json2.data.address}')" {type="text" value="${json2.data.address}" class="form-input text-center ltr:rounded-r-none rtl:rounded-l-none" readonly/>
                  </div>
                   <div class="mt-2 text-center">
                        <span class="badge rounded-pill bg-primary">1 TRX = ₦ ${json.trx}</span>
                    </div>
                        <p style="color: red;" class="text-center mt-2">Minimum Deposit is <?php echo $site_data['trx_minimum'];?> TRX ( TRC20 )</p>       
              <br>
            <center>
                <button id="cancle_btn_trx" onclick="trx_cancle_address();" class=" btn w-full btn-danger">
                      Delete Address
                  </button>
            </center>
        </div>
    </div>
</div>
  `;
countdownTimer(json2.data.left_time, 'trx_time');
}
}
        });
        }
        });
        //    at = end.split(":");
}
function usdt(){
Notiflix.Block.standard('#cards_page');
$.ajax({
            type: "GET",
            url: "api/recharge/crypto/getPrice",
             error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                Notiflix.Block.remove('#cards_page');
              },
            success: function (data) {
     //      Notiflix.Block.remove('#cards_page');            
                var json = JSON.parse(data);
               var token = $("#tokens").val();
             var params = {
            token: token,
        };           
$.ajax({                
            type: "GET",
            data: params,    
            url: "api/recharge/crypto/getAddress/usdt",
             error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                Notiflix.Block.remove('#cards_page');
              },
            success: function (data2) {
           Notiflix.Block.remove('#cards_page');            
                var json2 = JSON.parse(data2);              
      if(json2.ok !== true){ 
      document.getElementById("cards_page").innerHTML = `   
<div class="card shadow-base2">
    <div class="card-text space-y-2">
        <div class="mb-2 flex p-2">
            <div class="flex-shrink-0 mt-1 p-1 pointer" onclick="back()">
                <i class="fa fa-arrow-left text-2xl"></i>
            </div>
            <div id="data22" class="flex-grow-1" style="margin-left:10px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Tether | Usdt</p>
                <p class="text-sm mb-2" style="font-size: 12px;">Nᴇᴛᴡᴏʀᴋ: ꜱᴏʟ</p>
            </div>
           
        </div>
        <center>
            <img src="https://cdn-icons-png.flaticon.com/512/6192/6192061.png" height="150" width="150" style="margin-top: 0;">              
       </center>
        <div style="position: relative; margin-top: 8px; text-align: left;"> <!-- Adjusted for left alignment -->
            <div class="flex">
                <input id="iconRight" type="text" value="${json2.message}" class="form-input text-center ltr:rounded-r-none rtl:rounded-l-none" readonly/>
                  </div>
                   <div class="mt-2 text-center">
                        <span class="badge rounded-pill bg-primary">1 USDT = ₦ ${json.usdt}</span>
                    </div>
                        <p style="color: red;" class="text-center mt-2">Minimum Deposit is <?php echo $site_data['usdt_minimum'];?> USDT ( SOL )</p>       
              <br>
            <center>
                <button id="usdt_btn" onclick="usdt_address();" class=" btn w-full btn-primary">
                      Generate Address
                  </button>
            </center>
        </div>
    </div>
</div>


  `;
}else{           
document.getElementById("cards_page").innerHTML = `    <div class="col-lg-6">
     <div class="card shadow-base2">
    <div class="card-text space-y-2">
        <div class="mb-2 flex p-2">
            <div class="flex-shrink-0 mt-1 p-1 pointer" onclick="back()">
                <i class="fa fa-arrow-left text-2xl"></i>
            </div>
            <div id="data22" class="flex-grow-1" style="margin-left:10px">
                <p class="mb-0" style="font-size: 15px; font-weight: bold;">Usdt</p>
                <p class="text-sm mb-2" style="font-size: 12px;">Sol</p>
            </div>
           
        </div>
        <center>
            <img src="https://cdn-icons-png.flaticon.com/512/6192/6192061.png" height="150" width="150" style="margin-top: 0;">  
              <div class="mb-3 mt-4 text-center">
                  <span id="usdt_time" style="padding: 5px 10px; border-radius: 20px; background: #e1e3e5; font-size:16px; "><i class="bx bxs-alarm" style="margin-bottom:2px"></i> 20:00</span> 
                    </div>            
       </center>
        <div style="position: relative; margin-top: 8px; text-align: left;"> <!-- Adjusted for left alignment -->
            <div class="flex">
                <input id="iconRight" onclick="copy('${json2.data.address}')" {type="text" value="${json2.data.address}" class="form-input text-center ltr:rounded-r-none rtl:rounded-l-none" readonly/>
                  </div>
                   <div class="mt-2 text-center">
                        <span class="badge rounded-pill bg-primary">1 USDT = ₦ ${json.usdt}</span>
                    </div>
                        <p style="color: red;" class="text-center mt-2">Minimum Deposit is <?php echo $site_data['usdt_minimum'];?> USDT ( SOL )</p>       
              <br>
            <center>
                <button id="cancle_btn_usdt" onclick="usdt_cancle_address();" class=" btn w-full btn-danger">
                      Delete Address
                  </button>
            </center>
        </div>
    </div>
</div>
  `;
countdownTimer(json2.data.left_time, 'usdt_time');
}
}
        });
        }
        });
        //    at = end.split(":");
}


var notyf = new Notyf();
    
    function generateAccount(){
        // const userPhone = "<?php echo $userdata['phone_number']; ?>";
        
        // if (!userPhone) {
        //     notyf.error({
        //         message: 'Please visit your profile to add a phone number to generate the virtual account.',
        //         duration: 5000,
        //         position: {x:'right', y:'top'}
        //     });
        //     return;
        // }
        
        $("#generateAccountBtn").prop("disabled", true);
        $("#generateAccountBtn").html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span> Generating...');

        $.ajax({
            type: "POST",
            url: "api/sprintpay/generate_virtual_account.php",
            data: {
                token: "<?php echo $_SESSION['token'] ?>"
            },
            error: function (e) {
                notyf.error({
                    message: 'An error occurred during processing.',
                    duration: 5000,
                    position: {x:'right', y:'top'}
                });
                $("#generateAccountBtn").html("Generate Account");
                $("#generateAccountBtn").prop("disabled", false);
            },
            success: function (data) {
                $("#generateAccountBtn").hide();
                var json = JSON.parse(data);
                if (json.status === "1") {
                    notyf.success({
                        message: json.msg,
                        duration: 5000,
                        position: {x:'right', y:'top'}
                    });
                    showBankAccountDetails(json.data);
                } else {
                    notyf.error({
                        message: json.msg,
                        duration: 5000,
                        position: {x:'right', y:'top'}
                    });
                }
            },
        });
    }
    
    function showBankAccountDetails(account) {
        const viewContent = `
            <p><strong>Account Number:</strong> ${account.account_number}</p>
            <p><strong>Bank Name:</strong> ${account.bank_name}</p>
            <p><strong>Account Name:</strong> ${account.account_name}</p>
        `;
        
        $('#virtualBankView').html(viewContent);
    }

   function add_upi() {
        var txn_id = $("#ref_id").val();
       var token = $("#tokens").val();
        if (txn_id === '') {
            notyf.error('Please Enter Valid Utr.');
            return; // Stop execution if email or password is blank
        }

     

        $('#recharges').prop("disabled", true);
        $('#recharges').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="margin-right:8px"></span>Checking....');

        var params = {
            token: token,
            txn_id: txn_id,
        };
        $.ajax({
            type: "GET",
            url: "api/recharge/upi",
            data: params,
            error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                $('#recharges').html("Add Money");
                $('#recharges').prop("disabled", false);
            },
            success: function (data) {
                $('#recharges').html("Add Money");
                $('#recharges').prop("disabled", false);
                var json = JSON.parse(data);
                if (json.status === "200") {
            var params = { token: token};

             $.ajax({
                type: "POST",
                url: "api/auth/session",
                data: params,
                error: function (e) {
                    console.log(e);
                    },
                success: function (data) {
                    var jsons= JSON.parse(data);
                   // Get a reference to the <span> element by its ID
var spanElement = document.getElementById("current_balance");

// Set the data (text) you want to display in the <span>
spanElement.textContent = jsons.balance;
 
                }
            });
                    notyf.success(json.message);
                } else {
                    notyf.error(json.message);
                }
            }
        });
    }
    
   function add_promo() {
        var txn_id = $("#redeem_id").val();
       var token = $("#tokens").val();
        if (txn_id === '') {
            notyf.error('Please Enter Promocode.');
            return; // Stop execution if email or password is blank
        }

     

        $('#recharges1').prop("disabled", true);
        $('#recharges1').html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span>Checking....');

        var params = {
            token: token,
            code: txn_id,
        };
        $.ajax({
            type: "POST",
            url: "api/recharge/promocode",
            data: params,
            error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                $('#recharges1').html("Redeem");
                $('#recharges1').prop("disabled", false);
            },
            success: function (data) {
                $('#recharges1').html("Redeem");
                $('#recharges1').prop("disabled", false);
                var json = JSON.parse(data);
                if (json.status === "200") {
            var params = { token: token};

             $.ajax({
                type: "POST",
                url: "api/auth/session",
                data: params,
                error: function (e) {
                    console.log(e);
                    },
                success: function (data) {
                    var jsons= JSON.parse(data);
                   // Get a reference to the <span> element by its ID
var spanElement = document.getElementById("current_balance");

// Set the data (text) you want to display in the <span>
spanElement.textContent = jsons.balance;
 
                }
            });
                    notyf.success(json.message);
                } else {
                    notyf.error(json.message);
                }
            }
        });
    }
        
    
function trx_address(){
       var token = $("#tokens").val();
       

        $('#trx_btn').prop("disabled", true);
        $('#trx_btn').html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span>Generate Address');

        var params = {
            token: token,
         };
        $.ajax({
            type: "GET",
            url: "api/recharge/crypto/generateAddress/trx",
            data: params,
            error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                $('#trx_btn').html("Generate Address");
                $('#trx_btn').prop("disabled", false);
            },
            success: function (data) {
                $('#trx_btn').html("Generate Address");
                $('#trx_btn').prop("disabled", false);
                var json = JSON.parse(data);
                if (json.ok === true) {
                trx();
                } else {
                    notyf.error(json.message);
                }
            }
        });
}   
function trx_cancle_address(){
       var token = $("#tokens").val();
       

        $('#cancle_btn_trx').prop("disabled", true);
        $('#cancle_btn_trx').html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span>Delete Address');

        var params = {
            token: token,
         };
        $.ajax({
            type: "GET",
            url: "api/recharge/crypto/cancelAddress/trx",
            data: params,
            error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                $('#cancle_btn_trx').html("Delete Address");
                $('#cancle_btn_trx').prop("disabled", false);
            },
            success: function (data) {
                $('#cancle_btn_trx').html("Delete Address");
                $('#cancle_btn_trx').prop("disabled", false);
                var json = JSON.parse(data);
                if (json.ok === true) {
                trx();
                } else {
                    notyf.error(json.message);
                }
            }
        });
}   

function usdt_address(){
       var token = $("#tokens").val();
       

        $('#usdt_btn').prop("disabled", true);
        $('#usdt_btn').html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span>Generate Address');

        var params = {
            token: token,
         };
        $.ajax({
            type: "GET",
            url: "api/recharge/crypto/generateAddress/usdt",
            data: params,
            error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                $('#usdt_btn').html("Generate Address");
                $('#usdt_btn').prop("disabled", false);
            },
            success: function (data) {
                $('#usdt_btn').html("Generate Address");
                $('#usdt_btn').prop("disabled", false);
                var json = JSON.parse(data);
                if (json.ok === true) {
                usdt();
                } else {
                    notyf.error(json.message);
                }
            }
        });
}   
function usdt_cancle_address(){
       var token = $("#tokens").val();
       

        $('#cancle_btn_usdt').prop("disabled", true);
        $('#cancle_btn_usdt').html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span>Delete Address');

        var params = {
            token: token,
         };
        $.ajax({
            type: "GET",
            url: "api/recharge/crypto/cancelAddress/usdt",
            data: params,
            error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                $('#cancle_btn_usdt').html("Delete Address");
                $('#cancle_btn_usdt').prop("disabled", false);
            },
            success: function (data) {
                $('#cancle_btn_usdt').html("Delete Address");
                $('#cancle_btn_usdt').prop("disabled", false);
                var json = JSON.parse(data);
                if (json.ok === true) {
                usdt();
                } else {
                    notyf.error(json.message);
                }
            }
        });
}       
    function copy(text) {
    const el = document.createElement('textarea');
    el.value = text;

    // Set the textarea to be invisible
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';

    // Append the textarea to the document
    document.body.appendChild(el);

    // Select the text in the textarea
    el.select();

    // Copy the selected text
    document.execCommand('copy');

    // Remove the textarea from the document
    document.body.removeChild(el);
    notyf.success('Address Copied');
}

function countdownTimer(milliseconds, elementId) {
    // Convert milliseconds to minutes
    const totalMinutes = Math.floor(milliseconds / 60000);
    const element = document.getElementById(elementId);

    if (element) {
  //    element.textContent = `${totalMinutes}`;
                  $(element).empty().append('<i class="bx bxs-alarm" style="margin-bottom:2px"></i> 00:00');
      // Create a countdown interval
      const countdown = setInterval(() => {
        milliseconds -= 1000; // Decrease by one second (1000 milliseconds)

        if (milliseconds >= 0) {
const remainingMinutes = String(Math.floor(milliseconds / 60000)).padStart(2, '0');
const remainingSeconds = String(Math.floor((milliseconds % 60000) / 1000)).padStart(2, '0');
      //    element.textContent = `${remainingMinutes}:${remainingSeconds}`;
                  $(element).empty().append('<i class="bx bxs-alarm" style="margin-bottom:2px"></i> ' + remainingMinutes + ":" + remainingSeconds + '');
        } else {
          clearInterval(countdown); // Stop the countdown when time expires
          element.textContent = "Time Over";
        }
      }, 1000); // Update every second (1000 milliseconds)
    }
  }
</script>
  
<?php include 'include/footer-main.php'; ?>
