
<?php
$page_name = "Dashboard";
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
<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md hydrated"></i>
                </a>
                <div class="pageTitle">Buy Logs</div>
            </div>
            <div class="right">
            </div>
        </div>
    </div>

    <div id="appCapsule">
       <div class="small-wallet-card mb-2">
            <div class="detail">
                <strong>Wallet Balance</strong>
                <p>₦<span id="current_balance"><?php echo $userwallet['balance'];?></span></p>
            </div>
            <div class="right">
                <a href="recharge" class="w-100 btn btn-primary ls-1">
                    Fund Wallet
                </a>
            </div>
       </div>
       <input type="hidden" id="token" value="<?php echo $_SESSION['token']; ?>">

        <div class="section logs-section mt-2 p-0">
          <div class="card shadow-sm">
            <div class="card-body">
              <style>
                /* Modern category + product card layout (matches provided reference) */
                .logs-wrap { display: flex; flex-direction: column; gap: 18px; }
                .logs-cat { border-radius: 18px; overflow: hidden; border: 1px solid rgba(0,0,0,.06); background: #fff; }
                .logs-cat-head {
                  display: flex; align-items: center; justify-content: space-between;
                  padding: 12px 12px;
                  background: linear-gradient(180deg, #1b2330 0%, #121826 100%);
                  color: #fff;
                }
                .logs-cat-title { display:flex; align-items:center; gap:10px; font-weight:800; letter-spacing:.4px; text-transform: uppercase; font-size: 12px; }
                .logs-cat-icon {
                  width: 26px; height: 26px; border-radius: 10px;
                  display: grid; place-items: center;
                  background: rgba(255,255,255,.12);
                  border: 1px solid rgba(255,255,255,.18);
                }
                .logs-cat-action {
                  border: 1px solid rgba(255,255,255,.18);
                  background: rgba(255,255,255,.08);
                  color:#fff;
                  padding: 7px 10px;
                  border-radius: 12px;
                  font-weight: 700;
                  font-size: 11px;
                }
                .logs-cat-body { padding: 12px; display:flex; flex-direction:column; gap:10px; background:#f6f7fb; }
                .log-card {
                  display:flex; align-items:center; justify-content: space-between; gap: 12px;
                  background:#fff;
                  border: 1px solid rgba(0,0,0,.07);
                  border-radius: 16px;
                  padding: 10px;
                  box-shadow: 0 1px 0 rgba(0,0,0,.03);
                }
                .log-left { display:flex; align-items:center; gap: 12px; min-width: 0; }
                .log-avatar {
                  width: 50px; height: 50px; border-radius: 16px;
                  background: radial-gradient(circle at 30% 20%, rgba(67,97,238,.18), rgba(0,0,0,0));
                  border: 1px solid rgba(0,0,0,.06);
                  display:grid; place-items:center;
                  flex: 0 0 auto;
                }
                .log-avatar span { font-weight: 900; color:#1b2330; }
                .log-meta { min-width: 0; }
                .log-name { font-weight: 800; margin: 0; font-size: 13px; color:#121826; }
                .log-desc { margin: 2px 0 0 0; font-size: 11px; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 210px; }
                .log-pills { display:flex; align-items:center; gap: 8px; margin-top: 7px; }
                .pill { display:inline-flex; align-items:center; justify-content:center; padding: 5px 10px; border-radius: 999px; font-weight: 800; font-size: 11px; border: 1px solid rgba(0,0,0,.08); }
                .pill-stock { background: #f3f4f6; color:#111827; }
                .pill-price { background: #e9f7ef; color:#116b3d; border-color: rgba(17,107,61,.18); }
                .log-cta {
                  width: 42px; height: 42px; border-radius: 14px;
                  display:grid; place-items:center;
                  border: 1px solid rgba(0,0,0,.08);
                  background: #eef2ff;
                  color:#1f2a5a;
                  flex: 0 0 auto;
                }
                .log-cta.disabled { background:#f3f4f6; color:#9ca3af; }
                .log-cta i { font-size: 17px; }
              </style>
              <div class="mb-4">
                <input
                  type="text"
                  name="search"
                  id="logSearch"
                  onkeyup="filterLogs()"
                  class="form-control"
                  placeholder="Search Logs by name..."
                  value=""
                />
              </div>
              <div class="mb-4">
                <input type="hidden" name="tokens" id="tokens" value="<?php echo $_SESSION['token']; ?>">
                <div class="logs-wrap">
                  <?php foreach ($log_data as $index => $entry): ?>
                    <?php
                      $catName = (string)($entry['category']['name'] ?? '');
                      $catInitials = strtoupper(mb_substr(preg_replace('/[^A-Za-z0-9]+/', ' ', $catName), 0, 1));
                      if ($catInitials === '') $catInitials = 'L';
                    ?>
                    <div class="logs-cat category-section" data-category-index="<?php echo $index; ?>">
                      <div class="logs-cat-head">
                        <div class="logs-cat-title">
                          <div class="logs-cat-icon"><?php echo htmlspecialchars($catInitials); ?></div>
                          <div><?php echo htmlspecialchars($catName); ?></div>
                        </div>
                        <button type="button" class="logs-cat-action" onclick="toggleCategory(<?php echo $index; ?>)">See all →</button>
                      </div>

                      <div class="logs-cat-body category-products">
                        <?php foreach ($entry['products'] as $pIndex => $product): ?>
                          <?php
                            $pName = (string)($product['name'] ?? '');
                            $pInitials = strtoupper(mb_substr(preg_replace('/[^A-Za-z0-9]+/', ' ', $pName), 0, 1));
                            if ($pInitials === '') $pInitials = 'L';
                            $inStock = (int)($product['in_stock'] ?? 0);
                            // Show at least 10 products per category by default
                            $hiddenClass = ($pIndex >= 10) ? ' d-none log-hidden' : '';
                          ?>
                          <div class="log-item log-card<?php echo $hiddenClass; ?>"
                               data-name="<?php echo strtolower(htmlspecialchars($pName)); ?>"
                               data-category-index="<?php echo $index; ?>">
                            <div class="log-left">
                              <div class="log-avatar"><span><?php echo htmlspecialchars($pInitials); ?></span></div>
                              <div class="log-meta">
                                <p class="log-name"><?php echo htmlspecialchars($pName); ?></p>
                                <p class="log-desc"><?php echo htmlspecialchars((string)($product['description'] ?? '')); ?></p>
                                <div class="log-pills">
                                  <span class="pill pill-stock"><?php echo $inStock; ?></span>
                                  <span class="pill pill-price">₦<?php echo number_format((float)($product['price'] ?? 0), 0); ?></span>
                                </div>
                              </div>
                            </div>

                            <?php if ($inStock > 0): ?>
                              <button class="log-cta buy-btn"
                                      data-id="<?= $product['id'] ?>"
                                      data-name="<?= htmlspecialchars($pName) ?>"
                                      data-price="<?= $product['price'] ?>"
                                      data-stock="<?= $inStock ?>"
                                      data-description="<?= htmlspecialchars((string)($product['description'] ?? '')) ?>">
                                <i class="ri-shopping-cart-2-line"></i>
                              </button>
                            <?php else: ?>
                              <button class="log-cta disabled" disabled>
                                <i class="ri-shopping-cart-2-line"></i>
                              </button>
                            <?php endif; ?>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
            </div>
            </div>
          </div>
        </div>
    </div>

    <script>
      function toggleCategory(index) {
        var section = document.querySelector('.category-section[data-category-index="' + index + '"]');
        if (!section) return;
        section.querySelectorAll('.log-hidden').forEach(function(el) {
          el.classList.toggle('d-none');
        });
      }
    </script>

    <div id="purchaseModal" class="modal-overlay">
        <div class="d-flex h-100 align-items-center justify-content-center">
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
    </div>

    <style>
        /* Overlay */
        .modal-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 5000;
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
            background: #6a0dad33;
            border: 1px solid #6a0dad;
            color: #6a0dad;
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
            color: #FF6D00;
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
            background: #FF6D00;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .pay-btn:hover {
            background: #FF6D0033;
            border: 1pxx solid #FF6D00;
            color: #FF6D00;
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

    <?php
        include("include/bottom-menu.php")
    ?>
</div>
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
<script >
  const toast = new Notyf({
    position: {x:'right',y:'top'}
  });
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
      url: 'api/service/buyLog',
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
<?php
    include 'include/footer-main.php';
?>