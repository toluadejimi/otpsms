<?php
$page_name = "Recharge";
include 'include/header-main.php';
?>
<script>
    $(document).ready(function() {
        // Remove "active" class from all <a> elements
        $('#slide-dashboard').removeClass("active");

        // Add "active" class to the specific element with ID "faq"
        $("#slide-recharge").addClass("active");
    });
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-3.2.6.min.css" rel="stylesheet">
<style>
    .typewriter {
        --blue: #5C86FF;
        --blue-dark: #275EFE;
        --key: #fff;
        --paper: #EEF0FD;
        --text: #D3D4EC;
        --tool: #FBC56C;
        --duration: 3s;
        position: relative;
        -webkit-animation: bounce05 var(--duration) linear infinite;
        animation: bounce05 var(--duration) linear infinite;
    }

    .typewriter .slide {
        width: 92px;
        height: 20px;
        border-radius: 3px;
        margin-left: 14px;
        transform: translateX(14px);
        background: linear-gradient(var(--blue), var(--blue-dark));
        -webkit-animation: slide05 var(--duration) ease infinite;
        animation: slide05 var(--duration) ease infinite;
    }

    .typewriter .slide:before,
    .typewriter .slide:after,
    .typewriter .slide i:before {
        content: "";
        position: absolute;
        background: var(--tool);
    }

    .typewriter .slide:before {
        width: 2px;
        height: 8px;
        top: 6px;
        left: 100%;
    }

    .typewriter .slide:after {
        left: 94px;
        top: 3px;
        height: 14px;
        width: 6px;
        border-radius: 3px;
    }

    .typewriter .slide i {
        display: block;
        position: absolute;
        right: 100%;
        width: 6px;
        height: 4px;
        top: 4px;
        background: var(--tool);
    }

    .typewriter .slide i:before {
        right: 100%;
        top: -2px;
        width: 4px;
        border-radius: 2px;
        height: 14px;
    }

    .typewriter .paper {
        position: absolute;
        left: 24px;
        top: -26px;
        width: 40px;
        height: 46px;
        border-radius: 5px;
        background: var(--paper);
        transform: translateY(46px);
        -webkit-animation: paper05 var(--duration) linear infinite;
        animation: paper05 var(--duration) linear infinite;
    }

    .typewriter .paper:before {
        content: "";
        position: absolute;
        left: 6px;
        right: 6px;
        top: 7px;
        border-radius: 2px;
        height: 4px;
        transform: scaleY(0.8);
        background: var(--text);
        box-shadow: 0 12px 0 var(--text), 0 24px 0 var(--text), 0 36px 0 var(--text);
    }

    .typewriter .keyboard {
        width: 120px;
        height: 56px;
        margin-top: -10px;
        z-index: 1;
        position: relative;
    }

    .typewriter .keyboard:before,
    .typewriter .keyboard:after {
        content: "";
        position: absolute;
    }

    .typewriter .keyboard:before {
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 7px;
        background: linear-gradient(135deg, var(--blue), var(--blue-dark));
        transform: perspective(10px) rotateX(2deg);
        transform-origin: 50% 100%;
    }

    .typewriter .keyboard:after {
        left: 2px;
        top: 25px;
        width: 11px;
        height: 4px;
        border-radius: 2px;
        box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
        -webkit-animation: keyboard05 var(--duration) linear infinite;
        animation: keyboard05 var(--duration) linear infinite;
    }

    @keyframes bounce05 {

        85%,
        92%,
        100% {
            transform: translateY(0);
        }

        89% {
            transform: translateY(-4px);
        }

        95% {
            transform: translateY(2px);
        }
    }

    @keyframes slide05 {
        5% {
            transform: translateX(14px);
        }

        15%,
        30% {
            transform: translateX(6px);
        }

        40%,
        55% {
            transform: translateX(0);
        }

        65%,
        70% {
            transform: translateX(-4px);
        }

        80%,
        89% {
            transform: translateX(-12px);
        }

        100% {
            transform: translateX(14px);
        }
    }

    @keyframes paper05 {
        5% {
            transform: translateY(46px);
        }

        20%,
        30% {
            transform: translateY(34px);
        }

        40%,
        55% {
            transform: translateY(22px);
        }

        65%,
        70% {
            transform: translateY(10px);
        }

        80%,
        85% {
            transform: translateY(0);
        }

        92%,
        100% {
            transform: translateY(46px);
        }
    }

    @keyframes keyboard05 {

        5%,
        12%,
        21%,
        30%,
        39%,
        48%,
        57%,
        66%,
        75%,
        84% {
            box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
        }

        9% {
            box-shadow: 15px 2px 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
        }

        18% {
            box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 2px 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
        }

        27% {
            box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 12px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
        }

        36% {
            box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 12px 0 var(--key), 60px 12px 0 var(--key), 68px 12px 0 var(--key), 83px 10px 0 var(--key);
        }

        45% {
            box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 2px 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
        }

        54% {
            box-shadow: 15px 0 0 var(--key), 30px 2px 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
        }

        63% {
            box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 12px 0 var(--key);
        }

        72% {
            box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 2px 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 10px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
        }

        81% {
            box-shadow: 15px 0 0 var(--key), 30px 0 0 var(--key), 45px 0 0 var(--key), 60px 0 0 var(--key), 75px 0 0 var(--key), 90px 0 0 var(--key), 22px 10px 0 var(--key), 37px 12px 0 var(--key), 52px 10px 0 var(--key), 60px 10px 0 var(--key), 68px 10px 0 var(--key), 83px 10px 0 var(--key);
        }
    }

    .pointer {
        cursor: pointer;
    }
