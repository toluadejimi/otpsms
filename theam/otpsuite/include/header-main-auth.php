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
            /* Page level modifiers */
           
            .login-page {
                min-height: 100vh
            }

            .login-page .card {
                max-width: 450px;
            }

            .form-group .form-label{
                font-size: 0.8rem;
                margin-bottom: 0.2rem;
            }

            .input-wrapper{
                width: 100%;
                background: #fff;
                color: #888;
                padding: 5px;
                border: 1px solid #D1D5DC;
                border-radius: 10px;
                height: 43px;
                display: flex;
                padding: 10px 0;
                align-items: center;
            }

            .input-wrapper i{
                margin-left: 11px;
                margin-right: 11px;
                font-size: 17px;
                color: #0004;
            }
            .input-wrapper input::placeholder {
                color: #0003;
            }
            .input-wrapper input{
                border: none;
                outline: none;
                color: #000;
                font-size: 12px;
                padding: 10px 0;
                height: 100%;
                background: transparent;
                font-family: var(--font);
                width: 100%;
            }

            @media(max-width: 991px) {
                .login-page .card {
                    width:100%;
                    padding-left: 0px !important;
                    padding-right: 0px !important
                }
            }

            body.dark-mode .input-wrapper{
                background: transparent;
            }

            body.dark-mode .input-wrapper input,
            body.dark-mode .input-wrapper input::placeholder{
                color: #fff;
            }

            body.dark-mode .input-wrapper i{
                color: #fff;
            }

            body.dark-mode .form-control{
                background-color: transparent;
            }
            
        </style>
    </head>
    <body>
