<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Number  History- <?php echo $web_name;?></title>
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
            $("#numbers-history").addClass("active");
        });
    </script>
 <div class="contents">
    <div class="container-fluid">
        <div class="social-dash-wrap"><br>
            <div class="row">
                <div class="col-lg-12">
<!-- empty -->
<?php 
 if(!$transactions){
 ?>                      <div class="card card-default card-md mb-4">
                        <div class="card-header">
                           <h6>No History</h6>
                        </div>
                        <div class="card-body">


                           <div class="dm-empty text-center">
                              <div class="dm-empty__image">

                                 <img src="img/svg/3.svg" alt="Admin Empty">

                              </div>
                              
                              <div class="dm-empty__text">
                                       <p>History Not Found</p>
                                 <div class="dm-empty__action">
                                    <a href="buy-number" ><button class="btn btn-primary btn-sm btn-squared">Buy Number</button></a>
                                 </div>

                              </div>
                           </div>


                        </div>
                     </div>
<?php }else{ ?> 
         <?php 
                                        $i = 1;
                                        foreach($transactions as $transaction){  
                                        $img = getImageUrlBasedOnDigits($transaction['number']);
                                          ?>                                          
                    <div id="card-container" class="card shadow-white col-md-12">
                        <div class="card d-flex flex-column" style="padding: 10px;">
                            <div class="card-text space-y-1">
                                <div class="d-flex align-items-center" style="margin-bottom: 0px;">
                                    <img id="flagimg" src="<?php echo $img; ?>" height="45" width="45" class="me-2">
                                    <div class="flex-grow-1 mb-0">
                                        <div class="d-flex align-items-center">
                                            <p id="num" class="text-black mb-0" style="color: black">+<?php echo $transaction['number']; ?></p>
                                        </div>
                                        <p id="service-name" class="d-inline text-sm mb-2"><?php echo $transaction['service_name']; ?> - ₹<?php echo $transaction['service_price']; ?> <!-- (S:<?php echo $transaction['server_id']; ?>) --> <?php echo $transaction['buy_time']; ?></p>
                                    </div>
                                </div>
                                <div class="d-flex" style="margin-top: 10px;">
                                    <input id="sms" class="form-control w-full mt-0" placeholder="Searching for otp" value="<?php echo $transaction['sms']; ?>" style="height: 45px;" disabled>
                                    <button id="copy" onclick="copy('<?php echo $transaction['sms']; ?>')" class="btn btn-primary" style="height: 43px; padding-right: 10px; padding-left: 10px">Copy</button>
                                </div>
                            </div>
                        </div>
                    </div> 
<?php 
}} 
?>                 
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
<script>
function copy(text) {
    // Create a textarea element to hold the text
    var textarea = document.createElement("textarea");
    textarea.value = text;

    // Make the textarea invisible
    textarea.style.position = "absolute";
    textarea.style.left = "-9999px";

    // Add the textarea to the document
    document.body.appendChild(textarea);

    // Select the text in the textarea
    textarea.select();

    try {
        // Use the Clipboard API to copy the selected text
        document.execCommand("copy");
        Toastify({
            text: "Copied: " + text,
            duration: 1000,
            gravity: "top",
            position: 'left',
        }).showToast();
    } catch (err) {
        console.error("Unable to copy: " + err);
    }

    // Remove the textarea from the document
    document.body.removeChild(textarea);
}

</script>
</body>

</html>