</style>
<wc-toast id="tt" position="top-right"> </wc-toast>

<script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/apexcharts.js"></script>

<div class="panel p-3 mb-4 flex items-center text-primary overflow-x-auto whitespace-nowrap">
    <div class="ring-2 ring-primary/30 rounded-full bg-primary text-white p-1.5 ltr:mr-3 rtl:ml-3">

        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5">
            <path d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z" stroke="currentColor" stroke-width="1.5" />
            <path opacity="0.5" d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
        </svg>
    </div>
    <span class="ltr:mr-3 rtl:ml-3 float-right">If You Are Facing Any Problem Please Contact Us</span><a href="<?php echo $site_data['support_url']; ?>" target="_blank"><button type="button" class="btn btn-outline-primary btn-sm">Contact Us</button></a>
</div>
<!-- Searchable -->
<input type="hidden" name="tokens" id="tokens" value="<?php echo $_SESSION['token']; ?>">
<input type="hidden" name="product_id" id="product_id" value="<?php echo $product['id']; ?>">
<div id="cards_page" class="panel p-4">
    <div class="card shadow-base2">
        <div class="card-text space-y-2">
            <div class="mb-2 flex p-2">
                <a class="flex-shrink-0 mt-1 p-1 pointer " href="buy-logs">
                    <i class="fa fa-arrow-left text-2xl"></i>
                </a>
            </div>
            <div class="grid place-content-center w-[70px] h-[70px] mx-auto rounded-md bg-primary-light dark:bg-primary text-primary dark:text-primary-light">
                <svg class="w-10 h-10" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.4036 22.4797L10.6787 22.015C11.1195 21.2703 11.3399 20.8979 11.691 20.6902C12.0422 20.4825 12.5001 20.4678 13.4161 20.4385C14.275 20.4111 14.8523 20.3361 15.3458 20.1317C16.385 19.7012 17.2106 18.8756 17.641 17.8365C17.9639 17.0571 17.9639 16.0691 17.9639 14.093V13.2448C17.9639 10.4683 17.9639 9.08006 17.3389 8.06023C16.9892 7.48958 16.5094 7.0098 15.9388 6.66011C14.919 6.03516 13.5307 6.03516 10.7542 6.03516H8.20964C5.43314 6.03516 4.04489 6.03516 3.02507 6.66011C2.45442 7.0098 1.97464 7.48958 1.62495 8.06023C1 9.08006 1 10.4683 1 13.2448V14.093C1 16.0691 1 17.0571 1.32282 17.8365C1.75326 18.8756 2.57886 19.7012 3.61802 20.1317C4.11158 20.3361 4.68882 20.4111 5.5477 20.4385C6.46368 20.4678 6.92167 20.4825 7.27278 20.6902C7.6239 20.8979 7.84431 21.2703 8.28514 22.015L8.5602 22.4797C8.97002 23.1721 9.9938 23.1721 10.4036 22.4797ZM13.1928 14.5171C13.7783 14.5171 14.253 14.0424 14.253 13.4568C14.253 12.8713 13.7783 12.3966 13.1928 12.3966C12.6072 12.3966 12.1325 12.8713 12.1325 13.4568C12.1325 14.0424 12.6072 14.5171 13.1928 14.5171ZM10.5422 13.4568C10.5422 14.0424 10.0675 14.5171 9.48193 14.5171C8.89637 14.5171 8.42169 14.0424 8.42169 13.4568C8.42169 12.8713 8.89637 12.3966 9.48193 12.3966C10.0675 12.3966 10.5422 12.8713 10.5422 13.4568ZM5.77108 14.5171C6.35664 14.5171 6.83133 14.0424 6.83133 13.4568C6.83133 12.8713 6.35664 12.3966 5.77108 12.3966C5.18553 12.3966 4.71084 12.8713 4.71084 13.4568C4.71084 14.0424 5.18553 14.5171 5.77108 14.5171Z" fill="currentColor" />
                    <path opacity="0.5" d="M15.486 1C16.7529 0.999992 17.7603 0.999986 18.5683 1.07681C19.3967 1.15558 20.0972 1.32069 20.7212 1.70307C21.3632 2.09648 21.9029 2.63623 22.2963 3.27821C22.6787 3.90219 22.8438 4.60265 22.9226 5.43112C22.9994 6.23907 22.9994 7.24658 22.9994 8.51343V9.37869C22.9994 10.2803 22.9994 10.9975 22.9597 11.579C22.9191 12.174 22.8344 12.6848 22.6362 13.1632C22.152 14.3323 21.2232 15.2611 20.0541 15.7453C20.0249 15.7574 19.9955 15.7691 19.966 15.7804C19.8249 15.8343 19.7039 15.8806 19.5978 15.915H17.9477C17.9639 15.416 17.9639 14.8217 17.9639 14.093V13.2448C17.9639 10.4683 17.9639 9.08006 17.3389 8.06023C16.9892 7.48958 16.5094 7.0098 15.9388 6.66011C14.919 6.03516 13.5307 6.03516 10.7542 6.03516H8.20964C7.22423 6.03516 6.41369 6.03516 5.73242 6.06309V4.4127C5.76513 4.29934 5.80995 4.16941 5.86255 4.0169C5.95202 3.75751 6.06509 3.51219 6.20848 3.27821C6.60188 2.63623 7.14163 2.09648 7.78361 1.70307C8.40759 1.32069 9.10805 1.15558 9.93651 1.07681C10.7445 0.999986 11.7519 0.999992 13.0188 1H15.486Z" fill="currentColor" />
                </svg>
            </div>
            <div style="position: relative; margin-top: 8px; text-align: left;"> <!-- Adjusted for left alignment -->
                <h5 class="font-bold text-2xl dark:text-white-light"><?php echo $product['name'] ?></h5>
                <p class="font-bold text-base mt-3 dark:text-white-light">
                    <?php echo $product['description'] ?>
                </p>
                <hr class="mt-5 mb-5">
                <div class="flex items-center justify-between">
                    <div class="font-bold text-md dark:text-white-light">
                        ₦<?php echo number_format($product['price']) ?>/Pcs
                    </div>
                    <span class="text-base px-2 rounded text-dark dark:text-white">
                        <?php echo $product['in_stock'] ?> pcs in stock
                    </span>
                </div>
                <hr class="mt-5 mb-5">
                <div class="flex items-center justify-between">
                    <input id="quantity" type="number" style="width: auto !important;" min="1" value="1" class="form-input rounded text-center">
                    <span class="text-sm px-2 py-1 rounded bg-primary text-white dark:text-white-light">
                        NGN
                        <span id="total"><?php echo $product['price'] ?></span>
                    </span>
                </div>
                <hr class="mt-5 mb-5">
                <center>
                    <button id="place_order_btn" <?php echo $product['in_stock'] == "0" ? "disabled" : "" ?> class=" btn w-full btn-outline-primary">
                        Buy Now
                    </button>
                </center>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script type="module">
    import {
        toast
    } from 'https://cdn.skypack.dev/wc-toast';

    const quantityInput = document.getElementById('quantity');
    const totalSpan = document.getElementById('total');

    // Get the initial total value
    let unitPrice = Number(<?php echo $product['price'] ?>);
    let quantity = parseInt(quantityInput.value); // Parse initial quantity value


    // Update total function
    function updateTotal() {
        quantity = parseInt(quantityInput.value); // Update quantity variable
        let total = unitPrice * quantity; // Calculate total
        totalSpan.textContent = total.toFixed(2); // Update total in the span element
    }

    // Event listener for input change
    quantityInput.addEventListener('input', updateTotal);

    updateTotal();

    $(document).ready(function() {
        $('#place_order_btn').on('click', function() {
            var quantity = $('#quantity').val();
            var product_id = $('#product_id').val(); // Assuming you set product_id in the hidden input
            var token = $('#tokens').val(); // Token from the session or similar

            // Check if quantity is valid
            if (quantity < 1) {
                toast.error("Pleases enter a valid quantity.");
                return;
            }

            // Send data using Ajax
            $.ajax({
                url: 'api/service/buyLog', // URL for the PHP file handling the order placement
                type: 'POST',
                data: {
                    action: "place_order",
                    quantity: quantity,
                    product_id: product_id,
                    token: token
                },
                beforeSend: function() {
                    $('#place_order_btn').prop("disabled", true);
                    $('#place_order_btn').html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span> Placing Order...');
                },
                success: function(response) {
                    $('#place_order_btn').html("<span class='fa fa-cart-plus' style='margin-right: 8px;'></span>Buy Number");
                    $('#place_order_btn').prop("disabled", false);
                    var res = JSON.parse(response);
                    if (res.status == '200') {
                        toast.success(res.message)
                        setTimeout(() => {
                            window.location.href = "log-orders";
                        }, 1500);
                    } else {
                        toast.error("Error: " + res.message);
                    }
                },
                error: function(xhr, status, error) {
                    toast.error("An error occurred while placing the order.");
                    $('#place_order_btn').html("<span class='fa fa-cart-plus' style='margin-right: 8px;'></span>Buy Number");
                    $('#place_order_btn').prop("disabled", false);
                },
                complete: function() {
                    $('#place_order_btn').text('Place Order');
                }
            });
        });
    });
</script>

<?php include 'include/footer-main.php'; ?>
<script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
<div class="elfsight-app-488ed751-6471-4a45-ba1c-37692167165d" data-elfsight-app-lazy></div>