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
                <div class="pageTitle">Bill Payments</div>
            </div>
            <div class="right">
            </div>
        </div>
    </div>

    <div id="appCapsule">
        <ul class="listview image-listview">
            <li>
                <a href="airtime" class="item">
                    <div class="in">
                        <div>
                            <strong>Airtime</strong>
                            <p class="my-2">Buy Fast Airtime for your use.</p>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="data" class="item">
                    <div class="in">
                        <div>
                            <strong>Data</strong>
                            <p class="my-2">Buy Data for your use.</p>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <?php
        include("include/bottom-menu.php")
    ?>
</div>
<?php
    include 'include/footer-main.php';
?>