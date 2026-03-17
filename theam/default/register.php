
<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Login - <?php echo $web_name;?></title>

   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

   <!-- inject:css-->

   <link rel="stylesheet" href="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/css/bootstrap/bootstrap.css">

   <link rel="stylesheet" href="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/css/daterangepicker.css">

   <link rel="stylesheet" href="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/css/fontawesome.css">

   <link rel="stylesheet" href="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/css/footable.standalone.min.css">

   <link rel="stylesheet" href="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/css/fullcalendar@5.2.0.css">

   <link rel="stylesheet" href="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/css/jquery-jvectormap-2.0.5.css">

   <link rel="stylesheet" href="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/css/jquery.mCustomScrollbar.min.css">


   <link rel="stylesheet" href="<?php echo WEBSITE_URL; ?>/theam/default/assets/style.css">

   <!-- endinject -->
   <link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png">
   <!-- Fonts -->
   <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.0/css/line.css">

</head>

<body>
    <wc-toast id="tt" position="top-right"> </wc-toast>
    
   <main class="main-content">

      <div class="admin">
         <div class="container-fluid">
            <div class="row justify-content-center">
               <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-8">
                  <div class="edit-profile">                   
                     <div class="card border-0">
                        <div class="card-header">
                           <div class="edit-profile__title">
                              <h6>Sign Up <?php echo $web_name;?></h6>
                           </div>
                        </div>
                        <div class="card-body">
                           <div class="edit-profile__body">
                         <div class="form-group mb-25">
                                 <label for="username">Name</label>
                                 <input type="text" class="form-control" id="name" placeholder="Enter Name">
                              </div>   
                            <div class="form-group mb-25">
                                 <label for="username">Email Address</label>
                                 <input type="email" class="form-control" id="email" placeholder="Enter Email">
                              </div>
                              <div class="form-group mb-25">
                                 <label for="password">Password</label>
                                 <input type="password" class="form-control" id="password" placeholder="Enter Password">
                              </div>
                              <div class="form-group mb-15">
                                 <label for="password-field">Confirm Password</label>
                                 <div class="position-relative">
                                    <input  type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Password">
                                  </div>
                              </div>
                                  
                                 <div class="admin__button-group button-group d-flex pt-1 justify-content-md-start justify-content-center">
                                 <button class="btn btn-primary btn-default w-100 btn-squared text-capitalize lh-normal px-50 signIn-createBtn " id="register">
                                    sign in
                                 </button>
                              </div>
                           </div> 
                        </div><!-- End: .card-body -->
                     
                        <div class="admin-topbar">
                           <p class="mb-0">
                              Already have an account ?
                              <a href="index" class="color-primary">
                                 Sign in
                              </a>
                           </p>
                        </div><!-- End: .admin-topbar  -->
                     </div><!-- End: .card -->
                  </div><!-- End: .edit-profile -->
               </div><!-- End: .col-xl-5 -->
            </div>
         </div>
      </div><!-- End: .admin-element  -->

   </main>
 
   <!-- inject:js-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/js/jquery/jquery-3.5.1.min.js"></script>

   <script src="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/js/jquery/jquery-ui.js"></script>

   <script src="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/js/bootstrap/popper.js"></script>

   <script src="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/js/bootstrap/bootstrap.min.js"></script>

   <script src="<?php echo WEBSITE_URL; ?>/theam/default/assets/vendor_assets/js/moment/moment.min.js"></script>

   <script src="<?php echo WEBSITE_URL; ?>/theam/default/assets/theme_assets/js/main.js"></script>

<?php 
include("include/custom_js.php");
?>  
 <!-- endinject-->
</body>

</html>