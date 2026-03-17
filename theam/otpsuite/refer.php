<?php
$page_name = "Dashboard";
include 'include/header-main.php';
?>
<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md hydrated"></i>
                </a>
                <div class="pageTitle">Refer Earn</div>
            </div>
            <div class="right">
            </div>
        </div>
    </div>

    <div id="appCapsule">
        <div class="section p-0 mt-2 mb-2">
            <div class="mb-1 px-4">
                <img src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/image/refer-earn.png" alt="image" class="imaged img-fluid">
            </div>

            <div class="input-group my-3">
                <input id="upi_copy" type="text" class="form-control" readonly value="<?php echo $site_data['web_url']; ?>/register?ref_id=<?php echo $referwallet['own_code']; ?>">
                <button class="btn btn-primary" type="button" id="copyBtn"><i class="fa fa-clipboard"></i></button>
            </div>
        </div>

         <!-- Services -->
        <div class="section services-section mt-4 p-0">
            <div class="section-heading">
                <h2 class="title">Invite</h2>
            </div>

            <div class="refer-social-links d-flex justify-content-between my-4">
                <div class="item">
                    <div class="img-wrapper">
                        <img src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/image/facebook.png" alt="img" class="image-block imaged w48">
                    </div>
                    <strong>Facebook</strong>
                </div>

                <div class="item">
                    <div class="img-wrapper">
                        <img src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/image/whatsapp.png" alt="img" class="image-block imaged w48">
                    </div>
                    <strong>Whatsapp</strong>
                </div>
            
                <div class="item">
                    <div class="img-wrapper">
                        <img src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/image/mail.png" alt="img" class="image-block imaged w48">
                    </div>
                    <strong>Mail</strong>
                </div>
            
                <div class="item">
                    <div class="img-wrapper">
                        <img src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/image/link.png" alt="img" class="image-block imaged w48">
                    </div>
                    <strong>Link</strong>
                </div>
            </div>
        </div>
        <!-- * Services -->

        <!-- Refer nstructions -->
        <div class="refer-instructions m-t">
            <h4>Instructions</h4>
            <ul class="list-group list-group-flush text-start">
                <li class="list-group-item">1. Copy and share your link.</li>
                <li class="list-group-item">2. When your friend registers and deposits ₦1,000 or more.</li>
                <li class="list-group-item">3. You receive ₦<?php echo number_format($site_data['refer_amount'], 0); ?> in your refer wallet.</li>
                <li class="list-group-item">4. Use that balance directly to buy numbers.</li>
            </ul>
        </div>

        <!-- Summary -->
        <div class="section mt-4 p-0">
            <div class="section-heading">
                <h2 class="title">Refer Data</h2>
            </div>
            <div class="summary">
                <div class="item">
                    <div class="detail">
                        <div class="icon-wrapper bg-pink-light">
                            <i class="ri-shopping-bag-4-line"></i>
                        </div>
                        <div>
                            <strong>Total Reffered User</strong>
                            <p><?php echo $refer_users;?></p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="detail">
                        <div class="icon-wrapper bg-primary-light">
                            <i class="ri-shopping-bag-4-line"></i>
                        </div>
                        <div>
                            <strong>Total Transfer Amount</strong>
                            <p>₦<?php echo number_format($referwallet['transfer']);?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Summary -->
    </div>

    <?php
        include("include/bottom-menu.php")
    ?>
</div>
<script>
    const toast = new Notyf({
        position: {x:'right',y:'top'}
    });

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
            toast.success("Refer Link Copied");

            this.classList.add("ticked");

            // Remove the class after a short delay to reset the animation
            setTimeout(() => {
                this.classList.remove("ticked");
            }, 500);
            });
        }
    });
</script>  
<?php
    include 'include/footer-main.php';
?>