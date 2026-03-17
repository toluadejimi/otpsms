<?php
$page_name = "Login";
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
                <form id="login-form" method="POST">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div>
                                <div class="card-title mb-2">
                                    <h1 class="text-dark fw-bold">Welcome Back</h1>
                                    <p class="text-secondary fw-light">Sign in to access your dashboard</p>
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

                                <button id="login" type="submit" class="w-100 btn btn-primary mt-2 ls-1">
                                    Login
                                </button>

                                <div class="text-center mt-3">
                                    <span class="fw-semibold text-muted">Don't have an account ?</span>
                                    <a href="register" class="fw-semibold text-primary">Sign Up</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>
<script>
    // Notification
    const notyf = new Notyf({
        position: {x:'right',y:'top'}
      }
    );

    if($("#login-form").length){
      const validation = new JustValidate('#login-form', {
        errorFieldCssClass: 'is-invalid',
      });

      validation
      .addField('#email', [
        {
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
      .addField('#password', [
        {
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
      .onSuccess((event) => {
          const loginForm = document.getElementById("login-form");

          const formData = new FormData(loginForm);
          const data = {};

          for (var [key, value] of formData.entries()) { 
            data[key] = value;
          }

          $("#login").prop("disabled", true);
          $("#login").html(
            '<div class="spinner-border spinner-border-sm"><span class="visually-hidden">Loading...</span></div>Sign in...'
          );

          $.ajax({
            type: "POST",
            url: "/api/auth/login",
            data: data,
            xhrFields: { withCredentials: true },
            error: function (e) {
              console.log(e);
              notyf.error("An error occurred during login.");
              $("#login").html("Sign In");
              $("#login").prop("disabled", false);
            },
            success: function (data) {
              $("#login").html("Sign In");
              $("#login").prop("disabled", false);
              var json = JSON.parse(data);
              if (json.status === "1") {
                notyf.success(json.msg);
                setTimeout(function () {
                  window.location.href = window.location.origin + "/dashboard";
                }, 1000);
              } else {
                notyf.error(json.msg);
              }
            },
          });
      });
    }

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
<?php
include 'include/footer-main-auth.php';
?>