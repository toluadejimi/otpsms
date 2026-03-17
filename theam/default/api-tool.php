<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Api Tool- <?php echo $web_name;?></title>
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
            $("#api-tool").addClass("active");
        });
    </script>
 <div class="contents">
    <div class="container-fluid">
        <div class="social-dash-wrap"><br>
            <div class="row">
                <div class="col-lg-10">
                       <div class="card card-default card-md mb-4">
                            <div class="card-body">


                           <div class="dm-empty text-center">
                              <div class="">

                                 <img src="data:image/png;base64,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" height="150px" alt="Admin Empty">

                              </div>
                              
                              <div class="dm-empty__text">
                                       <p style="font-size:20px; font-weight:bold;">Api Comming Soon</p>
                              
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