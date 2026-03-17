<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Recharge - <?php echo $web_name;?></title>
   <?php
   include("include/head.php");
   ?>
</head>

<body class="layout-light side-menu">
    <wc-toast id="tt" position="top-right"> </wc-toast>
    
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
      </nav>
   </header>
   <?php
   include("include/slidebar.php");
   ?>
   <script>
        $(document).ready(function() {
            // Remove "active" class from all <a> elements
            $('#dashboard').removeClass("active");
            
            // Add "active" class to the specific element with ID "faq"
            $("#profile").addClass("active");
        });
    </script>
      <div class="contents">

         <div class="profile-setting ">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-lg-12">

                     <div class="breadcrumb-main">
                        <h4 class="text-capitalize breadcrumb-title">My profile</h4>
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                           <nav aria-label="breadcrumb">
                              <ol class="breadcrumb">
                                 <li class="breadcrumb-item"><a href="#"><i class="uil uil-estate"></i>Home</a></li>
                                 <li class="breadcrumb-item active" aria-current="page">My profile</li>
                              </ol>
                           </nav>
                        </div>
                     </div>

                  </div>
                  <div class="col-xxl-3 col-lg-4 col-sm-5">
                     <!-- Profile Acoount -->
                     <div class="card mb-25">
                        <div class="card-body text-center p-0">

                           <div class="account-profile border-bottom pt-25 px-25 pb-0 flex-column d-flex align-items-center ">
                              <div class="ap-img mb-20 pro_img_wrapper">
                                 <input id="file-upload" type="file" name="fileUpload" class="d-none">
                                 <label for="file-upload">
                                    <!-- Profile picture image-->
                                    <img class="ap-img__main rounded-circle wh-120" src="img/ellipse15.png" alt="profile">
                                    <span class="cross" id="remove_pro_pic">
                                       <img src="img/svg/camera.svg" alt="camera" class="svg">
                                    </span>
                                 </label>
                              </div>
                              <div class="ap-nameAddress pb-3">
                                 <h5 class="ap-nameAddress__title"><?php echo $userdata['name'];?></h5>
                                 <p class="ap-nameAddress__subTitle fs-14 m-0"><?php echo $userdata['email'];?></p>
                              </div>
                           </div>
                           <div class="ps-tab p-20 pb-25">
                              <div class="nav flex-column text-start" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                     <a class="nav-link active" id="v-pills-messages-tab" data-bs-toggle="pill" href="#v-pills-messages" role="tab" aria-selected="true">
                                    <img src="img/svg/key.svg" alt="key" class="svg">change password</a>
                               </div>
                           </div>

                        </div>
                     </div>
                     <!-- Profile Acoount End -->
                  </div>
                  <div class="col-xxl-9 col-lg-8 col-sm-7">
                 
                     <div class="mb-50">
                        <div class="tab-content" id="v-pills-tabContent">
                          <div class="tab-pane active" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                              <!-- Edit Profile -->
                              <div class="edit-profile mt-25">
                                 <div class="card">
                                    <div class="card-header  px-sm-25 px-3">
                                       <div class="edit-profile__title">
                                          <h6>change password</h6>
                                          <span class="fs-13 color-light fw-400">Change or reset your account
                                             password</span>
                                       </div>
                                    </div>
                                    <div class="card-body">
                                       <div class="row justify-content-center">
                                          <div class="col-xxl-6">
                                             <div class="edit-profile__body mx-xl-20">
                                                  <div class="form-group mb-20">
                                                  <input type="hidden" value="<?php echo $_SESSION['token'];?>" id="tokens">
                                                      <label for="name">old passowrd</label>
                                                      <input type="text" class="form-control" id="old_password">
                                                   </div>
                                                   <div class="form-group mb-1">
                                                      <label for="password-field">new password</label>
                                                      <div class="position-relative">
                                                         <input id="new_password" type="password" class="form-control" name="new_password" placeholder="Password">
                                                     </div>
                                                      <small id="passwordHelpInline" class="text-light fs-13">Minimum
                                                         6
                                                         characters
                                                      </small>
                                                      <div class="button-group d-flex flex-wrap pt-45 mb-35">


                                                         <button id="change_pass" class="btn btn-primary btn-default w-100 btn-squared text-capitalize lh-normal px-50 signIn-createBtn">Save Changes
                                                         </button>


                                                      </div>
                                                   </div>
                                              </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <!-- Edit Profile End -->
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
<?php 
include("include/custom_js.php");
?>
</body>

</html>
