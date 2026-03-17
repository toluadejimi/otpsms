<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title><?php echo $page_name;?> -  <?php echo $web_name;?></title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="icon" type="image/x-icon" href="favicon.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel='stylesheet' type='text/css' media='screen' href='<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/css/perfect-scrollbar.min.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/css/style.css'>
    <link defer rel='stylesheet' type='text/css' media='screen' href='<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/css/animate.css'>
    <script src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/perfect-scrollbar.min.js"></script>
    <script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/popper.min.js"></script>
    <script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/tippy-bundle.umd.min.js"></script>
    <script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script id="chatway" async="true" src="https://cdn.chatway.app/widget.js?id=WuTH6lboC6D3"></script>
      
<?php include "loader.php";?>
<style>
button {
  max-width: 320px;
  display: flex;
  padding: 0.5rem 1.4rem;
  font-size: 0.875rem;
  line-height: 1.25rem;
  font-weight: 700;
  text-align: center;
  text-transform: uppercase;
  vertical-align: middle;
  align-items: center;
  border-radius: 0.5rem;
  border: 1px solid rgba(0, 0, 0, 0.25);
  gap: 0.75rem;
  color: rgb(65, 63, 63);
  background-color: #fff;
  cursor: pointer;
  transition: all .6s ease;
}

.button svg {
  height: 24px;
}

button:hover {
  transform: scale(1.02);
}
</style>
</head>


<body x-data="main" class="antialiased relative font-nunito text-sm font-normal overflow-x-hidden" :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme, $store.app.menu, $store.app.layout,$store.app.rtlClass]">

    <!-- screen loader -->
    <div class="screen_loader fixed inset-0 bg-[#fafafa] dark:bg-[#060818] z-[60] grid place-content-center animate__animated">
      <div class="loader"></div>
    </div>

    <div class="fixed bottom-6 right-6 z-50" x-data="scrollToTop">
        <template x-if="showTopButton">
            <button type="button" class="btn btn-outline-primary rounded-full p-2 animate-pulse" @click="goToTop">
                <svg width="24" height="24" class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M12 20.75C12.4142 20.75 12.75 20.4142 12.75 20L12.75 10.75L11.25 10.75L11.25 20C11.25 20.4142 11.5858 20.75 12 20.75Z" fill="currentColor" />
                    <path d="M6.00002 10.75C5.69667 10.75 5.4232 10.5673 5.30711 10.287C5.19103 10.0068 5.25519 9.68417 5.46969 9.46967L11.4697 3.46967C11.6103 3.32902 11.8011 3.25 12 3.25C12.1989 3.25 12.3897 3.32902 12.5304 3.46967L18.5304 9.46967C18.7449 9.68417 18.809 10.0068 18.6929 10.287C18.5768 10.5673 18.3034 10.75 18 10.75L6.00002 10.75Z" fill="currentColor" />
                </svg>
            </button>
        </template>
    </div>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("scrollToTop", () => ({
                showTopButton: false,
                init() {
                    window.onscroll = () => {
                        this.scrollFunction();
                    };
                },

                scrollFunction() {
                    if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                        this.showTopButton = true;
                    } else {
                        this.showTopButton = false;
                    }
                },

                goToTop() {
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                },
            }));
        });
    </script>

    <div class="main-container text-black dark:text-white-dark min-h-screen">
