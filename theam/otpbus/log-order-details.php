<?php
$page_name = "Log Details";
include 'include/header-main.php';
?>
<script src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/simple-datatables.js"></script>

<script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/apexcharts.js"></script>
<script>
    $(document).ready(function() {
        // Remove "active" class from all <a> elements
        $('#slide-dashboard').removeClass("active");

        // Add "active" class to the specific element with ID "faq"
        $("#slider-numbers").addClass("active");
    });
</script>
<style>
    .pl1 {
        display: block;
        width: 8em;
        height: 8em;
    }

    .pl1__g,
    .pl1__rect {
        animation: pl1-a 1.5s cubic-bezier(0.65, 0, 0.35, 1) infinite;
    }

    .pl1__g {
        transform-origin: 64px 64px;
    }

    .pl1__rect:first-child {
        animation-name: pl1-b;
    }

    .pl1__rect:nth-child(2) {
        animation-name: pl1-c;
    }

    @keyframes pl1-a {
        from {
            transform: rotate(0);
        }

        80%,
        to {
            animation-timing-function: steps(1, start);
            transform: rotate(90deg);
        }
    }

    @keyframes pl1-b {
        from {
            animation-timing-function: cubic-bezier(0.33, 0, 0.67, 0);
            width: 40px;
            height: 40px;
        }

        20% {
            animation-timing-function: steps(1, start);
            width: 40px;
            height: 0;
        }

        60% {
            animation-timing-function: cubic-bezier(0.65, 0, 0.35, 1);
            width: 0;
            height: 40px;
        }

        80%,
        to {
            width: 40px;
            height: 40px;
        }
    }

    @keyframes pl1-c {
        from {
            animation-timing-function: cubic-bezier(0.33, 0, 0.67, 0);
            width: 40px;
            height: 40px;
            transform: translate(0, 48px);
        }

        20% {
            animation-timing-function: cubic-bezier(0.33, 1, 0.67, 1);
            width: 40px;
            height: 88px;
            transform: translate(0, 0);
        }

        40% {
            animation-timing-function: cubic-bezier(0.33, 0, 0.67, 0);
            width: 40px;
            height: 40px;
            transform: translate(0, 0);
        }

        60% {
            animation-timing-function: cubic-bezier(0.33, 1, 0.67, 1);
            width: 88px;
            height: 40px;
            transform: translate(0, 0);
        }

        80%,
        to {
            width: 40px;
            height: 40px;
            transform: translate(48px, 0);
        }
    }
