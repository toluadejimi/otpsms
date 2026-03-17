<?php 
$page_name = "Profile";
include 'include/header-main.php'; 
?>
<script>
        $(document).ready(function() {
            // Remove "active" class from all <a> elements
            $('#slide-dashboard').removeClass("active");
            
            // Add "active" class to the specific element with ID "faq"
            $("#slider-profile").addClass("active");
        });
    </script> 
    <wc-toast id="tt" position="top-center"> </wc-toast>    
<script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/apexcharts.js"></script>
<div x-data="analytics">
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] before:mr-1 rtl:before:ml-1 ">
            <span>Profile</span>
        </li>
    </ul>
        <!--    <div class="panel p-3 flex items-center text-primary overflow-x-auto whitespace-nowrap mt-3">
            <div class="ring-2 ring-primary/30 rounded-full bg-primary text-white p-1.5 ltr:mr-3 rtl:ml-3">

                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5">
                    <path d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z" stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <span class="ltr:mr-3 rtl:ml-3">Join Telegram For Latest Updates </span><a href="<?php echo $site_data['channel_url'];?>" target="_blank"><button type="button" class="btn btn-primary btn-sm">Join Now</button></a>
        </div> -->
    
    <div class="panel mt-5">
        <h5 class="font-bold text-lg dark:text-white-light">User Information</h5>
        <div class="grid grid-cols-1 gap-5 mt-2">
            <input type="hidden" id="tokens" value="<?php echo $_SESSION['token']; ?>">
            
            <!-- Name field (readonly) -->
            <div>
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" value="<?php echo $userdata['name']; ?>" class="form-input" readonly />
            </div>

            <!-- Email field (readonly) -->
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" value="<?php echo $userdata['email']; ?>" class="form-input" readonly />
            </div>

            <!-- Phone number field (editable) -->
            <div>
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" id="phone_number" value="<?php echo $userdata['phone_number']; ?>" class="form-input" />
            </div>
        </div>
        <button type="button" id="save_profile" class="btn btn-primary w-full mt-6">Save Changes</button>
    </div>
    
    <div class="panel mt-5">
     <h5 class="font-bold text-lg dark:text-white-light">Change Password</h5> 
    <div class="grid grid-cols-1 sm:flex justify-between gap-5 mt-2">
      <input type="hidden" id="tokens" value="<?php echo $_SESSION['token'];?>">                     
        <input type="text" id="old_password" placeholder="Enter Old Password" class="form-input" />
        <input type="text" id="new_password" placeholder="Enter New Password" class="form-input" />
        <input type="text" id="confirm_password" placeholder="Enter Confirm Password" class="form-input" />                
    </div>
    <button type="button" id="change_pass" class="btn btn-primary w-full mt-6">Save</button>
      </div>      
<?php 
include("include/custom_js.php");
?>                   
<?php include 'include/footer-main.php'; ?>
