<?php
    $uri = $_SERVER["REQUEST_URI"];
?>
<a href="https://t.me/otpsuite" target="_blank" class="telegram-float">
	<div class="telegram-icon">
		<svg viewBox="0 0 64 64"><path d="M56.4,8.2l-51.2,20c-1.7,0.6-1.6,3,0.1,3.5l9.7,2.9c2.1,0.6,3.8,2.2,4.4,4.3l3.8,12.1c0.5,1.6,2.5,2.1,3.7,0.9 l5.2-5.3c0.9-0.9,2.2-1,3.2-0.3l11.5,8.4c1.6,1.2,3.9,0.3,4.3-1.7l8.7-41.8C60.4,9.1,58.4,7.4,56.4,8.2z M50,17.4L29.4,35.6 c-1.1,1-1.9,2.4-2,3.9c-0.2,1.5-2.3,1.7-2.8,0.3l-0.9-3c-0.7-2.2,0.2-4.5,2.1-5.7l23.5-14.6C49.9,16.1,50.5,16.9,50,17.4z"></path></svg>
    </div>
</a>
<!-- App Bottom Menu -->
<div class="appBottomMenu">
    <a href="dashboard" class="item <?= $uri === "/dashboard"? "active" : "" ?>">
        <div class="col">
            <i class="ri-home-5-fill icon"></i>
            <strong>Home</strong>
        </div>
    </a>
    <a href="bill-payments" class="item <?= $uri === "/bill-payments"? "active" : "" ?>">
        <div class="col">
            <i class="ri-store-2-line icon"></i>
            <strong>Bill Payments</strong>
        </div>
    </a>
    <a href="buy-logs" class="item <?= $uri === "/buy-logs"? "active" : "" ?>">
        <div class="col">
            <i class="ri-receipt-line icon"></i>
            <strong>Logs</strong>
        </div>
    </a>
    <a href="recharge" class="item <?= $uri === "/recharge"? "active" : "" ?>">
        <div class="col">
            <i class="ri-smartphone-line icon"></i>
            <strong>Recharge</strong>
        </div>
    </a>
    <a href="profile-main" class="item <?= $uri === "/profile-main"? "active" : "" ?>">
        <div class="col">
            <i class="ri-layout-2-fill icon"></i>
            <strong>Profile</strong>
        </div>
    </a>
</div>
<!-- * App Bottom Menu -->