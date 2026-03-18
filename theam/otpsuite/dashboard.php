<?php
$page_name = "Dashboard";
include 'include/header-main.php';
?>
<style>
    .avatar-initials {
        width: 48px;
        height: 48px;
        border-radius: 100%;
        display: inline-flex;
        align-items: center;
        font-weight: bold;
        justify-content: center;
        color: #FF6D00;
        background-color: #FF6D0033;
        font-size: 28px;
    }

    /* Modern dashboard styling */
    :root{
        --ds-bg: #f6f7fb;
        --ds-card: #ffffff;
        --ds-border: rgba(0,0,0,.06);
        --ds-border-strong: rgba(0,0,0,.08);
        --ds-border-card: rgba(0,0,0,.07);
        --ds-text: #111827;
        --ds-muted: #6b7280;
        --ds-darkA: #1b2330;
        --ds-darkB: #121826;
        --ds-pill: #f3f4f6;
        --ds-success-bg: #e9f7ef;
        --ds-success: #116b3d;
        --ds-warning-bg: #fff7ed;
        --ds-warning: #9a3412;
        --ds-danger-bg: #fef2f2;
        --ds-danger: #991b1b;
    }
    #appCapsule{ background: var(--ds-bg); }
    .ds-card{
        background: var(--ds-card);
        border: 1px solid var(--ds-border);
        border-radius: 18px;
        box-shadow: 0 1px 0 rgba(0,0,0,.03);
        overflow: hidden;
    }
    .ds-section-head{
        display:flex; align-items:center; justify-content: space-between;
        padding: 12px 12px;
        background: linear-gradient(180deg, var(--ds-darkA) 0%, var(--ds-darkB) 100%);
        color:#fff;
    }
    .ds-section-title{
        display:flex; align-items:center; gap:10px;
        font-weight: 800; letter-spacing: .4px;
        text-transform: uppercase;
        font-size: 12px;
        margin:0;
        color:#fff !important;
    }
    .ds-section-icon{
        width: 26px; height: 26px; border-radius: 10px;
        display:grid; place-items:center;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.18);
        font-weight: 900;
    }
    .ds-section-action{
        border: 1px solid rgba(255,255,255,.18);
        background: rgba(255,255,255,.08);
        color:#fff;
        padding: 7px 10px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 11px;
        text-decoration:none;
    }
    .ds-body{ padding: 12px; }
    .tx-list{ display:flex; flex-direction:column; gap:10px; }
    .tx-item{
        display:flex; align-items:center; justify-content: space-between; gap:12px;
        background:#fff;
        border: 1px solid var(--ds-border-card);
        border-radius: 16px;
        padding: 10px;
        text-decoration:none;
        color: var(--ds-text);
    }
    .tx-left{ display:flex; align-items:center; gap:12px; min-width:0; }
    .tx-avatar{
        width: 46px; height: 46px; border-radius: 16px;
        display:grid; place-items:center;
        background: radial-gradient(circle at 30% 20%, rgba(67,97,238,.18), rgba(0,0,0,0));
        border: 1px solid var(--ds-border);
        flex: 0 0 auto;
        color: #1b2330;
        font-weight: 900;
        font-size: 14px;
    }
    .tx-meta{ min-width:0; }
    .tx-title{ margin:0; font-weight: 800; font-size: 13px; color: var(--ds-text); }
    .tx-sub{ margin:2px 0 0 0; font-size: 11px; color: var(--ds-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 210px; }
    .tx-right{ display:flex; flex-direction:column; align-items:flex-end; gap:6px; flex:0 0 auto; }
    .tx-amount{ font-weight: 900; font-size: 12px; color: var(--ds-success); background: var(--ds-success-bg); border: 1px solid rgba(17,107,61,.18); padding: 5px 10px; border-radius: 999px; }
    .tx-amount.debit{ color: var(--ds-danger); background: var(--ds-danger-bg); border-color: rgba(153,27,27,.18); }
    .tx-status{ font-weight: 800; font-size: 11px; padding: 4px 10px; border-radius: 999px; border: 1px solid var(--ds-border-strong); background: var(--ds-pill); color: var(--ds-text); }
    .tx-status.success{ background: var(--ds-success-bg); color: var(--ds-success); border-color: rgba(17,107,61,.18); }
    .tx-status.pending{ background: var(--ds-warning-bg); color: var(--ds-warning); border-color: rgba(154,52,18,.18); }
    .tx-status.cancelled{ background: var(--ds-danger-bg); color: var(--ds-danger); border-color: rgba(153,27,27,.18); }
    .ds-activities-table{
        width:100%;
        border-collapse:separate;
        border-spacing:0;
    }
    .ds-activities-table th{
        text-align:left;
        font-size:11px;
        color: var(--ds-muted);
        font-weight:800;
        padding: 10px 10px;
        border-bottom: 1px solid var(--ds-border-card);
    }
    .ds-activities-table td{
        font-size:12px;
        padding: 10px 10px;
        border-bottom: 1px solid rgba(0,0,0,.04);
        color: var(--ds-text);
        vertical-align:middle;
    }
    body.dark-mode .ds-activities-table td{
        border-bottom: 1px solid rgba(255,255,255,.08);
    }

    /* Recent Activity feed (home page) */
    .ra-header{
        background: linear-gradient(90deg, #6D28D9 0%, #7C3AED 55%, #5B21B6 100%);
        color:#fff;
        padding: 14px 14px;
        display:flex;
        align-items:center;
        gap:12px;
        border-radius: 16px;
        margin-bottom: 12px;
    }
    .ra-header-icon{
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: rgba(255,255,255,.14);
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:900;
    }
    .ra-header-title{ font-size: 14px; font-weight: 900; line-height:1.1; }
    .ra-header-sub{ font-size: 11px; color: rgba(255,255,255,.85); margin-top: 2px; }

    .ra-item{
        display:flex;
        align-items:center;
        justify-content: space-between;
        gap: 12px;
        background: rgba(255,255,255,.65);
        border: 1px solid var(--ds-border-card);
        border-radius: 16px;
        padding: 12px 12px;
        margin-bottom: 10px;
    }
    body.dark-mode .ra-item{
        background: rgba(255,255,255,.04);
    }
    .ra-left{
        display:flex;
        align-items:center;
        gap:12px;
        min-width: 0;
    }
    .ra-icon{
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display:flex;
        align-items:center;
        justify-content:center;
        flex: 0 0 auto;
        font-weight:900;
        border: 1px solid rgba(255,255,255,.12);
    }
    .ra-icon.order{
        background: rgba(99,102,241,.18);
        color: #A5B4FC;
        border-color: rgba(99,102,241,.30);
    }
    .ra-icon.deposit{
        background: rgba(16,185,129,.16);
        color: #34D399;
        border-color: rgba(16,185,129,.25);
    }
    .ra-center{ min-width:0; }
    .ra-user{
        font-size: 13px;
        font-weight: 900;
        color: var(--ds-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    body.dark-mode .ra-user{ color: var(--ds-text); }
    .ra-desc{
        margin-top: 4px;
        font-size: 11px;
        color: var(--ds-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 220px;
    }
    .ra-right{
        display:flex;
        flex-direction:column;
        align-items:flex-end;
        gap: 8px;
        flex: 0 0 auto;
    }
    .ra-badge{
        display:flex;
        align-items:center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 900;
        border: 1px solid var(--ds-border-card);
        background: var(--ds-pill);
        color: var(--ds-text);
    }
    .ra-badge.order{
        background: rgba(99,102,241,.18);
        border-color: rgba(99,102,241,.30);
        color: #A5B4FC;
    }
    .ra-badge.deposit{
        background: rgba(16,185,129,.16);
        border-color: rgba(16,185,129,.25);
        color: #34D399;
    }
    .ra-time{
        font-size: 11px;
        color: var(--ds-muted);
        white-space: nowrap;
    }

    /* Dark mode overrides (theme uses body.dark-mode) */
    body.dark-mode{
        --ds-bg: #0b0f1a;
        --ds-card: #0f172a;
        --ds-border: rgba(255,255,255,.08);
        --ds-border-card: rgba(255,255,255,.10);
        --ds-border-strong: rgba(255,255,255,.12);
        --ds-text: rgba(255,255,255,.92);
        --ds-muted: rgba(255,255,255,.62);
        --ds-darkA: #0b1220;
        --ds-darkB: #060a12;
        --ds-pill: rgba(255,255,255,.08);
        --ds-success-bg: rgba(17,107,61,.18);
        --ds-success: #6ee7b7;
        --ds-warning-bg: rgba(154,52,18,.18);
        --ds-warning: #fdba74;
        --ds-danger-bg: rgba(153,27,27,.18);
        --ds-danger: #fca5a5;
    }
    body.dark-mode .tx-item{ background: var(--ds-card); }
    .broadcast-message { word-break: break-word; max-height: 60vh; overflow-y: auto; }
    body.dark-mode .tx-avatar{ color: rgba(255,255,255,.9); background: radial-gradient(circle at 30% 20%, rgba(99,102,241,.22), rgba(0,0,0,0)); }
    body.dark-mode .ds-card{ box-shadow: 0 1px 0 rgba(0,0,0,.25); }
</style>
<div id="app">
    <div class="container-fluid p-0">
        <!-- App Header -->
        <div class="appHeader">
            <div class="left">
                <div class="avatar avatar-initials">
                    <?= strtoupper(substr($userdata['name'],0,1)) ?>
                </div>
                <div class="welcome-mesg">
                    <span class="text-muted">Good Morning,</span>
                    <span class="text-dark fw-bold"><?php echo $userdata['name']; ?> 👋🏻</span>
                </div>
            </div>
            <div class="right">
                <button class="headerButton" id="themeToggle">
                    <ion-icon class="icon" name="moon-outline"></ion-icon>
                </button>
                <a href="notifications" class="headerButton notification">
                    <ion-icon class="icon" name="notifications-outline"></ion-icon>
                    <?php if($unread_notifications > 0): ?>
                        <span class="badge badge-danger">
                            <?= $unread_notifications > 99 ? '99+' : $unread_notifications ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        <!-- * App Header -->
        <div id="appCapsule">
             <input type="hidden" id="token" value="<?= $_SESSION['token']?>">
            <!-- Wallet -->
            <div class="section full gradientSection">
                <div class="in">
                    <h5 class="title mb-2">Main Balance</h5>
                    <div class="wallet-inline-amount">
                        <h1 class="total" data-visible="true">
                            ₦<?php echo number_format($data['balance'], 2);?>
                        </h1>
                        <h1 class="balance-hidden" style="display: none;">****</h1>
                        <span class="balance-icon" onclick="toggleBalance(this)"><i class="bi bi-eye-slash"></i></span>
                    </div>
                    <div class="wallet-inline-button mt-5">
                        <a href="recharge" class="item">
                            <div class="iconbox">
                                <ion-icon name="wallet"></ion-icon>
                            </div>
                            <strong>Add Wallet</strong>
                        </a>
                        <a href="transactions" class="item">
                            <div class="iconbox">
                               <ion-icon name="sync-circle"></ion-icon>
                            </div>
                            <strong>View History</strong>
                        </a>
                        <a href="buy-usa-only-numbers" class="item">
                            <div class="iconbox">
                                <ion-icon name="add-circle"></ion-icon>
                            </div>
                            <strong>Buy Number</strong>
                        </a>
                        <a href="https://whatsapp.com/channel/0029VbB96hpAO7RJnV3xYa2K" class="item">
                            <div class="iconbox">
                                <ion-icon name="paper-plane"></ion-icon>
                            </div>
                            <strong>Join Channel</strong>
                        </a>
                    </div>
                </div>
            </div>
            <!-- * Wallet -->

            <!-- Services -->
            <div class="section services-section mt-4 p-0">
                <div class="section-heading">
                    <h2 class="title">Quick Services</h2>
                    <span id="see-all-services" class="text-primary">See All</span>
                </div>

                <div class="row mt-4">
                    <div class="col-3">
                        <div class="item">
                            <a href="airtime">
                                <div class="icon-wrapper">
                                    <i class="ri-phone-line"></i>
                                </div>
                                <strong class="icon-title">Airtime</strong>
                            </a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="item">
                            <a href="data">
                                <div class="icon-wrapper">
                                    <i class="ri-wifi-line"></i>
                                </div>
                                <strong class="icon-title">Data</strong>
                            </a>
                        </div>
                    </div>
                    <!-- <div class="col-3">
                        <div class="item">
                            <a href="cable">
                                <div class="icon-wrapper">
                                    <i class="ri-mac-line"></i>
                                </div>
                                <strong class="icon-title">Cable</strong>
                            </a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="item">
                             <a href="electricity">
                                <div class="icon-wrapper">
                                    <i class="ri-lightbulb-flash-line"></i>
                                </div>
                                <strong class="icon-title">Electricity</strong>
                            </a>
                        </div>
                    </div> -->
                    <div class="col-3">
                        <div class="item">
                            <a href="buy-usa-only-numbers">
                                <div class="icon-wrapper">
                                    <i class="ri-mail-line"></i>
                                </div>
                                <strong class="icon-title">Numbers</strong>
                            </a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="item">
                            <a href="buy-logs">
                                <div class="icon-wrapper">
                                    <i class="ri-shield-user-line"></i>
                                </div>
                                <strong class="icon-title">Logs</strong>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-3">
                        <div class="item">
                            <a href="boost-socials">
                                <div class="icon-wrapper">
                                    <i class="ri-megaphone-line"></i>
                                </div>
                                <strong class="icon-title">Boosting</strong>
                            </a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="item">
                            <span class="badge badge-danger">New</span>
                            <a href="buy-telegram-products">
                                <div class="icon-wrapper">
                                    <i class="ri-verified-badge-fill"></i>
                                </div>
                                <strong class="icon-title">Telegram Blue Tick</strong>
                            </a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="item">
                            <a href="refer">
                                <div class="icon-wrapper">
                                    <i class="ri-user-add-line"></i>
                                </div>
                                <strong class="icon-title">Refer</strong>
                            </a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="item">
                            <a href="video-tutorials">
                                <div class="icon-wrapper">
                                    <i class="ri-vidicon-line"></i>
                                </div>
                                <strong class="icon-title">Video Tutorials</strong>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row mt-4 hidden extra-services">
                    <div class="col-3">
                        <a href="https://t.me/otpsuite">
                            <div class="item">
                                <div class="icon-wrapper">
                                    <i class="ri-customer-service-2-line"></i>
                                </div>
                                <strong class="icon-title">Contact Us</strong>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <div class="item">
                            <a href="https://whatsapp.com/channel/0029VbB96hpAO7RJnV3xYa2K">
                                <div class="icon-wrapper">
                                    <i class="ri-contract-right-fill"></i>
                                </div>
                                <strong class="icon-title">Join Us</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- * Services -->

            <!-- Summary -->
            <div class="section mt-4 p-0">
                <div class="section-heading">
                    <h2 class="title">Summary</h2>
                </div>
                <div class="summary">
                    <div class="item">
                        <div class="detail">
                            <div class="icon-wrapper bg-pink-light">
                                <i class="ri-shopping-bag-4-line"></i>
                            </div>
                            <div>
                                <strong>Recharge Life Time</strong>
                                <p>₦<?php echo number_format($data['total_recharge']);?></p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="detail">
                            <div class="icon-wrapper bg-primary-light">
                                <i class="ri-shopping-bag-4-line"></i>
                            </div>
                            <div>
                                <strong>Number Purcahsed - Life Time</strong>
                                <p><?php echo $data['total_otp'];?></p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="detail">
                            <div class="icon-wrapper bg-warning-light">
                                <i class="ri-shopping-bag-4-line text-warning"></i>
                            </div>
                            <div>
                                <strong>Refer Balance</strong>
                                <p>₦<?php echo number_format($referwallet['balance']);?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Summary -->
            <?php ob_start(); ?>

            <!-- Recent Activities -->
            <div class="section mt-4 p-0">
                <div class="ds-card">
                    <div class="ds-body">
                        <?php
                            $raEvents = is_array($recent_activities ?? null) ? $recent_activities : [];

                            $maskUser = function($name){
                                $name = trim((string)$name);
                                if ($name === '') return 'User***';
                                $len = strlen($name);
                                if ($len <= 2) return substr($name, 0, 1) . '***';
                                if ($len <= 4) return substr($name, 0, 1) . '***' . substr($name, -1);
                                return substr($name, 0, 2) . '***' . substr($name, -1);
                            };

                            $timeAgo = function($dateStr){
                                $dateStr = (string)$dateStr;
                                if (trim($dateStr) === '') return '';

                                $ts = false;

                                // If provider sends unix timestamp (seconds or ms)
                                if (is_numeric($dateStr)) {
                                    $n = (float)$dateStr;
                                    if ($n > 1000000000000) { // ms
                                        $n = $n / 1000;
                                    }
                                    $ts = (int)$n;
                                } else {
                                    // If ISO datetime with Z, parse as UTC then convert (avoid timezone drift)
                                    if (str_ends_with($dateStr, 'Z')) {
                                        try {
                                            $dt = new DateTime($dateStr, new DateTimeZone('UTC'));
                                            $dt->setTimezone(new DateTimeZone('Africa/Lagos'));
                                            $ts = $dt->getTimestamp();
                                        } catch (Exception $e) {
                                            $ts = strtotime($dateStr);
                                        }
                                    } else {
                                        $ts = strtotime($dateStr);
                                    }
                                }

                                if (!$ts) return '';

                                $diff = time() - $ts;
                                if ($diff < 0) $diff = 0;

                                if ($diff < 60) return 'Just now';
                                $m = floor($diff / 60);
                                if ($m < 60) return $m . 'm ago';
                                $h = floor($m / 60);
                                if ($h < 24) return $h . 'h ago';
                                $d = floor($h / 24);
                                return $d . 'd ago';
                            };
                        ?>

                        <div class="ra-header">
                            <div class="ra-header-icon">
                                <i class="ri-broadcast-line"></i>
                            </div>
                            <div>
                                <div class="ra-header-title">Recent Activity</div>
                                <div class="ra-header-sub">Latest orders and deposits</div>
                            </div>
                        </div>

                        <?php if (!empty($raEvents)) { ?>
                            <div>
                                <?php foreach ($raEvents as $ev) {
                                    $direction = strtolower((string)($ev['direction'] ?? 'credit'));
                                    $isDebit = ($direction === 'debit');

                                    $userName = (string)($ev['user_name'] ?? '');
                                    $maskedUser = $maskUser($userName);

                                    $amount = (float)($ev['amount'] ?? 0);
                                    $status = (string)($ev['status'] ?? '0');

                                    $statusText = 'Cancelled';
                                    if ($status === '1') $statusText = 'Success';
                                    elseif ($status === '2') $statusText = 'Pending';

                                    $timeText = $timeAgo($ev['date'] ?? '');

                                    if ($isDebit) {
                                        $badgeText = 'Order';
                                        $badgeClass = 'order';
                                        $iconClass = 'order';
                                        $icon = 'ri-shopping-cart-line';
                                        $desc = 'Purchased ' . trim((string)($ev['activity_text'] ?? ''));
                                        $sub = '';
                                    } else {
                                        $badgeText = 'Deposit';
                                        $badgeClass = 'deposit';
                                        $iconClass = 'deposit';
                                        $icon = 'ri-arrow-down-circle-line';
                                        $provider = trim((string)($ev['activity_text'] ?? 'Deposit'));
                                        $desc = 'Funded ₦' . number_format($amount, 0) . ' via ' . $provider;
                                        $sub = $statusText;
                                    }
                                ?>
                                    <div class="ra-item">
                                        <div class="ra-left">
                                            <div class="ra-icon <?php echo $iconClass; ?>">
                                                <i class="<?php echo $icon; ?>"></i>
                                            </div>
                                            <div class="ra-center">
                                                <div class="ra-user"><?php echo htmlspecialchars($maskedUser); ?></div>
                                                <div class="ra-desc"><?php echo htmlspecialchars($desc); ?></div>
                                            </div>
                                        </div>
                                        <div class="ra-right">
                                            <div class="ra-badge <?php echo $badgeClass; ?>">
                                                <i class="ri-bank-card-line"></i>
                                                <?php echo htmlspecialchars($badgeText); ?>
                                            </div>
                                            <?php if ($timeText !== '') { ?>
                                                <div class="ra-time"><?php echo htmlspecialchars($timeText); ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="transactions-empty" style="background:var(--ds-card);border:1px solid var(--ds-border-card);border-radius:16px;padding:16px;">
                                <img src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/image/empty.gif" alt="empty box">
                                <p>No Recent Activity Found</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- * Recent Activities -->
            <?php $recentActivitiesHtml = ob_get_clean(); ?>

            <!-- Transactions -->
            <div class="section mt-4 p-0">
                <div class="ds-card">
                    <div class="ds-section-head">
                        <h2 class="ds-section-title">
                            <span class="ds-section-icon">₦</span>
                            Recent Payments
                        </h2>
                        <a class="ds-section-action" href="transactions">See all →</a>
                    </div>
                <?php
                    if($recent_history){
                ?>
                <div class="ds-body">
                  <div class="tx-list">
                    <?php 
                        $i = 1;
                        foreach($recent_history as $transaction){
                            $statusClass = 'cancelled';
                            $statusText = 'Cancelled';
                            if($transaction['status'] == "1"){
                                $statusClass = 'success';
                                $statusText = 'Success';
                            }elseif($transaction['status'] == "2"){
                                $statusClass = 'pending';
                                $statusText = 'Pending';
                            }

                            $timestamp = strtotime($transaction['date']);
                            $formattedDate = date("M d, Y - h:i A", $timestamp);  
                            $type = (string)($transaction['type'] ?? '');
                            $avatar = strtoupper(substr(preg_replace('/[^A-Za-z0-9]+/', '', $type), 0, 1));
                            if($avatar === '') $avatar = '₦';

                            $direction = strtolower((string)($transaction['direction'] ?? 'credit'));
                            $isDebit = ($direction === 'debit');
                            $amountPrefix = $isDebit ? '- ₦' : '+ ₦';
                            $amountClass = $isDebit ? 'tx-amount debit' : 'tx-amount';
                            $href = '#';
                            if (!empty($transaction['order_id'])) {
                                $href = 'log-order-details?order_id=' . urlencode((string)$transaction['order_id']);
                            }
                    ?>
                    <!-- item -->
                    <a href="<?php echo htmlspecialchars($href); ?>" class="tx-item">
                        <div class="tx-left">
                            <div class="tx-avatar"><?php echo htmlspecialchars($avatar); ?></div>
                            <div class="tx-meta">
                                <p class="tx-title"><?php echo htmlspecialchars($type); ?></p>
                                <p class="tx-sub"><?php echo htmlspecialchars($formattedDate); ?></p>
                            </div>
                        </div>
                        <div class="tx-right">
                            <div class="<?php echo $amountClass; ?>"><?php echo $amountPrefix; ?><?php echo number_format((float)$transaction['amount'], 2);?></div>
                            <div class="tx-status <?php echo $statusClass; ?>"><?php echo $statusText; ?></div>
                        </div>
                    </a>
                    <!-- * item -->
                    <?php
                        }
                    ?>
                  </div>
                </div>
                <?php
                    }else{ 
                ?>
                    <div class="ds-body">
                        <div class="transactions-empty" style="background:var(--ds-card);border:1px solid var(--ds-border-card);border-radius:16px;padding:16px;">
                            <img src="<?php echo WEBSITE_URL; ?>/theam/otpsuite/assets/image/empty.gif" alt="empty box">
                            <p>No Recent Activity Found</p>
                        </div>
                    </div>
                <?php
                }    
                ?>
                </div>
            </div>
            <!-- * Transactions -->
            <?php if (!empty($recentActivitiesHtml)) { echo $recentActivitiesHtml; } ?>
        </div>
    </div> 

    <?php
        include("include/bottom-menu.php")
    ?>
</div>
<!-- LOGIN BROADCAST (vital info, once per session) -->
<div class="modal fade dialogbox" id="BroadcastPopup" tabindex="-1" role="dialog">
    <div class="modal-dialog logout" role="document">
        <div class="modal-content">
            <div class="modal-icon">
                <ion-icon name="megaphone-outline"></ion-icon>
            </div>
            <div class="modal-header">
                <h5 class="modal-title" id="broadcastTitle">Announcement</h5>
            </div>
            <div class="modal-body">
                <div id="broadcastMessage" class="broadcast-message"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="broadcastGotIt">Got it</button>
            </div>
        </div>
    </div>
</div>
<!-- AUTO NOTIFICATION DIALOG -->
<div class="modal fade dialogbox" id="NotificationPopup" tabindex="-1" role="dialog">
    <div class="modal-dialog logout" role="document">
        <div class="modal-content">

            <div class="modal-icon">
                <ion-icon name="notifications-outline"></ion-icon>
            </div>

            <div class="modal-header">
                <h5 class="modal-title" id="popupTitle"></h5>
            </div>

            <div class="modal-body">
                <div id="popupPreview"></div>
                <small class="text-muted d-block mt-2" id="popupTime"></small>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="popupReadMore">
                    Read More
                </button>

                <button class="btn btn-outline-secondary" id="popupDismiss">
                    Dismiss
                </button>

                <button class="btn btn-outline-danger" id="popupDontShow">
                    Don’t Show Again
                </button>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#see-all-services').on('click', function () {
            const section = $('.services-section');
            const extraRow = section.find('.extra-services');

            const isHidden = extraRow.hasClass('hidden');

            extraRow.toggleClass('hidden');

           
            $(this).text(isHidden ? 'See Less' : 'See All');

            if (isHidden) {
                section[0].scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Toggle Balance Funciton
    function toggleBalance(el) {
      const balance = el.parentElement;
      const amount = balance.querySelector('.total');
      const hidden = balance.querySelector('.balance-hidden');
      const icon = el.querySelector('i');

      const isVisible = amount.dataset.visible === "true";

      if (isVisible) {
        amount.style.display = 'none';
        hidden.style.display = 'inline';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
      } else {
        amount.style.display = 'inline';
        hidden.style.display = 'none';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
      }

      amount.dataset.visible = !isVisible;
    }
    
    $(document).ready(function(){
        const token = $('#token').val();
        if(!token) return;

        function showNotificationPopup() {
            $.getJSON("api/notifications/getUnreadPopup.php",{token},function(res){
                if(res.status !== 200) return;
                const n = res.data;
                $("#popupTitle").text(n.title);
                $("#popupPreview").html(n.preview);
                $("#popupTime").text(n.created_at);
                const modal = new bootstrap.Modal(document.getElementById('NotificationPopup'));
                modal.show();
                $("#popupReadMore").off().on("click",function(){ window.location.href = "notifications?open=" + n.id; });
                $("#popupDismiss").off().on("click",function(){ modal.hide(); });
                $("#popupDontShow").off().on("click",function(){
                    $.post("api/notifications/dismissNotification.php",{id:n.id,token},function(){ modal.hide(); });
                });
            });
        }

        // Login broadcast (vital info) – show once per session, then notification if any
        $.getJSON("api/broadcast/get.php",{token},function(res){
            if(res.status === 200 && !sessionStorage.getItem('broadcast_dismissed')){
                const b = res.data;
                $("#broadcastTitle").text(b.title);
                $("#broadcastMessage").html(b.message);
                const broadcastModal = new bootstrap.Modal(document.getElementById('BroadcastPopup'));
                broadcastModal.show();
                $("#broadcastGotIt").off().on("click",function(){
                    sessionStorage.setItem('broadcast_dismissed','1');
                    broadcastModal.hide();
                    showNotificationPopup();
                });
            } else {
                showNotificationPopup();
            }
        });
    });

</script>
<?php
    include 'include/footer-main.php';
?>