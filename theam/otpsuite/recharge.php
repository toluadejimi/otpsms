<?php
$page_name = "Dashboard";
include 'include/header-main.php';

// Show only payment gateways enabled in admin (payment_gateways.status = 1)
$enabled_gateway_names = [];
if (isset($conn)) {
    $gwq = $conn->query("SELECT name FROM payment_gateways WHERE status = 1");
    if ($gwq) {
        while ($r = $gwq->fetch_assoc()) {
            $enabled_gateway_names[] = strtolower(trim((string)($r['name'] ?? '')));
        }
    }
}
$sprintpay_enabled = in_array('sprintpay', $enabled_gateway_names, true);
$paymentpoint_enabled = in_array('paymentpoint', $enabled_gateway_names, true) || in_array('paypoint', $enabled_gateway_names, true);
?>
<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md hydrated"></i>
                </a>
                <div class="pageTitle">Add Wallet</div>
            </div>
            <div class="right">
            </div>
        </div>
    </div>

    <div id="appCapsule">
        <div class="listview-title mb-1">
            Recharge Methods
        </div>
        <ul class="listview image-listview" id="walletMethodsList">
            <?php if ($sprintpay_enabled) { ?>
            <li>
                <a href="#" class="item" onclick="showSprintPayAmountForm()">
                    <img src="img/sprintpay.png" alt="SprintPay" class="image" onerror="this.src='img/svg/155-credit-card.svg';this.alt='SprintPay';">
                    <div class="in">
                        <div>
                            <strong>Pay with SprintPay</strong>
                            <p class="my-2">Enter amount and pay on SprintPay (redirect)</p>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" class="item" onclick="loadSprintPayVirtualAccount()">
                    <img src="img/sprintpay.png" alt="SprintPay" class="image" onerror="this.src='img/svg/155-credit-card.svg';this.alt='Virtual Account';">
                    <div class="in">
                        <div>
                            <strong>SprintPay Virtual Account</strong>
                            <p class="my-2">Pay using your dedicated virtual account</p>
                        </div>
                    </div>
                </a>
            </li>
            <?php } ?>

            <?php if ($paymentpoint_enabled) { ?>
            <li>
                <a href="#" class="item" onclick="loadPaymentPointAccount()">
                    <img src="img/paymentpoint.png" alt="PaymentPoint" class="image" onerror="this.src='img/svg/155-credit-card.svg';this.alt='PaymentPoint';">
                    <div class="in">
                        <div>
                            <strong>PaymentPoint Virtual Account</strong>
                            <p class="my-2">Pay using your PaymentPoint virtual account</p>
                        </div>
                    </div>
                </a>
            </li>
            <?php } ?>

            <?php if (!$sprintpay_enabled) { ?>
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            <strong>No payment gateway enabled</strong>
                            <p class="my-2">Ask admin to enable a gateway in Admin → Payment Gateways.</p>
                        </div>
                    </div>
                </div>
            </li>
            <?php } ?>
        </ul>

        <div id="virtualAccountContainer" style="display:none; margin-top: 20px;"></div>
        <div id="sprintPayAmountContainer" style="display:none; margin-top: 20px;"></div>
    </div>

    <?php
        include("include/bottom-menu.php");
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
var notyf = new Notyf();

function copyText(text) {
    if (!text) return;
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
            notyf.success('Copied');
        }).catch(function() {
            fallbackCopyText(text);
        });
    } else {
        fallbackCopyText(text);
    }
}

function fallbackCopyText(text) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.setAttribute('readonly', '');
    ta.style.position = 'absolute';
    ta.style.left = '-9999px';
    document.body.appendChild(ta);
    ta.select();
    try {
        document.execCommand('copy');
        notyf.success('Copied');
    } catch (e) {
        notyf.error('Could not copy');
    }
    document.body.removeChild(ta);
}

