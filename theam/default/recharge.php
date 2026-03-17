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

   </header>
<?php
include("include/slidebar.php");
?>
<script>
        $(document).ready(function() {
            // Remove "active" class from all <a> elements
            $('#dashboard').removeClass("active");
            
            // Add "active" class to the specific element with ID "faq"
            $("#recharge").addClass("active");
        });
    </script>
<div class="contents">
    <div class="container-fluid">
        <div class="social-dash-wrap"><br>
            <div class="row">
                <div class="col-lg-6">
              <div class="card shadow-base2">
    <div class="card-body d-flex flex-column p-3">
        <div class="card-text space-y-5">
            <div class="d-flex align-items-center mb-0">
                <img id="flagimg" src="https://play-lh.googleusercontent.com/B5cNBA15IxjCT-8UTXEWgiPcGkJ1C07iHKwm2Hbs8xR3PnJvZ0swTag3abdC_Fj5OfnP" height="55" width="55" class="me-2">
                <div class="flex-grow-1">
                    <p class="text-black mb-0" style="font-size: 15px; color: black; font-weight: bold;">Upi</p>
                    <p class="text-sm mb-2" style="font-size: 12px;">You Can Pay Using All Upi Payment App</p>
                </div>
            </div>
            <center><img src="<?php echo $site_data['upi_qr'];?>" height="200" width="200" style="margin-top: 0;">
            <p style="color: red;">Minimum Recharge Amount is ₹<?php echo $site_data['upi_min_recharge'];?></p></center>
            
             <div style="position: relative; margin-top: 8px;">
    <input type="text" class="form-control" id="upi_copy" value="<?php echo $site_data['upi_id'];?>" style="padding-right: 40px; height:40px;" readonly>
   <a id="copyBtn">  <i class="fa fa-clipboard" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 24px;"></i></a>                    
                </div>
            <div class="input-area">
                <center><label for="name" class="form-label">ENTER UTR NUMBER HERE</label></center>
                <input type="hidden" name="tokens" id="tokens" value="<?php echo $_SESSION['token']; ?>">
                <input id="ref_id" type="number" style="height:50px" class="form-control" placeholder="UTR NUMBER">
            </div><br>
            
             <center><button id="recharges" class="justify-content-center w-100 btn mb-1 btn-rounded btn-primary d-flex align-items-center">
                <span class="d-flex align-items-center">
                    <span>Add Money</span>
                </span>
            </button></center>
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
<script type="module">
import { toast } from 'https://cdn.skypack.dev/wc-toast';
document.addEventListener("DOMContentLoaded", function() {
  const copyBtn = document.getElementById("copyBtn");
  const upiCopyElement = document.getElementById("upi_copy");

  if (copyBtn && upiCopyElement) {
    copyBtn.addEventListener("click", function() {
      const str = upiCopyElement.value.slice(0);
      const el = document.createElement('textarea');
      el.value = str;

      el.setAttribute('readonly', '');
      el.style.position = 'absolute';
      el.style.left = '-9999px';
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
        toast('Upi Address Copied', { icon: { type: 'custom', content: '📁' } });   


      this.classList.add("ticked");

      // Remove the class after a short delay to reset the animation
      setTimeout(() => {
        this.classList.remove("ticked");
      }, 500);
    });
  }
});
$(document).ready(function () {
    // Attach a click event handler to the button
    $("#recharges").click(function () {
        var txn_id = $("#ref_id").val();
       var token = $("#tokens").val();
        if (txn_id === '') {
            toast.error('Please Enter Valid Utr.');
            return; // Stop execution if email or password is blank
        }

     

        $('#recharges').prop("disabled", true);
        $('#recharges').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Checking....');

        var params = {
            token: token,
            txn_id: txn_id,
        };
        $.ajax({
            type: "GET",
            url: "api/recharge/upi",
            data: params,
            error: function (e) {
                console.log(e);
                toast.error('An error occurred during login.');
                $('#recharges').html("Add Money");
                $('#recharges').prop("disabled", false);
            },
            success: function (data) {
                $('#recharges').html("Add Money");
                $('#recharges').prop("disabled", false);
                var json = JSON.parse(data);
                if (json.status === "200") {
                    toast.success(json.message);
                } else {
                    toast.error(json.message);
                }
            }
        });
    });
  });
</script>   
</body>

</html>