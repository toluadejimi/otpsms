<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Dashboard - <?php echo $web_name;?></title>
<?php
include("include/head.php");
?>

</head>

<body class="layout-light side-menu">
   <div class="mobile-author-actions"></div>
   <header class="header-top">
      <nav class="navbar navbar-light">
         <div class="navbar-left">
       <?php
include("include/logo.php");
?> 
            <div class="top-menu">

               <div class="hexadash-top-menu position-relative">

               </div>

            </div>
         </div>
         <!-- ends: navbar-left -->
<?php
include ("include/navbar.php");
?>       
   </header>
<?php
include("include/slidebar.php");
?>
<div class="contents">
    <div class="container-fluid">
        <div class="social-dash-wrap"><br>
            <div class="row">
                <div class="col-xxl-6 col-sm-6 mb-25">
                    <!-- Card 1  -->
                    <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                        <div class="overview-content w-100">
                            <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                                <div class="ap-po-details__titlebar">
                                    <h1>₹<?php echo $data['balance'];?></h1>
                                    <p>BALANCE</p>
                                </div>
                                <div class="ap-po-details__icon-area">
                                    <div class="svg-icon order-bg-opacity-primary color-primary">
                                        <i class="fa fa-rupee-sign"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 1 End  -->
                </div>
                <div class="col-xxl-6 col-sm-6 mb-25">
                    <!-- Card 2 -->
                    <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                        <div class="overview-content w-100">
                            <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                                <div class="ap-po-details__titlebar">
                                    <h1>₹<?php echo $data['total_recharge'];?></h1>
                                    <p>TOTAL RECHARGE</p>
                                </div>
                                <div class="ap-po-details__icon-area">
                                    <div class="svg-icon order-bg-opacity-info color-info">
                                        <i class="fa fa-gas-pump"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 2 End  -->
                </div>
                <div class="col-xxl-6 col-sm-6 mb-25">
                    <!-- Card 3 -->
                    <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                        <div class="overview-content w-100">
                            <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                                <div class="ap-po-details__titlebar">
                                    <h1><?php echo $data['total_otp'];?></h1>
                                    <p>TOTAL OTP BUY</p>
                                </div>
                                <div class="ap-po-details__icon-area">
                                    <div class="svg-icon order-bg-opacity-secondary color-secondary">
                                        <i class="fa fa-radiation"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 3 End  -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include("include/footer.php");
?>
   </main>
<?php
include("include/loader.php");
?>
<?php
include("include/script.php");
?>
</body>

</html>