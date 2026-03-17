<?php
$page_name = "Dashboard";
include 'include/header-main.php';
?>
<style>
    .avatar-initials {
        flex: 0 0 48px;
        height: 48px;
        margin-right: 15px;
        border-radius: 100%;
        display: inline-flex;
        align-items: center;
        font-weight: bold;
        justify-content: center;
        color: #FF6D00;
        background-color: #FF6D0033;
        font-size: 28px;
    }
</style>
<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md hydrated"></i>
                </a>
                <div class="pageTitle">Profile</div>
            </div>
            <div class="right">
            </div>
        </div>
    </div>

    <div id="appCapsule">
        <ul class="mb-3 listview image-listview border-0">
            <li>
                <a href="profile" class="item">
                    <div class="avatar avatar-initials">
                        <?= strtoupper(substr($userdata['name'],0,1)) ?>
                    </div>
                    <div class="in">
                        <div><?=  $userdata['name'] ?></div>
                    </div>
                </a>
            </li>
        </ul>

        <ul class="listview image-listview">
            <li>
                <a href="refer" class="item">
                    <div class="icon-box">
                        <i class="ri-vip-crown-2-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Refer & Earn</div>
                    </div>
                </a>
            </li>
            <?php
                if($userdata['type']=="admin"){
            ?>
            <li>
                <a href="radium_sahil_op_786_12" class="item">
                    <div class="icon-box">
                        <i class="ri-vip-crown-2-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Admin</div>
                    </div>
                </a>
            </li>
            <?php
                }
            ?>
            <li>
                <a href="airtime-history" class="item">
                    <div class="icon-box">
                        <i class="ri-book-marked-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Airime History</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="data-history" class="item">
                    <div class="icon-box">
                        <i class="ri-book-marked-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Data History</div>
                    </div>
                </a>
            </li>
            <!--<li>-->
            <!--    <a href="cable-history" class="item">-->
            <!--        <div class="icon-box">-->
            <!--            <i class="ri-book-marked-line md hydrated"></i>-->
            <!--        </div>-->
            <!--        <div class="in">-->
            <!--            <div>Cable History</div>-->
            <!--        </div>-->
            <!--    </a>-->
            <!--</li>-->
            <!--<li>-->
            <!--    <a href="electricity-history" class="item">-->
            <!--        <div class="icon-box">-->
            <!--            <i class="ri-book-marked-line md hydrated"></i>-->
            <!--        </div>-->
            <!--        <div class="in">-->
            <!--            <div>Electricity History</div>-->
            <!--        </div>-->
            <!--    </a>-->
            <!--</li>-->
            <li>
                <a href="numbers" class="item">
                    <div class="icon-box">
                        <i class="ri-book-marked-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Number History</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="log-orders" class="item">
                    <div class="icon-box">
                        <i class="ri-book-marked-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Logs History</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="transactions" class="item">
                    <div class="icon-box">
                        <i class="ri-book-marked-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Transaction History</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="boosting-orders" class="item">
                    <div class="icon-box">
                        <i class="ri-book-marked-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Boosting History</div>
                    </div>
                </a>
            </li>
             <li>
                <a href="video-tutorials" class="item">
                    <div class="icon-box">
                        <i class="ri-vidicon-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Video Tutorials</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="https://whatsapp.com/channel/0029VbB96hpAO7RJnV3xYa2K" class="item">
                    <div class="icon-box">
                        <i class="ri-customer-service-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Contact Us</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="https://whatsapp.com/channel/0029VbB96hpAO7RJnV3xYa2K" class="item">
                    <div class="icon-box">
                       <i class="ri-user-community-line md hydrated"></i>
                    </div>
                    <div class="in">
                        <div>Join Channel</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#DialogLogoutConfirmation">
                    <div class="icon-box">
                        <ion-icon name="log-out-outline" role="img" class="md hydrated"></ion-icon>
                    </div>
                    <div class="in">
                        <div>Log Out</div>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <!-- DialogIconedInfo -->
    <div class="modal fade dialogbox" id="DialogLogoutConfirmation" data-bs-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog logout" role="document">
            <div class="modal-content">
                <div class="modal-icon">
                    <ion-icon name="exit-outline"></ion-icon>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure you want to logout?</h5>
                </div>
                <div class="modal-body">
                    While logged out, you may miss critical notifications and reminders.
                </div>
                <div class="modal-footer">
                    <a href="logout" class="btn btn-primary">
                        Log Out
                    </a>
                    <a href="#" class="btn" data-bs-dismiss="modal">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- * DialogIconedInfo -->

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