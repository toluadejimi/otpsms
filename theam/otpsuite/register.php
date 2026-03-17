<?php
$page_name = "Register";
include 'include/header-main-auth.php';
?>
<div class="login-page align-items-center d-flex">
    <div class="container">
        <div class="card my-5 mx-auto p-2">
            <div class="card-body">
                <div class="logo mb-2 position-relative">
                    <a href="/">
                        <img src="logo.png" alt="Logo Image" width="160" />
                    </a>
                </div>
                <form id="register-form" method="POST">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div>
                                <div class="card-title mb-2">
                                    <h1 class="text-dark fw-bold">Create Account</h1>
                                    <p class="text-secondary fw-light">Join Otpsuite and start managing your digital services</p>
                                </div>

                                <!-- name -->
                                <div class="form-group mb-2">
                                    <label for="name" class="form-label fw-semibold text-dark">Your
                                      Name
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="ri-user-3-line"></i>
                                        <input type="text" name="name" id="name" placeholder="Enter Your Name">
                                    </div>
                                    <div id="name-error"></div>
                                </div>

                                <!-- email address -->
                                <div class="form-group mb-2">
                                    <label for="email" class="form-label fw-semibold text-dark">Your
                                        Email Address
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="ri-mail-line"></i>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email">
                                    </div>
                                    <div id="email-error"></div>
                                </div>

                                <!-- phone number -->
                                <div class="form-group mb-2">
                                    <label for="phone_number" class="form-label fw-semibold text-dark">
                                        Phone Number 
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="ri-phone-line"></i>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter Your Phone Number">
                                    </div>
                                    <div id="phone-error"></div>
                                </div>

                                <!-- password -->

                                <div class="form-group mb-2">
                                    <label for="password" class="form-label fw-semibold text-dark">
                                        Password 
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="ri-lock-line"></i>
                                        <input type="password" name="password" class="form-control passsword" id="password" placeholder="Enter Password">
                                        <i class="ri-eye-line toggle-password" data-target="password"></i>
                                    </div>
                                    <div id="password-error"></div>
                                </div>

                                <input type="hidden" id="refer_id" value="<?php echo $_GET['ref_id'] ?? ""; ?>" />  
                                <!-- confirm password -->
                                <div class="form-group mb-2">
                                    <label for="confirmPassword" class="form-label fw-semibold text-dark">
                                        Confirm Password
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="ri-lock-line"></i>
                                        <input type="password" class="form-control password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter Password">
                                        <i class="ri-eye-line toggle-password" data-target="confirmPassword"></i>
                                    </div>
                                    <div id="confirmPassword-error"></div>
                                </div>

                                 <div class="form-group form-check">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="agree" value="option1">
                                        <label class="form-check-label" for="agree">
                                            I agree to <span class="text-primary fw-semibold">Terms of Service</span> and <span class="text-primary fw-semibold">Privacy Policy</span>.
                                        </label>
                                    </div>
                                    <div id="agree-error"></div>
                                </div>

                                <button id="register" type="submit" class="w-100 btn btn-primary mt-2 ls-1">
                                    Register
                                </button>

                                <div class="text-center mt-3">
                                    <span class="fw-semibold text-muted">Already have an account ?</span>
                                    <a href="login" class="fw-semibold text-primary">Sign In</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // PASSWORD VISIBILITY TOGGLER
    document.querySelectorAll('.toggle-password').forEach(icon => {
      icon.addEventListener('click', () => {
        const inputId = icon.getAttribute('data-target');
        const input = document.getElementById(inputId);

        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('ri-eye-line');
          icon.classList.add('ri-eye-off-line');
        } else {
          input.type = 'password';
          icon.classList.remove('ri-eye-off-line');
          icon.classList.add('ri-eye-line');
        }
      });
    });
</script>
<script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>
<script>
  const notyf = new Notyf({
      position: {x:'right',y:'top'}
    }
  );

  if ($("#register-form").length) {
    const validation = new JustValidate('#register-form', {
      errorFieldCssClass: 'is-invalid',
      errorLabelCssClass: 'just-validate-error-label',
    });

    validation
      .addField('#name', [{
          rule: 'required',
          errorMessage: "Field is required"
        },
        {
          rule: 'minLength',
          value: 3,
        },
        {
          rule: 'maxLength',
          value: 60,
        },
      ], {
        errorsContainer: '#name-error'
      })
      .addField('#email', [{
          rule: 'required',
          errorMessage: 'Field is required',
        },
        {
          rule: 'email',
          errorMessage: 'Email is invalid!',
        },
      ], {
        errorsContainer: '#email-error'
      })
      .addField('#phone_number', [{
          rule: 'required'
        },
        {
          rule: 'customRegexp',
          value: /^\d{11}$/,
          errorMessage: 'Phone number must be 11 digits',
        },
      ], {
        errorsContainer: '#phone-error'
      })
      .addField('#password', [{
          rule: 'minLength',
          value: 6,
        },
        {
          rule: 'required',
          errorMessage: "Please provide a password"
        }
      ], {
        errorsContainer: '#password-error'
      })
      .addField('#confirmPassword', [{
          rule: 'minLength',
          value: 6,
        },
        {
          rule: 'required',
          errorMessage: "Field is required"
        },
        {
          validator: (value, fields) => {
            if (fields['#password'] && fields['#password'].elem) {
              const repeatPasswordValue = fields['#password'].elem.value;

              return value === repeatPasswordValue;
            }

            return true;
          },
          errorMessage: 'Passwords should be the same',
        }
      ], {
        errorsContainer: '#confirmPassword-error'
      })
      .addField(
          '#agree',
          [
              {
                  rule: 'required',
                  errorMessage: "Please Accept Terms"
              },
          ],
          {
              errorsContainer: '#agree-error',
          }
      )
      .onSuccess((event) => {
          const registerForm = document.getElementById("register-form");

          const formData = new FormData(registerForm);

          const data = {};

          for (var [key, value] of formData.entries()) { 
              data[key] = value;
          }
          
          data['refer_id'] = $("#refer_id").val();

          // Disable the button and show loading spinner
          $("#register")
          .prop("disabled", true)
          .html(
              '<div class="spinner-border spinner-border-sm"><span class="visually-hidden">Loading...</span></div> Registering...'
          );

          $.ajax({
              type: "POST",
              url: "api/auth/register",
              data: data,
              dataType: "json", // Specify JSON response type
              success: function (json) {
              $("#register").html("Register").prop("disabled", false);
              // grecaptcha.reset(widgetId);
              if (json.status === "1") {
                  notyf.success(json.msg);
                  setTimeout(() => {
                      location.href = "dashboard";
                  }, 3000);
              } else {
                  notyf.error(json.msg);
              }
              },
              error: function (e) {
                  console.log(e);
                  notyf.error("An error occurred during Registration");
                  $("#register").html("Register").prop("disabled", false);
              },
          });
      });
  }
</script>
<?php
include 'include/footer-main-auth.php';
?>