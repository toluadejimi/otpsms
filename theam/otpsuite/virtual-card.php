<?php
$page_name = "Virtual Card";
include 'include/header-main.php';
?>

<style>
    .vc-shell {
        background: radial-gradient(circle at top, #111827 0%, #020617 45%, #000 100%);
        min-height: 100vh;
    }
    .vc-card-wrap{
        margin-top:18px;
        margin-bottom:18px;
    }
    .vc-card{
        position:relative;
        border-radius:22px;
        padding:20px 18px;
        color:#f9fafb;
        overflow:hidden;
        background: radial-gradient(circle at -10% -10%, #6366f1 0%, #0f172a 45%, #020617 100%);
        box-shadow:0 22px 45px rgba(15,23,42,.7);
    }
    .vc-chip{
        width:38px;
        height:28px;
        border-radius:10px;
        background:linear-gradient(135deg,rgba(148,163,184,.4),rgba(15,23,42,0.1));
        border:1px solid rgba(148,163,184,.6);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:10px;
        text-transform:uppercase;
        letter-spacing:1px;
        color:rgba(248,250,252,.85);
    }
    .vc-brand{
        font-size:11px;
        text-transform:uppercase;
        letter-spacing:2px;
        color:rgba(226,232,240,.8);
    }
    .vc-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-top:16px;
    }
    .vc-balance-label{
        font-size:11px;
        color:rgba(148,163,184,.9);
        text-transform:uppercase;
        letter-spacing:1px;
    }
    .vc-balance-value{
        font-size:22px;
        font-weight:800;
    }
    .vc-holder{
        font-size:13px;
        font-weight:700;
    }
    .vc-meta{
        font-size:11px;
        color:rgba(148,163,184,.9);
    }
    .vc-last4{
        font-size:16px;
        letter-spacing:3px;
        font-weight:700;
    }
    .vc-badge{
        display:inline-flex;
        align-items:center;
        padding:4px 10px;
        border-radius:999px;
        font-size:11px;
        background:rgba(34,197,94,.12);
        color:#bbf7d0;
        border:1px solid rgba(34,197,94,.4);
    }
    .vc-actions{
        display:flex;
        gap:10px;
        margin-bottom:18px;
        overflow-x:auto;
    }
    .vc-action{
        flex:1 0 0;
        min-width:0;
        border-radius:14px;
        padding:12px 10px;
        border:1px solid rgba(15,23,42,.9);
        background:rgba(15,23,42,.9);
        display:flex;
        align-items:center;
        gap:10px;
        color:#e5e7eb;
    }
    .vc-action-icon{
        width:32px;
        height:32px;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        background:rgba(148,163,184,.18);
        color:#e5e7eb;
    }
    .vc-action strong{
        font-size:13px;
        display:block;
    }
    .vc-action span{
        font-size:11px;
        color:rgba(148,163,184,.9);
    }
    .vc-section-title{
        font-size:13px;
        font-weight:700;
        margin-bottom:10px;
    }
    .vc-tx-list{
        border-radius:16px;
        background:rgba(15,23,42,.94);
        border:1px solid rgba(30,64,175,.4);
        overflow:hidden;
    }
    .vc-tx-item{
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:11px 14px;
        border-bottom:1px solid rgba(15,23,42,1);
    }
    .vc-tx-item:last-child{
        border-bottom:none;
    }
    .vc-tx-left{
        display:flex;
        align-items:center;
        gap:10px;
    }
    .vc-tx-avatar{
        width:32px;
        height:32px;
        border-radius:999px;
        background:rgba(37,99,235,.18);
        display:flex;
        align-items:center;
        justify-content:center;
        color:#bfdbfe;
        font-size:15px;
        font-weight:800;
    }
    .vc-tx-meta-title{
        font-size:12px;
        font-weight:600;
    }
    .vc-tx-meta-sub{
        font-size:11px;
        color:rgba(148,163,184,.9);
    }
    .vc-tx-amount{
        font-size:13px;
        font-weight:700;
    }
    .vc-tx-amount.negative{
        color:#fecaca;
    }
    .vc-tx-amount.positive{
        color:#bbf7d0;
    }
    .vc-empty{
        border-radius:18px;
        padding:22px 16px;
        background:rgba(15,23,42,.96);
        border:1px dashed rgba(148,163,184,.4);
        text-align:center;
        color:#9ca3af;
    }
    .vc-empty strong{
        display:block;
        margin-bottom:6px;
        color:#e5e7eb;
    }
    .vc-primary-btn{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        padding:12px 20px;
        border-radius:999px;
        background:linear-gradient(90deg,#4f46e5,#6366f1);
        color:#f9fafb;
        font-size:13px;
        font-weight:700;
        border:none;
        width:100%;
    }
</style>

<div id="app">
    <div class="container-fluid p-0 vc-shell">
        <div class="appHeader">
            <div class="left">
                <a href="javascript:history.back();" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon"></i>
                </a>
                <div class="pageTitle">Virtual Card</div>
            </div>
            <div class="right"></div>
        </div>
        <div id="appCapsule">
            <div class="section mt-3 px-3">
                <?php if (empty($virtualCard)) { ?>
                    <div class="vc-empty mb-3">
                        <strong>No virtual card yet</strong>
                        <div style="font-size:12px;margin-bottom:14px;">
                            Create a secure virtual card for online payments, subscriptions and more.
                        </div>
                        <button type="button" class="vc-primary-btn" id="btnCreateVirtualCard">
                            <i class="ri-bank-card-2-line"></i>
                            <span>Create new virtual card</span>
                        </button>
                    </div>
                <?php } else {
                    $card = $virtualCard;
                    $last4 = htmlspecialchars($card['last4'] ?? '0000');
                    $nickname = htmlspecialchars($card['nickname'] ?? 'Main Virtual Card');
                    $status = strtolower((string)($card['status'] ?? 'active'));
                    $statusLabel = ucfirst($status);
                    if ($statusLabel === '') $statusLabel = 'Active';
                ?>
                    <div class="vc-card-wrap">
                        <div class="vc-card">
                            <div class="vc-row">
                                <div class="vc-brand">OTPSUITE VIRTUAL</div>
                                <div class="vc-chip">VIRTUAL</div>
                            </div>
                            <div class="vc-row" style="margin-top:22px;">
                                <div>
                                    <div class="vc-balance-label">Available balance</div>
                                    <div class="vc-balance-value">₦0.00</div>
                                </div>
                                <div>
                                    <div class="vc-last4">**** **** **** <?php echo $last4; ?></div>
                                </div>
                            </div>
                            <div class="vc-row" style="margin-top:20px;">
                                <div>
                                    <div class="vc-holder"><?php echo $nickname; ?></div>
                                    <div class="vc-meta">Virtual Card • NGN</div>
                                </div>
                                <div>
                                    <span class="vc-badge"><?php echo $statusLabel; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="vc-actions mb-3">
                        <button type="button" class="vc-action js-vc-coming">
                            <div class="vc-action-icon"><i class="ri-shield-keyhole-line"></i></div>
                            <div>
                                <strong>Block card</strong>
                                <span>Freeze payments instantly</span>
                            </div>
                        </button>
                        <button type="button" class="vc-action js-vc-coming">
                            <div class="vc-action-icon"><i class="ri-wallet-3-line"></i></div>
                            <div>
                                <strong>Fund card</strong>
                                <span>Move money from wallet</span>
                            </div>
                        </button>
                        <button type="button" class="vc-action js-vc-coming">
                            <div class="vc-action-icon"><i class="ri-close-circle-line"></i></div>
                            <div>
                                <strong>Cancel card</strong>
                                <span>Close this virtual card</span>
                            </div>
                        </button>
                    </div>

                    <div class="mb-4">
                        <div class="vc-section-title">Card transactions</div>
                        <div class="vc-tx-list">
                            <div class="vc-tx-item">
                                <div class="vc-tx-left">
                                    <div class="vc-tx-avatar">N</div>
                                    <div>
                                        <div class="vc-tx-meta-title">Netflix Subscription</div>
                                        <div class="vc-tx-meta-sub">Demo transaction • Pending provider API</div>
                                    </div>
                                </div>
                                <div class="vc-tx-amount negative">-₦3,200</div>
                            </div>
                            <div class="vc-tx-item">
                                <div class="vc-tx-left">
                                    <div class="vc-tx-avatar">A</div>
                                    <div>
                                        <div class="vc-tx-meta-title">Amazon Trial Hold</div>
                                        <div class="vc-tx-meta-sub">Demo transaction • Pending provider API</div>
                                    </div>
                                </div>
                                <div class="vc-tx-amount negative">-₦1,000</div>
                            </div>
                            <div class="vc-tx-item">
                                <div class="vc-tx-left">
                                    <div class="vc-tx-avatar">F</div>
                                    <div>
                                        <div class="vc-tx-meta-title">Wallet Funding</div>
                                        <div class="vc-tx-meta-sub">Demo credit • will sync from API later</div>
                                    </div>
                                </div>
                                <div class="vc-tx-amount positive">+₦5,000</div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        const token = '<?php echo isset($_SESSION['token']) ? addslashes($_SESSION['token']) : ''; ?>';

        $('#btnCreateVirtualCard').on('click', function () {
            if (!token) return;
            const $btn = $(this);
            $btn.prop('disabled', true).text('Creating card...');
            $.post('api/virtual-card/create.php', { token: token })
                .done(function (res) {
                    try { res = typeof res === 'string' ? JSON.parse(res) : res; } catch (e) {}
                    if (res && res.status === 200) {
                        window.location.reload();
                        return;
                    }
                    alert((res && res.message) ? res.message : 'Unable to create card');
                })
                .fail(function () {
                    alert('Network error while creating card');
                })
                .always(function () {
                    $btn.prop('disabled', false).text('Create new virtual card');
                });
        });

        $('.js-vc-coming').on('click', function () {
            alert('This action will be connected to the virtual card API soon.');
        });
    });
</script>

<?php include 'include/footer-main.php'; ?>

