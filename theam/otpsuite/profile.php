<?php
$page_name = "Dashboard";
include 'include/header-main.php';
?>
<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md hydrated"></i>
                </a>
                <div class="pageTitle">Profile</div>
            </div>
            <div class="right">
            </div>
        </div>
    </div>

    <div id="appCapsule">
        <input type="hidden" id="tokens" value="<?php echo $_SESSION['token']; ?>">
        <div class="section p-0 mt-2 mb-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Basic Info</h5>
                    <h6 class="card-subtitle mb-1">Edit Basic Information</h6>
                    <form id="profile-form" method="POST">
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" value="<?= $userdata['name'] ?>" disabled>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="Email">Email</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter Email" value="<?= $userdata['email'] ?>" disabled>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" placeholder="Enter Phone" value="<?= $userdata['phone_number'] ?>">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="button" id="save_profile" class="btn btn-primary w-100">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="section p-0 mt-2 mb-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Change Password</h5>
                    <h6 class="card-subtitle mb-1">Change your password here</h6>
                    <form id="profile-form" method="POST">
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <input type="text" class="form-control" id="old_password" placeholder="Enter Old password">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <input type="text" class="form-control" id="new_password" placeholder="Enter New Password">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <input type="text" class="form-control" id="confirm_password" placeholder="Enter Confirm Password">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="button" id="change_pass" class="btn btn-primary w-100">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
        include("include/bottom-menu.php")
    ?>
</div>
<script>
    const toast = new Notyf({
        position: {x:'right',y:'top'}
    });

    $(document).ready(function () {
        // Handle phone number update
        $("#save_profile").click(function () {
            var phone_number = $("#phone").val();  // Get the updated phone number
            var token = $("#tokens").val();  // Token for authentication
        
            if (phone_number === "") {
                toast.error("Please Enter Phone Number.");
                return; // Stop execution if phone number is blank
            }
        
            // Disable the button and show a loading spinner
            $(this).prop("disabled", true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>Saving...'
            );
        
            var params = {
                phone_number: phone_number,
                token: token,
            };
        
            // AJAX request to update the phone number
            $.ajax({
            type: "POST",
            url: "api/auth/update_phone_number",  // The URL to the PHP script for updating phone number
            data: params,
            dataType: "json",  // Expecting a JSON response
            success: function (json) {
                $("#save_profile").html("Save Changes").prop("disabled", false);  // Enable button after request
                if (json.status === "1") {
                toast.success(json.msg);  // Show success message
                } else {
                toast.error(json.msg);  // Show error message
                }
            },
            error: function (e) {
                console.log(e);
                toast.error("An error occurred during phone number update.");
                $("#save_profile").html("Save Changes").prop("disabled", false);  // Enable button after error
            },
            });
        });

        $("#change_pass").click(function () {
            var old_password = $("#old_password").val();
            var new_password = $("#new_password").val();
            var confirm_password = $("#confirm_password").val();

            var tokens = $("#tokens").val(); // Corrected variable name

            if (old_password === '' || new_password === '') {
                toast.error('Enter Old And New Password.');
                return; // Stop execution if old or new password is blank
            }
            if (new_password !== confirm_password ) {
                toast.error('New And Confirm Password Not Match.');
                return; // Stop execution if old or new password is blank
            }
            // Disable the button and show loading spinner
            $(this).prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>');

            var params = {
                new_password: new_password,
                old_password: old_password,
                token: tokens, // Corrected variable name
            };
            $.ajax({
                type: "POST",
                url: "api/auth/change_password",
                data: params,
                dataType: "json", // Specify JSON response type
                success: function (json) {
                    $('#change_pass').html("Save").prop("disabled", false);
                    if (json.status === "1") {
                        toast.success(json.msg);
                    } else {
                        toast.error(json.msg);
                    }
                },
                error: function (e) {
                    console.log(e);
                    toast.error('An error occurred during password change.');
                    $('#change_pass').html("Save").prop("disabled", false);
                }
            });
        });
    });
</script>  
<?php
    include 'include/footer-main.php';
?>