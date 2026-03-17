<?php
$page_name = "Buy International Numbers";
include 'include/header-main.php';
?>
<script>
  $(document).ready(function() {
    // Remove "active" class from all <a> elements
    $('#slide-dashboard').removeClass("active");

    // Add "active" class to the specific element with ID "faq"
    $("#slide-buy-international-numbers").addClass("active");
  });
</script>
<link rel='stylesheet' type='text/css' href='<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/css/nice-select2.css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/animations.min.css" integrity="sha512-GKHaATMc7acW6/GDGVyBhKV3rST+5rMjokVip0uTikmZHhdqFWC7fGBaq6+lf+DOS5BIO8eK6NcyBYUBCHUBXA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-3.2.6.min.css" rel="stylesheet">
<style>
  textarea {
    display: block;
    resize: none;
    padding-top: 5px;
    padding-left: 15px;
    border-radius: 10px;
  }

  .loadernum {
    position: relative;
    width: auto;
    height: 180px;
    margin-bottom: 10px;
    border: 1px solid #d3d3d3;
    padding: 15px;
    background-color: #e3e3e3;
    overflow: hidden;
  }

  .loadernum:after {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background: linear-gradient(110deg, rgba(227, 227, 227, 0) 0%, rgba(227, 227, 227, 0) 40%, rgba(227, 227, 227, 0.5) 50%, rgba(227, 227, 227, 0) 60%, rgba(227, 227, 227, 0) 100%);
    animation: gradient-animation_2 1.2s linear infinite;
  }

  .loadernum .wrapper {
    width: 100%;
    height: 100%;
    position: relative;
  }

  .loadernum .wrapper>div {
    background-color: #cacaca;
  }

  .loadernum .circlenum {
    width: 50px;
    height: 50px;
    border-radius: 50%;
  }

  .loadernum .buttonnum {
    display: inline-block;
    height: 32px;
    width: 75px;
  }

  .loadernum .line-1 {
    position: absolute;
    top: 11px;
    left: 58px;
    height: 10px;
    width: 100px;
  }

  .loadernum .line-2 {
    position: absolute;
    top: 34px;
    left: 58px;
    height: 10px;
    width: 150px;
  }

  .loadernum .line-3 {
    position: absolute;
    top: 57px;
    left: 0px;
    height: 10px;
    width: 100%;
  }

  .loadernum .line-4 {
    position: absolute;
    top: 80px;
    left: 0px;
    height: 10px;
    width: 92%;
  }

  .services-loader {
    display: block;
    margin: 0 auto;
    width: 60px;
    aspect-ratio: 4;
    --_g: no-repeat radial-gradient(circle closest-side,#496fd9 90%,#0000);
    background: 
      var(--_g) 0%   50%,
      var(--_g) 50%  50%,
      var(--_g) 100% 50%;
    background-size: calc(100%/3) 100%;
    animation: l7 1s infinite linear;
  }

  @keyframes l7 {
    33%{background-size:calc(100%/3) 0%  ,calc(100%/3) 100%,calc(100%/3) 100%}
    50%{background-size:calc(100%/3) 100%,calc(100%/3) 0%  ,calc(100%/3) 100%}
    66%{background-size:calc(100%/3) 100%,calc(100%/3) 100%,calc(100%/3) 0%  }
  }

  .service-price-loader {
    width: 20px;
    padding: 8px;
    aspect-ratio: 1;
    border-radius: 50%;
    background: #496fd9;
    --_m: 
      conic-gradient(#0000 10%,#000),
      linear-gradient(#000 0 0) content-box;
    -webkit-mask: var(--_m);
            mask: var(--_m);
    -webkit-mask-composite: source-out;
            mask-composite: subtract;
    animation: l3 1s infinite linear;
  }
  @keyframes l3 {to{transform: rotate(1turn)}}

  /* .services-loader {
    width: 38px;
    height: 38px;
    border: 3px solid #808080;
    border-radius: 50%;
    display: inline-block;
    position: relative;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
  }

  .services-loader::after {
    content: '';
    box-sizing: border-box;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 46px;
    height: 46px;
    border-radius: 50%;
    border: 3px solid;
    border-color: rgb(73 111 217/var(--tw-bg-opacity)) transparent;
  }

  @keyframes rotation {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  @keyframes gradient-animation_2 {
    0% {
      transform: translateX(-100%);
    }

    100% {
      transform: translateX(100%);
    }
  } */
</style>
<wc-toast id="tt" position="top-right"> </wc-toast>

<script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/apexcharts.js"></script>
<!--    <div class="panel p-3 flex items-center text-primary overflow-x-auto whitespace-nowrap mt-3">
            <div class="ring-2 ring-primary/30 rounded-full bg-primary text-white p-1.5 ltr:mr-3 rtl:ml-3">

                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5">
                    <path d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z" stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <span class="ltr:mr-3 rtl:ml-3">Join Telegram For Latest Updates </span><a href="<?php echo $site_data['channel_url']; ?>" target="_blank"><button type="button" class="btn btn-primary btn-sm">Join Now</button></a>
        </div> -->
<!-- Searchable -->
<!-- <div id="card-container" class="mt-3 rounded-lg">
  <div class="panel">
   
                    <div class="iq-alert-text" style="text-align: center;">
                        <p style="font-size: 16px; font-weight: bold;">Join Our Community</p>
                        <p>Stay updated and connect with us through our official channels:</p>

                        <a href="https://t.me/ebucesmsverify" target="_blank"
                           style="display: inline-block; background-color: #0088cc; color: #fff; padding: 10px 20px;
              border-radius: 5px; text-decoration: none; font-weight: bold; margin: 5px;">
                            <i class="fab fa-telegram-plane"></i> Join Telegram
                        </a>

                </div>
            </div>
  </div>
</div> -->
<div class="panel buy-number-panel mt-2 dark:bg-[#1b2e4b] dark:border-0">
 
  <div class="flex items-center justify-between mb-5">
    <h5 class="font-semibold text-lg dark:text-white-light">Buy International Number</h5>

  </div>
  <input type="hidden" id="server_no" value="">
  <input type="hidden" id="token" value="<?php echo $_SESSION['token']; ?>">
    <div class="mb-5">
      <select id="country-id" class="selectize">
       <option value="" selected disabled>Select Country</option>
             <?php
                $count = 1;
                foreach($servers as $server){
                  echo "<option value=".$server['id'].">" . $server['server_name'] ."</option>";
                  $count++;
                }
              ?>
     </select>
    </div>
    <div id="services-container" class="hidden mb-3">
      <select id="service-id">
      </select>
    </div>
    <div id="services-loader" class="my-3 text-center hidden">
        <span class="services-loader"></span>
    </div>
  <div id="service-price-loader" class="my-1 service-price-loader hidden"></div>
  <p id="pricing-information-view" class="dark:text-white-light"></p>
  <div class="mt-5">
    <button type="button" id="buy-numbers" class="btn btn-primary w-full"><i class='bx bx-sm bx-cart-add mr-2'></i> Buy Number</button>
  </div>
</div>

<div class="space-y-5 mt-5">
    <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md bg-white dark:bg-[#0e1726] " x-data="{ active: null }">
        <div class="flex font-semibold p-5 rounded-t-md  cursor-pointer" :class="{'bg-primary/20 text-primary' : active === 1}" x-on:click="active === 1 ? active = null : active = 1">
            <span class="text-primary">Read IF You're Encounting Any Issues </span>
            <div class="ltr:ml-auto  rtl:mr-auto flex">

                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :class="{ 'rotate-180': active === 1 }">
                    <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
        </div>
        <div x-show="active === 1" x-collapse="" style="display: none; height: 0px; overflow: hidden;" hidden="">
            <div class="p-3 font-semibold">
                <p style="color: red">
                    Not receiving Otp/Code?
                </p>
                <p></p>
                <p class="p-2 text-[#515365] dark:text-white-light">
                    If you don't receive any otp/code after 2-7 mins..
                    Just cancel the previous number and request for another number..
                </p>
                <p>
                    ■ If you buy any number and its show you that it have been banned.. (Mostly: WhatsApp/Telegram)
                    Just cancel and also request for another numbers 
                </p>
            </div>
        </div>
    </div>
    
    <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md bg-white dark:bg-[#0e1726] " x-data="{ active: null }">
                <div class="flex font-semibold p-5 rounded-t-md  cursor-pointer" :class="{'bg-primary/20 text-primary' : active === 1}" x-on:click="active === 1 ? active = null : active = 1">
                    <span class="text-primary">Read Before You Buy Numbers </span>
                    <div class="ltr:ml-auto  rtl:mr-auto flex">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :class="{ 'rotate-180': active === 1 }">
                            <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                </div>
                <div x-show="active === 1" x-collapse="" style="display: none; height: 0px; overflow: hidden;" hidden="">
                    <div class="p-3 font-semibold">
                        <p class="p-2 text-[#515365] dark:text-white-light">
                           <p style="color:#408EE0"><strong>Telegram Tips: </strong></p>
                                <ul> 
                                ● Telegram numbers have only 45% percent rate to receive code.
                                </ul>
                                <ul>
                                ● 50/50 to receive code on telegram numbers.<ul>
                                <ul>● Add Two 2fa authentication after purchasing.</ul>
                                <p style="color: green"><strong>WhatsApp Tips: </strong></p>
                                ● 50/50 chance for using WhatsApp Business.
                                <ul>● Don't Text immediately with the WhatsApp after purchasing.</ul>
                                <ul>●  Add Two 2fa authentication after purchasing.</ul>
                                <ul>●  Delete And install back your WhatsApp Before Buying..</ul>
                            </p>
                    </ul></ul></div>
                </div>
            </div>
    </div>

<div id="card-container" class="mt-3 rounded-lg">
  <div class="panel">
    <center><img src="img/ebuce_logo.png" height="100" width="100"></center>
    <div class="flex items-center justify-center mt-2">
      <h5 class="font-bold text-lg dark:text-white-light">No Active Numbers</h5>
    </div>
  </div>
</div>
<div id="my_model" class="">

</div>

    

<script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script>

<script src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/nice-select2.js"></script>

<script src="js/main-int.js?v=55448539"></script>
<script type="module" src="js/sms-int.js?v=056"></script>
<script src="js/xy.js?v=22929"></script>

<?php include 'include/footer-main.php'; ?>