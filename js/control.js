import { toast } from 'https://esm.sh/wc-toast';

function validateEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailRegex.test(email);
}

$(document).ready(function () {
    // Attach a click event handler to the button
    $("#login").click(function () {
        var email = $("#email").val();
        var password = $("#password").val();

        if (email === '' || password === '') {
            toast.error('Please Enter Email & Password.');
            return; // Stop execution if email or password is blank
        }

        if (!validateEmail(email)) {
            toast.error('Please Enter Valid Email');
            return;
        }

        $('#login').prop("disabled", true);
        $('#login').html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span>Sign in...');

        var params = {
            email: email,
            password: password,
        };
        $.ajax({
            type: "POST",
            url: "api/auth/login",
            data: params,
            error: function (e) {
                console.log(e);
                toast.error('An error occurred during login.');
                $('#login').html("Sign In");
                $('#login').prop("disabled", false);
            },
            success: function (data) {
                $('#login').html("Sign In");
                $('#login').prop("disabled", false);
                var json = JSON.parse(data);
                if (json.status === "1") {
                    toast.success(json.msg);
                    setTimeout(function () {
                        window.location.href = 'dashboard';
                    }, 1000);
                } else {
                    toast.error(json.msg);
                }
            }
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
        $("#register").click(function () {
        var widgetId;
        var name = $("#name").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        var refer_id = $("#refer_id").val();
     
        if (name === '' || email === '' || password === '' || confirm_password === '') {
            toast.error('Please Fill All Details');
            return; // Stop execution if old or new password is blank
        }
        if(password !== confirm_password){
                  toast.error('Password And Confirm Password Not Match');
            return; // Stop execution if old or new password is blank
          }
      if (!validateEmail(email)) {
            toast.error('Please Enter Valid Email');
            return;
        }
        //  var recaptchaResponse = grecaptcha.getResponse();
        //     if (!recaptchaResponse) {
        //   toast.error("Please Fill Captcha");
        //  return;
        //     } 
        // Disable the button and show loading spinner
        $('#register').prop("disabled", true).html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span>Sign Up...');

        var params = {
            name: name,
            email: email,
            password: password, 
            refer_id: refer_id,
        //   "g-recaptcha-response": recaptchaResponse, 
       };
        $.ajax({
            type: "POST",
            url: "api/auth/register",
            data: params,
            dataType: "json", // Specify JSON response type
            success: function (json) {
                $('#register').html("Sign Up").prop("disabled", false);
                // grecaptcha.reset(widgetId);
                if (json.status === "1") {
                    toast.success(json.msg);
                    setTimeout(() => {
                        location.href="index";
                    }, 1500)
                } else {
                    toast.error(json.msg);
                }
            },
            error: function (e) {
                console.log(e);
                toast.error('An error occurred during Registration');
                $('#register').html("Sign Up").prop("disabled", false);
            }
        });
    });
    if($("#save_profile").length){
      // Handle phone number update
      $("#save_profile").click(function () {
        var phone_number = $("#phone_number").val();  // Get the updated phone number
        var token = $("#tokens").val();  // Token for authentication
    
        if (phone_number === "") {
          toast.error("Please Enter Phone Number.");
          return; // Stop execution if phone number is blank
        }
    
        // Disable the button and show a loading spinner
        $(this).prop("disabled", true).html(
          '<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span>Saving...'
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
    }
});