</style>
<div x-data="basic">
    <input type="hidden" id="token" value="<?php echo $_SESSION['token']; ?>">
    <div id="empty_data">
    </div>
    <div class="mb-2 flex">
        <div class="align-self-end">
            <a href="log-orders" class="text-primary hover:underline">Dashboard</a>
        </div>
    </div>
    <div class="panel" id="display_data">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-lg dark:text-white-light">Log Order Details</h3>
            <a href="download-log-order.php?order_id=<?php echo $_GET['order_id'] ?>&token=<?php echo $_SESSION['token']; ?>" target="_blank" class="btn btn-primary">Download Log Order</a>
        </div>
        <h6 class="font-bold text-underline text-base dark:text-white-light mt-3">Item Ordered:</h6>
        <p class="font-bold text-sm mt-3 dark:text-white-light">
            <?php echo $product_ordered['name'] ?>
        </p>
        <p class="font-bold text-sm mt-3 dark:text-white-light">
            <?php echo $product_ordered['description'] ?>
        </p>
        <table id="trans_tb" class=" panel whitespace-nowrap table-hover">
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("basic", () => ({
            datatable: null,
            init() {
                var token = $("#token").val();
                var params = {
                    token: token,
                    order_id: <?php echo $_GET['order_id']?>
                };

                // Store a reference to the Alpine component
                var self = this;
                var cardContainer = document.getElementById("empty_data");
                var cardContainer2 = document.getElementById("display_data");
                cardContainer2.classList.add("hidden");
                cardContainer.innerHTML = '';
                cardContainer.innerHTML = `   <div class="fixed  inset-0 grid  place-content-center">
   <center><main>
	<svg height="128px" width="128px" viewBox="0 0 128 128" class="pl1">
		<defs>
			<linearGradient y2="1" x2="1" y1="0" x1="0" id="pl-grad">
				<stop stop-color="#000" offset="0%"></stop>
				<stop stop-color="#fff" offset="100%"></stop>
			</linearGradient>
			<mask id="pl-mask">
				<rect fill="url(#pl-grad)" height="128" width="128" y="0" x="0"></rect>
			</mask>
		</defs>
		<g fill="var(--primary)">
			<g class="pl1__g">
				<g transform="translate(20,20) rotate(0,44,44)">
					<g class="pl1__rect-g">
						<rect height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
						<rect transform="translate(0,48)" height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
					</g>
					<g transform="rotate(180,44,44)" class="pl1__rect-g">
						<rect height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
						<rect transform="translate(0,48)" height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
					</g>
				</g>
			</g>
		</g>
		<g mask="url(#pl-mask)" fill="hsl(343,90%,50%)">
			<g class="pl1__g">
				<g transform="translate(20,20) rotate(0,44,44)">
					<g class="pl1__rect-g">
						<rect height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
						<rect transform="translate(0,48)" height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
					</g>
					<g transform="rotate(180,44,44)" class="pl1__rect-g">
						<rect height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
						<rect transform="translate(0,48)" height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
					</g>
				</g>
			</g>
		</g>
	</svg>
</main></center>
</div>             `;
                // Make AJAX request to fetch data
                $.ajax({
                    url: "api/service/getLogOrderDetails",
                    method: "GET",
                    data: params,
                    dataType: "json",
                    success: function(data) {
                        var cardContainer = document.getElementById("empty_data");
                        var cardContainer2 = document.getElementById("display_data");

                        cardContainer.innerHTML = '';
                        cardContainer2.classList.remove("hidden");

                        if (data && data.status === "200" && data.data && data.data.length > 0) {
                            var tableRows = data.data.map(function(item, index) {
                                return "<tr>" +
                                    "<td>" + (index + 1) + "</td>" +
                                    "<td>" + item.product_details + "</td>" +
                                    "<td>" + "<button class='btn btn-primary' data-log='" + item.product_details + "' onclick='copyTextFromButton(this)'>Copy</button>" + "</td>" +
                                    "</tr>";
                            });

                            // Append rows to the table body
                            $("#trans_tb tbody").html(tableRows.join(""));

                            // Initialize datatable with fetched data
                            self.datatable = new simpleDatatables.DataTable('#trans_tb', {
                                data: {
                                    headings: ["S/NM", "Details", "Action"],
                                },
                                sortable: true,
                                searchable: true,
                                perPage: 20,
                                perPageSelect: false,
                                firstLast: true,
                                firstText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                                lastText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                                prevText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                                nextText: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                                labels: {
                                    perPage: "{select}"
                                },
                            });
                        } else {
                            var cardContainer = document.getElementById("empty_data");
                            var cardContainer2 = document.getElementById("display_data");
                            cardContainer2.classList.add("hidden");
                            cardContainer.innerHTML = '';
                            cardContainer.innerHTML = `   <div class="fixed  inset-0 grid  place-content-center">
   <center><img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png"  height="150" width="150"></center>
     <p class="text-center font-bold text-2xl">Empty History </p>
     

</div>             `;
                        }
                    },
                    error: function(xhr, status, error) {
                        var cardContainer = document.getElementById("empty_data");
                        var cardContainer2 = document.getElementById("display_data");
                        cardContainer2.classList.add("hidden");
                        cardContainer.innerHTML = '';
                        cardContainer.innerHTML = `   <div class="fixed  inset-0 grid  place-content-center">
   <center><img src="https://cdn-icons-png.flaticon.com/512/5741/5741333.png"  height="150" width="150"></center>
     <p class="text-center font-bold text-2xl">System Error</p>
     

</div>             `;
                    }
                });
            }
        }));
    });

    function copyTextFromButton(btn) {
        const text = btn.getAttribute("data-log");
    
        const textarea = document.createElement("textarea");
        textarea.value = text;
        textarea.style.position = "absolute";
        textarea.style.left = "-9999px";
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
    
        Toastify({
            text: "Logs Copied: " + text,
            duration: 1000,
            gravity: "top",
            position: 'center',
        }).showToast();
    }

</script>


<?php include 'include/footer-main.php'; ?>
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
    <a href="https://t.me/no1verify" class="float-message float2" target="_blank"> 
        <i class="fa fa-telegram"></i> Message Support
    </a> 
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
            color: #496FD9;
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