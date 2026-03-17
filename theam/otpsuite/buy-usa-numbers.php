
<?php
$page_name = "Dashboard";
include 'include/header-main.php';
?>
<link rel='stylesheet' type='text/css' href='<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/css/nice-select2.css'>
<style>
    .pool-container{
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(160px,1fr));
        gap:10px;
    }
    
    .pool-card{
        border:1px solid #ddd;
        border-radius:10px;
        padding:12px;
        cursor:pointer;
        transition:0.2s;
        background:#fff;
    }
    
    .pool-card:hover{
        border-color:#496fd9;
        transform:scale(1.02);
    }
    
    .pool-card.active{
        background: #FF6D001A;
        border: 2px solid #FF6D004D;
    }
    
    .pool-price{
        font-weight:700;
        font-size:16px;
    }
    
    .pool-meta{
        font-size:12px;
        color:#666;
    }

    .number-count-loader {
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
    .form-group p{
        font-size: 12px;
    }

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

  .number-count-loader {
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
<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md hydrated"></i>
                </a>
                <div class="pageTitle">Buy Your Desired Number</div>
            </div>
            <div class="right">
            </div>
        </div>
    </div>

    <div id="appCapsule">
       <div class="small-wallet-card mb-2">
            <div class="detail">
                <strong>Wallet Balance</strong>
                <p>₦<span id="current_balance"><?php echo $userwallet['balance'];?></span></p>
            </div>
            <div class="right">
                <a href="#" class="w-100 btn btn-primary ls-1">
                    Fund Wallet
                </a>
            </div>
       </div>
       <input type="hidden" id="token" value="<?php echo $_SESSION['token']; ?>">
       <ul class="nav nav-tabs lined" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="buy-usa-only-numbers">
                    USA Numbers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="buy-usa-numbers">
                    USA/Canada Numbers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="buy-international-number">
                    International
                </a>
            </li>
        </ul>
        <div class="form-group basic">
            <div class="input-wrapper my-3">
                <select id="country-id" disabled>
                    <?php
                        $count = 1;
                        foreach($countries as $country){
                            echo "<option value=".$country['id'].">" . $country['server_name'] ."</option>";
                            $count++;
                        }
                    ?>
                </select>
            </div>
        </div>

        <div id="services-container" class="form-group basic d-none">
            <div class="input-wrapper mb-3">
                <select id="service-id">
                    <option value="">-- Please select a service --</option>
                </select>
            </div>
        </div>

         <div id="services-loader" class="d-none form-group basic">
            <div class="text-center mb-3">
                <div id="spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <button id="loadMoreBtn" class="btn btn-secondary btn-sm mt-2" style="display: none;">Load More</button>
            </div>
        </div>
        
        <div id="pricing-information-view"></div>

        <!--<div class="accordion">-->
        <!--    <div class="accordion-item">-->
        <!--        <h2 class="accordion-header">-->
        <!--            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordionb1">-->
        <!--                Advanced Preferences (optional)-->
        <!--            </button>-->
        <!--        </h2>-->
        <!--        <div id="accordionb1" class="accordion-collapse collapse" data-bs-parent="#accordionExample3">-->
        <!--            <div class="accordion-body">-->
        <!--                <div class="form-group boxed">-->
        <!--                    <div class="input-wrapper">-->
        <!--                        <label class="label" for="areaCode">Area Code (Optional)</label>-->
        <!--                        <input type="text" class="form-control" id="areaCode" placeholder="e.g., 212, 310, 415">-->
        <!--                        <i class="clear-input">-->
        <!--                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>-->
        <!--                        </i>-->
        <!--                    </div>-->
        <!--                    <p class="my-1">Specific area code may have limited availability</p>-->
        <!--                </div>-->
        <!--                <div class="form-group boxed">-->
        <!--                    <div class="input-wrapper">-->
        <!--                        <label class="label" for="carrier">Carrier</label>-->
        <!--                        <select class="form-control custom-select" id="carrier">-->
        <!--                            <option value="">Any carrier</option>-->
        <!--                            <option value="tmo">T-Mobile (tmo)</option>-->
        <!--                            <option value="vz">Verizon (vz)</option>-->
        <!--                            <option value="att">AT&T (att)</option>-->
        <!--                        </select>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="form-group boxed">-->
        <!--                    <div class="input-wrapper">-->
        <!--                        <label class="label" for="phoneNo">Prefered Number (optional)</label>-->
        <!--                        <input type="text" class="form-control" id="phoneNo" placeholder="Enter specific digits you want">-->
        <!--                        <i class="clear-input">-->
        <!--                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>-->
        <!--                        </i>-->
        <!--                    </div>-->
        <!--                    <p class="my-1">We'll try to find numbers matching your preference</p>-->
        <!--                </div>-->
        <!--                <div class="price-summary" id="priceSummary" style="display: none;">-->
        <!--                    <h6>Price Impact Summary</h6>-->
        <!--                    <div class="price-breakdown" id="priceBreakdown"></div>-->
        <!--                    <button class="clear-settings btn btn-primary" onclick="clearAllSettings()">Clear All</button>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->

        <div class="mt-3">
            <button type="button" id="buy-numbers" class="btn btn-primary w-100"><i class="ri-shopping-cart-2-line me-2"></i> Purchase Number</button>
        </div>

        <div class="card my-4">
            <div class="card-body" id="card-container">
                <div>
                    <center><img src="https://cdn-icons-png.flaticon.com/512/5089/5089767.png" height="100" width="100"></center>
                    <div class="d-flex align-items-center justify-content-center mt-2">
                        <h5 class="fw-bold text-lg text-center">No Active Numbers</h5>
                    </div>
              </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteNumberModal" tabindex="-1" aria-labelledby="deleteNumberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">

                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title" id="deleteNumberModalLabel">
                        Number Delete Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-center">
                    <div class="text-danger fs-1 mb-3">
                        <i class='ri-delete-bin-line'></i>
                    </div>
                    <p class="fs-6">Are you sure you want to delete this Number <strong id="deletePhoneNumber"></strong>?</p>

                    <div class="d-flex justify-content-center mt-4 gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                            <span class='ri-delete-bin-line me-2'></span> Delete
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php
        include("include/bottom-menu.php")
    ?>
</div>
<script src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/js/nice-select2.js"></script>
<script type="module" src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/js/sms-usa.js?v=4324232"></script>
<script src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/js/main-usa.js?v=23212121"></script>
<script src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/js/cancel.js?v=44212"></script>
<?php
    include 'include/footer-main.php';
?>