<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Buy Number - <?php echo $web_name;?></title>
<?php
include("include/head.php");
?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
.select2-container--classic .select2-selection--single,
.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container--default .select2-selection--single .select2-selection__arrow,
.select2-container--default .select2-selection--multiple,
.select2-container--classic .select2-selection--single .select2-selection__arrow,
.select2-container--classic .select2-selection--single .select2-selection__rendered {
  border-color: #ebf1f6;
  color: #5A6A85;
  height: 40px;
  line-height: 40px;
}

.select2-container--default .select2-selection--multiple {
  line-height: 27px;
  height: auto;
}

.select2-container--classic .select2-selection--multiple .select2-selection__choice,
.select2-container--default .select2-selection--multiple .select2-selection__choice,
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
  background-color: #539BFF;
  border-color: #539BFF;
  color: #fff;
}

    .card {
        margin-bottom: 20px; /* Add margin between cards */
    }


textarea{
    display: block;
    resize: none;
    padding: 20px;
    color: black;
    border: 2px solid #3ba51f;
    border-radius: 50px;
    background-color:black;
    outline: none;
}
#wifi-loader {
  --background: #62abff;
  --front-color: #4f29f0;
  --back-color: #c3c8de;
  --text-color: #414856;
  width: 64px;
  height: 64px;
  border-radius: 50px;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}

#wifi-loader svg {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
}

#wifi-loader svg circle {
  position: absolute;
  fill: none;
  stroke-width: 6px;
  stroke-linecap: round;
  stroke-linejoin: round;
  transform: rotate(-100deg);
  transform-origin: center;
}

#wifi-loader svg circle.back {
  stroke: var(--back-color);
}

#wifi-loader svg circle.front {
  stroke: var(--front-color);
}

#wifi-loader svg.circle-outer {
  height: 86px;
  width: 86px;
}

#wifi-loader svg.circle-outer circle {
  stroke-dasharray: 62.75 188.25;
}

#wifi-loader svg.circle-outer circle.back {
  animation: circle-outer135 1.8s ease infinite 0.3s;
}

#wifi-loader svg.circle-outer circle.front {
  animation: circle-outer135 1.8s ease infinite 0.15s;
}

#wifi-loader svg.circle-middle {
  height: 60px;
  width: 60px;
}

#wifi-loader svg.circle-middle circle {
  stroke-dasharray: 42.5 127.5;
}

#wifi-loader svg.circle-middle circle.back {
  animation: circle-middle6123 1.8s ease infinite 0.25s;
}

#wifi-loader svg.circle-middle circle.front {
  animation: circle-middle6123 1.8s ease infinite 0.1s;
}

#wifi-loader svg.circle-inner {
  height: 34px;
  width: 34px;
}

#wifi-loader svg.circle-inner circle {
  stroke-dasharray: 22 66;
}

#wifi-loader svg.circle-inner circle.back {
  animation: circle-inner162 1.8s ease infinite 0.2s;
}

#wifi-loader svg.circle-inner circle.front {
  animation: circle-inner162 1.8s ease infinite 0.05s;
}

#wifi-loader .text {
  position: absolute;
  bottom: -40px;
  display: flex;
  justify-content: center;
  align-items: center;
  text-transform: lowercase;
  font-weight: 500;
  font-size: 14px;
  letter-spacing: 0.2px;
}

#wifi-loader .text::before, #wifi-loader .text::after {
  content: attr(data-text);
}

#wifi-loader .text::before {
  color: var(--text-color);
}

#wifi-loader .text::after {
  color: var(--front-color);
  animation: text-animation76 3.6s ease infinite;
  position: absolute;
  left: 0;
}

@keyframes circle-outer135 {
  0% {
    stroke-dashoffset: 25;
  }

  25% {
    stroke-dashoffset: 0;
  }

  65% {
    stroke-dashoffset: 301;
  }

  80% {
    stroke-dashoffset: 276;
  }

  100% {
    stroke-dashoffset: 276;
  }
}

@keyframes circle-middle6123 {
  0% {
    stroke-dashoffset: 17;
  }

  25% {
    stroke-dashoffset: 0;
  }

  65% {
    stroke-dashoffset: 204;
  }

  80% {
    stroke-dashoffset: 187;
  }

  100% {
    stroke-dashoffset: 187;
  }
}

@keyframes circle-inner162 {
  0% {
    stroke-dashoffset: 9;
  }

  25% {
    stroke-dashoffset: 0;
  }

  65% {
    stroke-dashoffset: 106;
  }

  80% {
    stroke-dashoffset: 97;
  }

  100% {
    stroke-dashoffset: 97;
  }
}

@keyframes text-animation76 {
  0% {
    clip-path: inset(0 100% 0 0);
  }

  50% {
    clip-path: inset(0);
  }

  100% {
    clip-path: inset(0 0 0 100%);
  }
}
 
    </style>
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
            $("#buy-number").addClass("active");
        });
    </script>
      <div class="contents">

         <div class="container-fluid">
            <div class="social-dash-wrap"><br>
               <div class="row">
                   <div class="col-lg-12">
                           <div class="card card-horizontal card-default card-md mb-4">
                              <div class="card-header">
                                 <h6>Buy Number</h6>
                              </div>
                              <div id="otp-main" class="card-body py-md-30">
                                 <div class="mb-15 select-style2">
                                <input type="hidden" id="server_no" value=""> 
                                 <input type="hidden" id="token" value="<?php echo $_SESSION['token'];?>">
                                    <div class="dm-select ">

                                    <select class="custom-select" id="server-id">
                                    <option value="">SELECT SERVER</option>
                                   <?php
                                    foreach($server as $servers){
                                        echo "<option value=".$servers['id'].">".$servers['server_name']."</option>";
                                    }
                                    ?>
                                       </select>
                                    </div>

                                 </div>
                                 <div class="select-style2">

                                    <div class="dm-select ">

                                       <select  id="service-id" class="form-control selecte2" >
                                           <option value="">SELECT SERVICE</option>   
                                        </select>

                                    </div>
                                 </div><br>
                                   <center><button class="btn btn-secondary btn-default btn-rounded btn-shadow-secondary btn-sm btn-block " id="buy-numbers"> <span class="fa fa-cart-plus"></span>Buy</button></center>
                              </div>
                           </div>

<div id="card-container">
    <!-- Cards will be dynamically added here -->
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
    $(document).ready(function() {
        // Initialize Select2
        $('select').select2();
    });
</script>



 <script src="js/main.js?v=1"></script>
  <script type="module" src="js/sms.js?v=133143759943"></script>
  



</body>

</html>