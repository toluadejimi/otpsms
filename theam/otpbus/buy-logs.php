<?php
$page_name = "Buy Logs";
include 'include/header-main.php';

$log_data = array();

foreach ($log_categories as $category) {
    $products = $wallet->all_logs_products($category['id']);
    if (!empty($products)) {
        $log_data[] = array(
            'category' => $category,
            'products' => $products
        );
    }
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/apexcharts.js"></script>

<wc-toast id="tt" position="top-right"> </wc-toast>
<div x-data="analytics">
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] before:mr-1 rtl:before:ml-1 ">
            <span>Buy Logs</span>
        </li>
    </ul>

    <div class="panel p-3 my-4 flex items-center text-primary overflow-x-auto whitespace-nowrap">
        <div class="ring-2 ring-primary/30 rounded-full bg-primary text-white p-1.5 ltr:mr-3 rtl:ml-3">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5">
                <path d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z" stroke="currentColor" stroke-width="1.5"></path>
                <path opacity="0.5" d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            </svg>
        </div>
        <span class="ltr:mr-3 rtl:ml-3 float-right">If You Are Facing Any Problem Please Contact Us</span><a href="<?php echo $site_data['channel_url']; ?>" target="_blank"><button type="button" class="btn btn-outline-primary btn-sm">Contact Us</button></a>
    </div>

    <input type="hidden" name="tokens" id="tokens" value="<?php echo $_SESSION['token']; ?>">
    <div class="panel py-3 mb-5">
        <!--<div class="mb-3">-->
        <!--    <h5 class="font-semibold text-lg dark:text-white-light">Logs For You</h5>-->
        <!--</div>-->
        <div class="mb-4">
            <input type="text" id="logSearch" placeholder="Search logs by name..." class="form-input w-full px-4 py-2 border rounded-md" onkeyup="filterLogs()">
        </div>
        <div class="space-y-4">
            <?php foreach ($log_data as $index => $entry): ?>
            <div class="category-section" data-category-index="<?php echo $index; ?>">
                <!-- Category Header -->
                <div class="bg-primary text-white p-2 rounded-lg shadow mb-3 flex items-center">
                    <h2 class="text-xl font-bold"><?php echo htmlspecialchars($entry['category']['name']); ?></h2>
                </div>
                <hr />
                 <div class="space-y-4 category-products">
                        <?php foreach ($entry['products'] as $product): ?>
                            <div class="border rounded-lg p-4 flex flex-col sm:flex-row justify-between sm:items-center shadow hover:shadow-lg transition log-item" data-name="<?php echo strtolower(htmlspecialchars($product['name'])); ?>" data-category-index="<?php echo $index; ?>">
                                <div class="mb-4 sm:mb-0">
                                    <h3 class="text-lg md:text-sm sm:text-xs font-bold uppercase mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="text-sm sm:text-xs text-gray-500 mb-2"><?= $product['description'] ?></p>
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-primary text-white px-2 py-0.5 rounded text-sm font-bold">Stock: <?= $product['in_stock'] ?></span>
                                    </div>
                                </div>
                                <div class="sm:text-right">
                                    <div class="text-success font-bold text-lg">₦<?= number_format($product['price'], 2) ?></div>
                                    <?php
                                    if($product['in_stock'] > 0){
                                    ?>
                                    <button class="bg-primary text-white px-4 py-1 rounded mt-2 buy-btn w-full sm:w-auto"
                                            data-id="<?= $product['id'] ?>"
                                            data-name="<?= htmlspecialchars($product['name']) ?>"
                                            data-price="<?= $product['price'] ?>"
                                            data-stock="<?= $product['in_stock'] ?>"
                                            data-description="<?= htmlspecialchars($product['description']) ?>">
                                        BUY NOW
                                    </button>
                                    <?php
                                    }else{
                                    ?>
                                    <button class="bg-danger text-white px-4 py-1 rounded mt-2 buy-btn w-full sm:w-auto" disabled>
                                        OUT OF STOCK
                                    </button>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <!--<div class="border border-[#ebedf2] rounded dark:bg-[#1b2e4b] dark:border-0 log-item" data-name="<?php echo strtolower(htmlspecialchars($product['name'])); ?>" data-category-index="<?php echo $index; ?>">-->
                            <!--    <div class="flex items-center justify-between p-4 py-2">-->
                            <!--        <div class="grid place-content-center w-9 h-9 rounded-md bg-primary-light dark:bg-primary text-primary dark:text-primary-light">-->
                            <!--            <svg class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">-->
                            <!--                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.4036 22.4797L10.6787 22.015C11.1195 21.2703 11.3399 20.8979 11.691 20.6902C12.0422 20.4825 12.5001 20.4678 13.4161 20.4385C14.275 20.4111 14.8523 20.3361 15.3458 20.1317C16.385 19.7012 17.2106 18.8756 17.641 17.8365C17.9639 17.0571 17.9639 16.0691 17.9639 14.093V13.2448C17.9639 10.4683 17.9639 9.08006 17.3389 8.06023C16.9892 7.48958 16.5094 7.0098 15.9388 6.66011C14.919 6.03516 13.5307 6.03516 10.7542 6.03516H8.20964C5.43314 6.03516 4.04489 6.03516 3.02507 6.66011C2.45442 7.0098 1.97464 7.48958 1.62495 8.06023C1 9.08006 1 10.4683 1 13.2448V14.093C1 16.0691 1 17.0571 1.32282 17.8365C1.75326 18.8756 2.57886 19.7012 3.61802 20.1317C4.11158 20.3361 4.68882 20.4111 5.5477 20.4385C6.46368 20.4678 6.92167 20.4825 7.27278 20.6902C7.6239 20.8979 7.84431 21.2703 8.28514 22.015L8.5602 22.4797C8.97002 23.1721 9.9938 23.1721 10.4036 22.4797ZM13.1928 14.5171C13.7783 14.5171 14.253 14.0424 14.253 13.4568C14.253 12.8713 13.7783 12.3966 13.1928 12.3966C12.6072 12.3966 12.1325 12.8713 12.1325 13.4568C12.1325 14.0424 12.6072 14.5171 13.1928 14.5171ZM10.5422 13.4568C10.5422 14.0424 10.0675 14.5171 9.48193 14.5171C8.89637 14.5171 8.42169 14.0424 8.42169 13.4568C8.42169 12.8713 8.89637 12.3966 9.48193 12.3966C10.0675 12.3966 10.5422 12.8713 10.5422 13.4568ZM5.77108 14.5171C6.35664 14.5171 6.83133 14.0424 6.83133 13.4568C6.83133 12.8713 6.35664 12.3966 5.77108 12.3966C5.18553 12.3966 4.71084 12.8713 4.71084 13.4568C4.71084 14.0424 5.18553 14.5171 5.77108 14.5171Z" fill="currentColor" />-->
                            <!--                <path opacity="0.5" d="M15.486 1C16.7529 0.999992 17.7603 0.999986 18.5683 1.07681C19.3967 1.15558 20.0972 1.32069 20.7212 1.70307C21.3632 2.09648 21.9029 2.63623 22.2963 3.27821C22.6787 3.90219 22.8438 4.60265 22.9226 5.43112C22.9994 6.23907 22.9994 7.24658 22.9994 8.51343V9.37869C22.9994 10.2803 22.9994 10.9975 22.9597 11.579C22.9191 12.174 22.8344 12.6848 22.6362 13.1632C22.152 14.3323 21.2232 15.2611 20.0541 15.7453C20.0249 15.7574 19.9955 15.7691 19.966 15.7804C19.8249 15.8343 19.7039 15.8806 19.5978 15.915H17.9477C17.9639 15.416 17.9639 14.8217 17.9639 14.093V13.2448C17.9639 10.4683 17.9639 9.08006 17.3389 8.06023C16.9892 7.48958 16.5094 7.0098 15.9388 6.66011C14.919 6.03516 13.5307 6.03516 10.7542 6.03516H8.20964C7.22423 6.03516 6.41369 6.03516 5.73242 6.06309V4.4127C5.76513 4.29934 5.80995 4.16941 5.86255 4.0169C5.95202 3.75751 6.06509 3.51219 6.20848 3.27821C6.60188 2.63623 7.14163 2.09648 7.78361 1.70307C8.40759 1.32069 9.10805 1.15558 9.93651 1.07681C10.7445 0.999986 11.7519 0.999992 13.0188 1H15.486Z" fill="currentColor" />-->
                            <!--            </svg>-->
                            <!--        </div>-->
                            <!--        <div class="ltr:ml-4 rtl:mr-4 flex items-start justify-between flex-auto font-semibold">-->
                            <!--            <h4 class="text-sm md:text-base text-dark dark:text-white">-->
                            <!--                <span class="block mb-2"><?php echo htmlspecialchars($product['name']); ?></span>-->
                            <!--                <div class="flex gap-2 items-center">-->
                            <!--                    <span class="text-sm px-1 rounded bg-primary text-white dark:text-white-light">-->
                            <!--                        ₦<?php echo number_format($product['price'], 0); ?>-->
                            <!--                    </span>-->
                            <!--                    |-->
                            <!--                    <span class="text-sm px-1 rounded bg-primary text-white dark:text-white-light">-->
                            <!--                        <?php echo $product['in_stock']; ?> pcs-->
                            <!--                    </span>-->
                            <!--                </div>-->
                            <!--            </h4>-->
                            <!--        </div>-->
                            <!--        <div class="ms-auto">-->
                            <!--            <a href="log-detail?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary btn-sm">-->
                            <!--                <i class='text-sm bx bx-right-arrow-alt'></i>-->
                            <!--            </a>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                        <?php endforeach; ?>
                    </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Purchase Modal -->
    <div id="purchaseModal" class="modal-overlay">
        <div class="flex h-full items-center justify-center">
            <div class="modal-content">
            
            <!-- Close button -->
            <button class="close-btn" id="closeModal">&times;</button>
    
            <div class="modal-grid">
                
                <!-- Left Side -->
                <div class="modal-left">
                    <h3 id="modalProductName" class="product-name">Product Name Here</h3>
                    <span id="modalStock" class="stock-badge">Stock: 0</span>
                    <div id="modalPrice" class="product-price">₦0.00</div>
                    <p id="modalDescription" class="product-description">
                        Product description goes here.
                    </p>
                </div>
    
                <!-- Right Side -->
                <div class="modal-right">
                    <h4 class="section-title">PURCHASE INFORMATION</h4>
                    <div class="info-row">My balance: <span id="modalBalance" class="balance">₦<?php echo number_format($userwallet['balance'],0,'',','); ?></span></div>
    
                    <!-- Quantity Controls -->
                    <div class="qty-container">
                        <button id="decreaseQty" class="qty-btn">-</button>
                        <input id="quantity" type="number" value="1" min="1" class="qty-input">
                        <button id="increaseQty" class="qty-btn">+</button>
                    </div>
    
                    <!-- Price Info -->
                    <div class="price-info">
                        <div class="info-row">Price: <span id="priceValue">₦0.00</span></div>
                        <div class="info-row total">Total payment: <span id="totalValue">₦0.00</span></div>
                    </div>
    
                    <!-- Pay Button -->
                    <button id="payBtn" class="pay-btn">PAY</button>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    /* Overlay */
    .modal-overlay {
        display: none; /* Hidden by default */
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }
    
    /* Modal Box */
    .modal-content {
        background: white;
        border-radius: 10px;
        max-width: 800px;
        width: 90%;
        padding: 20px 30px;
        position: relative;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        animation: fadeIn 0.2s ease-in-out;
    }
    
    /* Close Button */
    .close-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #777;
    }
    .close-btn:hover { color: black; }
    
    /* Grid Layout */
    .modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    
    /* Product Info */
    .product-name {
        font-size: 20px;
        font-weight: bold;
    }
    .stock-badge {
        background: #6a0dad;
        color: white;
        padding: 4px 10px;
        border-radius: 5px;
        font-size: 14px;
        display: inline-block;
        margin-top: 8px;
    }
    .product-price {
        color: green;
        font-weight: bold;
        font-size: 18px;
        margin-top: 10px;
    }
    .product-description {
        color: #555;
        margin-top: 10px;
        font-size: 14px;
    }
    
    /* Right Side */
    .section-title {
        font-weight: bold;
        margin-bottom: 10px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        margin: 5px 0;
    }
    .balance {
        font-weight: bold;
        color: green;
    }
    .discount {
        color: red;
    }
    .total {
        font-weight: bold;
        font-size: 16px;
        color: #007BFF;
    }
    
    /* Quantity Controls */
    .qty-container {
        display: flex;
        align-items: center;
        margin: 10px 0;
    }
    .qty-btn {
        background: #eee;
        border: none;
        padding: 6px 12px;
        font-size: 16px;
        cursor: pointer;
    }
    .qty-btn:hover {
        background: #ddd;
    }
    .qty-input {
        width: 50px;
        text-align: center;
        border: 1px solid #ccc;
        margin: 0 5px;
    }
    
    /* Pay Button */
    .pay-btn {
        width: 100%;
        background: #007BFF;
        color: white;
        padding: 10px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .pay-btn:hover {
        background: #0056b3;
    }
    
    @media (max-width: 580px){
        .modal-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.97); }
        to { opacity: 1; transform: scale(1); }
    }
    </style>


    <style>
    body.mobile-app-view .float {
        display: none;
    }

    .float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 80px;
        right: 40px;
        background-color: rgb(73 111 217);
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        z-index: 100;
    }
    
    .how-to-float {
        position: fixed;
        bottom: 120px;
        right: 30px;
    }