// Handle return from SprintPay (payment=success)
(function() {
    var params = new URLSearchParams(window.location.search);
    var ref = params.get('ref');
    var payment = params.get('payment');
    if (ref && payment === 'success') {
        notyf.success('Payment successful! Your wallet will be credited shortly.');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
})();

function showSprintPayAmountForm() {
    $('#walletMethodsList').hide();
    $('#virtualAccountContainer').hide();
    $('#sprintPayAmountContainer').html(`
        <div class="card text-dark p-3">
            <div class="pointer fs-3 mb-2" onclick="backToMethods()">
                <i class="ri-arrow-left-line icon md hydrated"></i>
            </div>
            <div class="text-center">
                <img src="img/sprintpay.png" height="100" onerror="this.style.display='none'"><br>
                <p style="color:red">Minimum amount is ₦100</p>
                <p>Enter the amount you want to add and you will be redirected to SprintPay to complete payment.</p>
                <div class="form-group mb-3 mt-3">
                    <label class="form-label">Amount (₦)</label>
                    <input type="number" id="sprintpay_amount" class="form-control" placeholder="e.g. 1000" min="100" value="1000">
                </div>
                <button type="button" onclick="redirectToSprintPay()" class="btn btn-primary w-full">
                    Pay with SprintPay
                </button>
            </div>
        </div>
    `).show();
}

function redirectToSprintPay() {
    var amount = parseInt($('#sprintpay_amount').val(), 10);
    if (isNaN(amount) || amount < 100) {
        notyf.error('Please enter at least ₦100');
        return;
    }
    var btn = $('button[onclick="redirectToSprintPay()"]');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Redirecting...');
    $.ajax({
        type: "POST",
        url: "api/sprintpay/redirect_pay_url.php",
        data: { token: "<?php echo $_SESSION['token']; ?>", amount: amount },
        success: function(data) {
            var json = typeof data === 'string' ? JSON.parse(data) : data;
            if (json.status === '1' && json.pay_url) {
                window.location.href = json.pay_url;
            } else {
                notyf.error(json.msg || 'Could not get payment link');
                btn.prop('disabled', false).html('Pay with SprintPay');
            }
        },
        error: function() {
            notyf.error('An error occurred.');
            btn.prop('disabled', false).html('Pay with SprintPay');
        }
    });
}

function loadSprintPayVirtualAccount() {
    $('#walletMethodsList').hide();
    $('#sprintPayAmountContainer').hide();

    $.ajax({
        type: "POST",
        url: "api/sprintpay/get_virtual_account.php",
        data: { token: "<?php echo $_SESSION['token']; ?>" },
        success: function(response) {
            var json = typeof response === 'string' ? JSON.parse(response) : response;
            if (json.status === "1") {
                renderAccountView(json.data);
            } else if (json.status === "0") {
                renderGenerateView();
            } else {
                notyf.error(json.msg || "Something went wrong.");
                backToMethods();
            }
        },
        error: function() {
            notyf.error("Something went wrong.");
            backToMethods();
        }
    });
}

function loadPaymentPointAccount() {
    $('#walletMethodsList').hide();
    $('#sprintPayAmountContainer').hide();

    $.ajax({
        type: "POST",
        url: "api/paymentpoint/get_bank_account.php",
        data: { token: "<?php echo $_SESSION['token']; ?>" },
        success: function(response) {
            var json = typeof response === 'string' ? JSON.parse(response) : response;
            if (json.status === "1") {
                renderGenericAccountView(json.data, 'PaymentPoint', 'img/paymentpoint.png');
            } else {
                // Try generate (may fail if provider not integrated)
                $.ajax({
                    type: "POST",
                    url: "api/paymentpoint/generate_bank_account.php",
                    data: { token: "<?php echo $_SESSION['token']; ?>" },
                    success: function(r2) {
                        var j2 = typeof r2 === 'string' ? JSON.parse(r2) : r2;
                        if (j2.status === "1") {
                            renderGenericAccountView(j2.data, 'PaymentPoint', 'img/paymentpoint.png');
                        } else {
                            notyf.error(j2.msg || 'PaymentPoint account not available.');
                            backToMethods();
                        }
                    },
                    error: function() {
                        notyf.error("Something went wrong.");
                        backToMethods();
                    }
                });
            }
        },
        error: function() {
            notyf.error("Something went wrong.");
            backToMethods();
        }
    });
}

function renderGenericAccountView(account, label, logo) {
    $('#sprintPayAmountContainer').hide();
    $('#virtualAccountContainer').html(`
        <div class="card text-dark p-3" style="border-radius:16px;">
            <div class="pointer fs-3 mb-2" onclick="backToMethods()">
                <i class="ri-arrow-left-line icon md hydrated"></i>
            </div>
            <div class="text-center">
                <img src="${logo}" height="100" onerror="this.style.display='none'"><br>
                <div class="mb-2" style="font-weight:700;font-size:18px;">${label} Account</div>
                <div class="text-muted" style="font-size:13px;">Transfer to the account below to fund your wallet</div>
            </div>

            <div class="mt-3">
                <div class="mb-2" style="font-size:12px;color:#6b7280;">Account Number</div>
                <div class="d-flex align-items-center justify-content-between" style="gap:10px;">
                    <div style="font-weight:900;font-size:18px;letter-spacing:.5px;">${account.account_number || ''}</div>
                    <button class="btn btn-sm btn-outline-primary" onclick="copyText('${account.account_number || ''}')">Copy</button>
                </div>
            </div>

            <div class="mt-3">
                <div class="mb-2" style="font-size:12px;color:#6b7280;">Account Name</div>
                <div style="font-weight:700;">${account.account_name || ''}</div>
            </div>

            <div class="mt-3">
                <div class="mb-2" style="font-size:12px;color:#6b7280;">Bank</div>
                <div style="font-weight:700;">${account.bank_name || label}</div>
            </div>

            <div class="mt-3 alert alert-warning" style="font-size:12px;">
                Please transfer from an account you own. Wallet credit depends on provider webhook confirmation.
            </div>
        </div>
    `).show();
}

function renderAccountView(account) {
    $('#sprintPayAmountContainer').hide();
    $('#virtualAccountContainer').html(`
        <div class="card text-dark p-3" style="border-radius:16px;">
            <div class="pointer fs-3 mb-2" onclick="backToMethods()">
                <i class="ri-arrow-left-line icon md hydrated"></i>
            </div>
            <div class="text-center">
                <img src="img/sprintpay.png" height="100" onerror="this.style.display='none'"><br>
                <div class="mb-2" style="font-weight:700;font-size:18px;">Your Virtual Account</div>
                <div class="text-muted" style="font-size:13px;">Minimum transfer is ₦100</div>
            </div>

            <div class="mt-3" style="display:flex;flex-direction:column;gap:10px;">
                <div style="padding:12px;border:1px solid rgba(0,0,0,.08);border-radius:14px;">
                    <div class="text-muted" style="font-size:12px;">Account Number</div>
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
                        <div style="font-weight:800;font-size:20px;letter-spacing:1px;">${account.account_number}</div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="copyText('${account.account_number}')">Copy</button>
                    </div>
                </div>
                <div style="display:flex;gap:10px;">
                    <div style="flex:1;padding:12px;border:1px solid rgba(0,0,0,.08);border-radius:14px;">
                        <div class="text-muted" style="font-size:12px;">Bank</div>
                        <div style="font-weight:700;">${account.bank_name}</div>
                    </div>
                    <div style="flex:1;padding:12px;border:1px solid rgba(0,0,0,.08);border-radius:14px;">
                        <div class="text-muted" style="font-size:12px;">Account Name</div>
                        <div style="font-weight:700;">${account.account_name}</div>
                    </div>
                </div>
            </div>
        </div>
    `).show();
}

function renderGenerateView() {
    $('#sprintPayAmountContainer').hide();
    $('#virtualAccountContainer').html(`
        <div class="card text-dark p-3" style="border-radius:16px;">
            <div class="pointer fs-3 mb-2" onclick="backToMethods()">
                <i class="ri-arrow-left-line icon md hydrated"></i>
            </div>
            <div class="text-center">
                <img src="img/sprintpay.png" height="150" onerror="this.style.display='none'"><br>
                <div class="mb-2" style="font-weight:700;font-size:18px;">Create your Virtual Account</div>
                <div class="text-muted" style="font-size:13px;">Enter your details to generate a dedicated account.</div>
            </div>

            <div class="mt-3">
                <div class="form-group mb-2">
                    <label class="form-label">Full name</label>
                    <input type="text" id="va_full_name" class="form-control" placeholder="e.g. John Doe">
                </div>
                <div class="form-group mb-2">
                    <label class="form-label">Phone number</label>
                    <input type="tel" id="va_phone" class="form-control" placeholder="e.g. 08012345678">
                </div>
                <div class="text-muted" style="font-size:12px;margin-top:6px;">Minimum transfer is ₦100</div>
                <button onclick="generateSprintPayAccount()" class="btn btn-primary w-full mt-3">
                    Generate Account
                </button>
            </div>
        </div>
    `).show();
}

function backToMethods() {
    $('#virtualAccountContainer').hide();
    $('#sprintPayAmountContainer').hide();
    $('#walletMethodsList').show();
}

function generateSprintPayAccount() {
    var btn = $('button[onclick="generateSprintPayAccount()"]');
    var fullName = ($('#va_full_name').val() || '').trim();
    var phone = ($('#va_phone').val() || '').trim();
    if (!fullName) {
        notyf.error("Please enter your full name");
        return;
    }
    if (!phone) {
        notyf.error("Please enter your phone number");
        return;
    }

    btn.prop('disabled', true);
    btn.html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 inline-block align-middle mr-2"></span> Generating...');

    $.ajax({
        type: "POST",
        url: "api/sprintpay/generate_virtual_account.php",
        data: { token: "<?php echo $_SESSION['token']; ?>", name: fullName, phone_number: phone },
        success: function(data) {
            var json = typeof data === 'string' ? JSON.parse(data) : data;
            if (json.status === "1") {
                notyf.success(json.msg);
                renderAccountView(json.data);
            } else {
                notyf.error(json.msg || "Could not generate account.");
                btn.prop('disabled', false).html('Generate Account');
            }
        },
        error: function() {
            notyf.error("An error occurred.");
            btn.prop('disabled', false).html('Generate Account');
        }
    });
}
</script>

<?php
include 'include/footer-main.php';
?>
