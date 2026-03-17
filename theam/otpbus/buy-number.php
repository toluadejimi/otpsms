<?php 
$page_name = "Buy Numbers";
include 'include/header-main.php'; 
?>
<script>
        $(document).ready(function() {
            // Remove "active" class from all <a> elements
            $('#slide-dashboard').removeClass("active");
            
            // Add "active" class to the specific element with ID "faq"
            $("#slide-buy-number").addClass("active");
        });
    </script>    
<link rel='stylesheet' type='text/css' href='<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/css/nice-select2.css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/animations.min.css" integrity="sha512-GKHaATMc7acW6/GDGVyBhKV3rST+5rMjokVip0uTikmZHhdqFWC7fGBaq6+lf+DOS5BIO8eK6NcyBYUBCHUBXA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-3.2.6.min.css" rel="stylesheet">
<style>
textarea{
    display: block;
    resize: none;
    padding-top: 5px;
    padding-left : 15px;
    border-radius: 10px;
}
.loadernum{
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

.loadernum .wrapper > div {
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

@keyframes gradient-animation_2 {
  0% {
    transform: translateX(-100%);
  }

  100% {
    transform: translateX(100%);
  }
}
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
            <span class="ltr:mr-3 rtl:ml-3">Join Telegram For Latest Updates </span><a href="<?php echo $site_data['channel_url'];?>" target="_blank"><button type="button" class="btn btn-primary btn-sm">Join Now</button></a>
        </div> -->
            <!-- Searchable -->
            <div class="panel mt-2 dark:bg-[#1b2e4b] dark:border-0" >
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Buy Numbers</h5>
                  
                </div>
             <input type="hidden" id="server_no" value=""> 
             <input type="hidden" id="token" value="<?php echo $_SESSION['token'];?>">                                                                                          
                  <div class="mb-5">
                  <select id="server-id" class="selectize">
                   <option value="" selected disabled>Select Server</option>
                         <?php
                           foreach($server as $servers){
                            echo "<option value=".$servers['id'].">".$servers['server_name']."</option>";
                              }
                              ?>
                 </select>
                </div>
                <div class="mb-3">
                    <select id="service-id">
                           </select>
                </div>
                <div class="mt-5">
                <button type="button" id="buy-numbers" class="btn btn-primary w-full"><span class='fa fa-cart-plus' style='margin-right: 8px;'></span>Buy Number</button>
                </div>          
            </div>
  
<div id="card-container" class="mt-3 rounded-lg">
           <div class="panel" >
               <center><img src="https://cdn-icons-png.flaticon.com/512/5089/5089767.png" height="100" width="100"></center>
                <div class="flex items-center justify-center mt-2">
                    <h5 class="font-bold text-lg dark:text-white-light">No Any Active Numbers</h5>
                  
                </div>
              </div>
</div>
<div id="my_model" class="">

</div>
   
<script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script>
                     
<script src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/nice-select2.js"></script>
            
 <script src="js/main.js?v=55448535"></script>
  <script type="module" src="js/sms.js?v=67652121"></script>
 <script src="js/xy.js?v=22929"></script>
   
<?php include 'include/footer-main.php'; ?>
