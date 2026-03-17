<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport"
            content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="theme-color" content="#000000">
        <base href="<?php echo rtrim(WEBSITE_URL, '/'); ?>/">
        <title><?php echo $page_name;?> -  <?php echo $web_name;?></title>
        <link rel="icon" type="image/png" href="logo.jpeg" sizes="32x32">
        <link rel="apple-touch-icon" sizes="180x180" href="logo.jpeg">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Bootstrap Icons -->
         <link rel='stylesheet' type='text/css' media='screen' href='<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/css/src/bootstrap-icon/font/bootstrap-icons.css'>
        <!-- Notyf CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
        <!-- Notyf JS -->
        <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
        <!-- Remix CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" integrity="sha512-XcIsjKMcuVe0Ucj/xgIXQnytNwBttJbNjltBV18IOnru2lDPe9KRRyvCXw6Y5H415vbBLRm8+q6fmLUU7DfO6Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel='stylesheet' type='text/css' media='screen' href='<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/css/style.css?v=453789'>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!--<script id="chatway" async="true" src="https://cdn.chatway.app/widget.js?id=JPvOvpNBmuuR"></script>-->
        <style>
            /* Telegram Support */
            .telegram-float {
              position: fixed;
              bottom: 80px;
              right: 20px;
              z-index: 1000;
            }
            
            .telegram-icon {
              width: 60px;
              height: 60px;
              border-radius: 50%;
              background-color: #0088cc;
              display: flex;
              justify-content: center;
              align-items: center;
              animation-name: pulse;
            	animation-duration: 1.5s;
            	animation-timing-function: ease-out;
            	animation-iteration-count: infinite;
            }
            
            @keyframes pulse {
            	0% {
            		box-shadow: 0 0 0 0 rgba(0, 136, 204, 0.5);
            	}
            	80% {
            		box-shadow: 0 0 0 14px rgba(0, 136, 204, 0);
            	}
            }
            
            .telegram-icon svg {
              fill: #fff;
              width: 30px;
              height: 30px;
            }
        </style>
    </head>
    <body>