</style>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!--<a href="https://chat.whatsapp.com/CiawzLKiZXNKaaV5H6time" class="float-message float1" target="_blank"> -->
    <!--    <i class="fa fa-whatsapp"></i> Join Group-->
    <!--</a> -->
    <!--<a href="https://t.me/no1verify" class="float" target="_blank">-->
    <!--    <i class="fa fa-comment my-float"></i>-->
    <!--</a>-->
    <style>
        .float-message {
          background-color: #FFFFFF;
          border: 0;
          border-radius: .5rem;
          box-sizing: border-box;
          color: #111827;
          font-size: .875rem;
          font-weight: 600;
          line-height: 1.25rem;
          padding: .75rem 1rem;
          text-align: center;
          text-decoration: none #D1D5DB solid;
          text-decoration-thickness: auto;
          box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
          cursor: pointer;
          user-select: none;
          -webkit-user-select: none;
          touch-action: manipulation;
        }
        
        .float-message i{
            color: #25d366;
        }
        
        .float1{ 
        position:fixed; 
            bottom: 70px;
            right: 30px; 
            z-index:1000; 
        } 
        .float2{ 
        position:fixed; 
            bottom: 20px;
            right: 30px; 
            z-index:1000; 
        } 
    </style> 
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        function filterLogs() {
            const input = document.getElementById('logSearch');
            const filter = input.value.toLowerCase();
            const logItems = document.querySelectorAll('.log-item');
            const categories = document.querySelectorAll('.category-section');
        
            // First, hide all log items that don't match
            logItems.forEach(item => {
                const name = item.getAttribute('data-name');
                item.style.display = name.includes(filter) ? '' : 'none';
            });
        
            // Then, check each category to see if any of its log items are visible
            categories.forEach(category => {
                const index = category.getAttribute('data-category-index');
                const itemsInCategory = category.querySelectorAll(`.log-item[data-category-index="${index}"]`);
                let anyVisible = false;
                itemsInCategory.forEach(item => {
                    if (item.style.display !== 'none') {
                        anyVisible = true;
                    }
                });
                category.style.display = anyVisible ? '' : 'none';
            });
        }
    </script>
    <script type="module">
        import { toast } from 'https://esm.sh/wc-toast';
        // Store product data when clicking Buy Now
        $(document).on('click', '.buy-btn', function() {
            let product = $(this).data(); // expects data-id, data-name, data-stock, data-price, data-description
        
            // Fill modal fields
            $('#modalProductName').text(product.name);
            $('#modalStock').text(`Stock: ${product.stock}`);
            $('#modalPrice').text(`₦${product.price.toFixed(2)}`);
            $('#priceValue').text(`₦${product.price.toFixed(2)}`);
            $('#totalValue').text(`₦${product.price.toFixed(2)}`);
            $('#modalDescription').text(product.description);
            $('#quantity').val(1);
        
            // Save product info for later
            $('#payBtn').data('productId', product.id);
            $('#payBtn').data('price', product.price);
        
            // Show modal
            $('#purchaseModal').fadeIn(200);
        });
        
        // Close modal
        $('#closeModal').click(function() {
            $('#purchaseModal').fadeOut(200);
        });
        
        // Quantity change events
        $('#increaseQty').click(function() {
            let qty = parseInt($('#quantity').val()) + 1;
            $('#quantity').val(qty);
            updateTotal();
        });
        $('#decreaseQty').click(function() {
            let qty = parseInt($('#quantity').val());
            if(qty > 1) {
                $('#quantity').val(qty - 1);
                updateTotal();
            }
        });
        $('#quantity').on('input', function() {
            if($(this).val() < 1) $(this).val(1);
            updateTotal();
        });
        
        function updateTotal() {
            let price = $('#payBtn').data('price');
            let qty = parseInt($('#quantity').val());
            let total = price * qty;
            $('#totalValue').text(`₦${total.toFixed(2)}`);
        }
        
        // Handle payment
        $('#payBtn').click(function() {
            let productId = $(this).data('productId');
            let qty = parseInt($('#quantity').val());
            let token = $('#tokens').val();
            
            if (qty < 1) {
                toast.error("Please enter a valid quantity.");
                return;
            }
            
            $.ajax({
                url: '/api/service/buyLog',
                method: 'POST',
                data: {
                    action: "place_order",
                    quantity: qty,
                    product_id: productId,
                    token: token
                },
                beforeSend: function() {
                    $('#payBtn').prop("disabled", true);
                    $('#payBtn').html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span> Placing Order...');
                },
                success: function(response) {
                    $('#payBtn').html("PAY");
                    $('#payBtn').prop("disabled", false);
                    var res = JSON.parse(response);
                    if (res.status == '200') {
                        toast.success(res.message)
                        setTimeout(() => {
                            window.location.href = "log-orders";
                        }, 1500);
                    } else {
                        toast.error("Error: " + res.message);
                    }
                    $('#purchaseModal').fadeOut(200);
                },
                error: function(xhr) {
                    toast.error("An error occurred while placing the order.");
                    $('#payBtn').html("PAY");
                    $('#payBtn').prop("disabled", false);
                }
            });
        });
    </script>
    <script>
        // var notyf = new Notyf({
        //     duration: 5000,
        //     position: {x:'right',y:'top'},
        //     ripple: true,
        //     dismissible: true
        // });
        var notyf = new Notyf();

        <?php
        if (isset($_SESSION['success_message'])) {
            echo "notyf.success({
        message: '" . $_SESSION['success_message'] . "',
      duration: 5000,
      position: {x:'right',y:'top'}});";
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo "notyf.error({
      message: '" . $_SESSION['error_message'] . "',
      duration: 5000,
      position: {x:'right',y:'top'} 
      });";
            unset($_SESSION['error_message']);
        }
        ?>
    </script>
    <?php include 'include/footer-main.php'; ?>