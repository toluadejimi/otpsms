<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Faq - <?php echo $web_name;?></title>
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
<script>
        $(document).ready(function() {
            // Remove "active" class from all <a> elements
            $('#dashboard').removeClass("active");
            
            // Add "active" class to the specific element with ID "faq"
            $("#faq").addClass("active");
        });
    </script>
      <div class="contents">

         <div class="container-fluid">
            <div class="social-dash-wrap"><br><br>
               <div class="row">
                                                  <div>
                  <div class="mb-30">
                     <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade  show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                           <!-- Edit Profile -->

                           <div class="card h-100 shadow-lg pb-md-50 pb-30 mb-md-50 mb-30">
                              <div class="card-header px-30 pt-30 pb-25 border-bottom-0">
                                 <h4 class="fw-500">Frequently Asked Questions</h4>
                              </div>
                              <div class="card-body pt-0">
                                 <div class="application-faqs">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                       <!-- panel 1 -->
                                       <div class="panel panel-default">
                                          <div class="panel-heading" role="tab" id="headingOne">
                                             <h4 class="panel-title">
                                                <a data-bs-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false">
                                                   How to Deposit?
                                                </a>
                                             </h4>

                                          </div>
                                          <div id="collapseOne" class="panel-collapse collapse in show" role="tabpanel" aria-labelledby="headingOne">
                                             <div class="panel-body">
                                                <p class="mb-sm-35 mb-20">You can Deposit using TRX,USDT by go "/recharge" and entering amount and click to "Pay" it will it you to the payment page , Where you can select the coins and BlockChain Networks .</p>
                                                                                     
                                             </div>
                                          </div>
                                       </div>
                                       <!-- panel 2 -->
                                       <div class="panel panel-default">
                                          <div class="panel-heading" role="tab" id="headingTwo">
                                             <h4 class="panel-title">
                                                <a class="collapsed" data-bs-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false">
                                                  I haved paid but the amount is not creadit to my account.
                                                </a>
                                             </h4>

                                          </div>
                                          <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                             <div class="panel-body">
                                                <p class="mb-sm-35 mb-20">If you had paid the payment and the payment screen is comfired the payment but you don't not get the amount in your wallet then contact the Support term and share your payment link and HASH..</p>
                                               </div>
                                          </div>
                                       </div>
                                       <!-- panel 3 -->
                                       <div class="panel panel-default">
                                          <div class="panel-heading" role="tab" id="headingThree">
                                             <h4 class="panel-title">
                                                <a class="collapsed" data-bs-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false">
                                                   I don't get the country that I want to buy for particular service
                                                </a>
                                             </h4>

                                          </div>
                                          <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                             <div class="panel-body">
                                                <p class="mb-sm-35 mb-20">Its may be due to low stock or 0 stock , You can wait for few min and check it aga</p>
                                                                                    </div>
                                          </div>
                                       </div>
                                       <!-- panel 4 -->
                                       <div class="panel panel-default">
                                          <div class="panel-heading" role="tab" id="headingfour">
                                             <h4 class="panel-title">
                                                <a class="collapsed" data-bs-toggle="collapse" data-parent="#accordion" href="#collapsefour" aria-expanded="false">
                                                   I want to use it for reselling.
                                                </a>
                                             </h4>

                                          </div>
                                          <div id="collapsefour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfour">
                                             <div class="panel-body">
                                                <p class="mb-sm-35 mb-20">Yes, You can sell our services to your Customer with our api that is easy to interigate ..</p>
                                                                                          </div>
                                          </div>
                                       </div>
                                       <!-- panel 5 -->
                                       <div class="panel panel-default">
                                          <div class="panel-heading" role="tab" id="headingfive">
                                             <h4 class="panel-title">
                                                <a class="collapsed" data-bs-toggle="collapse" data-parent="#accordion" href="#collapsefive" aria-expanded="false">
                                                Is it possible to get second sms on the same number?
                                                </a>
                                             </h4>

                                          </div>
                                          <div id="collapsefive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfive">
                                             <div class="panel-body">
                                                <p class="mb-sm-35 mb-20">Yes, You can receive the second sms on the same number in active state..</p>
                                                                                    </div>
                                          </div>
                                       </div>
                                       <!-- panel 6 -->
                                       <div class="panel panel-default">
                                          <div class="panel-heading" role="tab" id="headingsix">
                                             <h4 class="panel-title">
                                                <a class="collapsed" data-bs-toggle="collapse" data-parent="#accordion" href="#collapsesix" aria-expanded="false">
                                                if my account banned , how can i recover ?
                                                </a>
                                             </h4>

                                          </div>
                                          <div id="collapsesix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingsix">
                                             <div class="panel-body">
                                                <p class="mb-sm-35 mb-20">You can recover your account , but if you have done any kind of illegal activity, there is no chance of recover.</p>
                                                                  </div>
                                          </div>
                                       </div>
                                                                </div>
                                 </div>
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