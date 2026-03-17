<?php 
$page_name = "Transaction";
include 'include/header-main.php'; 
?>
<script src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/simple-datatables.js"></script>

<script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/apexcharts.js"></script>
<script>
        $(document).ready(function() {
            // Remove "active" class from all <a> elements
            $('#slide-dashboard').removeClass("active");
            
            // Add "active" class to the specific element with ID "faq"
            $("#slider-transaction").addClass("active");
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
  animation: pl1-a 1.5s cubic-bezier(0.65,0,0.35,1) infinite;
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
    animation-timing-function: steps(1,start);
    transform: rotate(90deg);
  }
}

@keyframes pl1-b {
  from {
    animation-timing-function: cubic-bezier(0.33,0,0.67,0);
    width: 40px;
    height: 40px;
  }

  20% {
    animation-timing-function: steps(1,start);
    width: 40px;
    height: 0;
  }

  60% {
    animation-timing-function: cubic-bezier(0.65,0,0.35,1);
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
    animation-timing-function: cubic-bezier(0.33,0,0.67,0);
    width: 40px;
    height: 40px;
    transform: translate(0,48px);
  }

  20% {
    animation-timing-function: cubic-bezier(0.33,1,0.67,1);
    width: 40px;
    height: 88px;
    transform: translate(0,0);
  }

  40% {
    animation-timing-function: cubic-bezier(0.33,0,0.67,0);
    width: 40px;
    height: 40px;
    transform: translate(0,0);
  }

  60% {
    animation-timing-function: cubic-bezier(0.33,1,0.67,1);
    width: 88px;
    height: 40px;
    transform: translate(0,0);
  }

  80%,
      to {
    width: 40px;
    height: 40px;
    transform: translate(48px,0);
  }
}
</style>    
<div x-data="basic">
      <input type="hidden" id="token" value="<?php echo $_SESSION['token'];?>"> 
<div id="empty_data">
</div>                                                                                           
    <div class="panel" id="display_data">
    <h5 class="font-semibold text-lg dark:text-white-light">Transactions</h5>    
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
                var params = { token: token };

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
                $.ajax({
                    url: "api/service/getTransaction",
                    method: "GET",
                    data: params,
                    dataType: "json",
                    success: function (data) {
                          var cardContainer = document.getElementById("empty_data");
        var cardContainer2 = document.getElementById("display_data");
 
                                      cardContainer.innerHTML = '';
                        cardContainer2.classList.remove("hidden");
                               
                        if (data && data.status === "200" && data.data && data.data.length > 0) {
                              var tableRows = data.data.map(function (item) {
                            if(item.status === "1"){
                            var status_bages = '<span class="badge bg-success rounded-full">Success</span>';
                            }else{
                              if(item.status === "2"){
                            var status_bages = '<span class="badge bg-warning rounded-full">Pending</span>';
                            }else{
                             var status_bages = '<span class="badge bg-success rounded-full">Rejected</span>'; 
                             }                      
                            }
                                return "<tr>" +
                                    "<td>" + item.id + "</td>" +
                                    "<td>" + item.type + "</td>" +
                                    "<td>₦" + item.amount + "</td>" +
                                    "<td>" + item.date + "</td>" +
                                    "<td>" + status_bages + "</td>" +
                                    "</tr>";
                            });

                                   $("#trans_tb tbody").html(tableRows.join(""));

                                    self.datatable = new simpleDatatables.DataTable('#trans_tb', {
                             data: {
                        headings: ["ID", "Type", "Amount", "Date", "Status"],
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
                    error: function (xhr, status, error) {
                                                        var cardContainer = document.getElementById("empty_data");
                                    var cardContainer2 = document.getElementById("display_data");
                                       cardContainer2.classList.add("hidden");
                                cardContainer.innerHTML = '';          
        cardContainer.innerHTML = `   <div class="fixed  inset-0 grid  place-content-center">
   <center><img src="https://cdn-icons-png.flaticon.com/512/5741/5741333.png"  height="150" width="150"></center>
     <p class="text-center font-bold text-2xl">Connection Error</p>
     

</div>             `;       
                    }
                });
            }
        }));
    });
</script>


<?php include 'include/footer-main.php'; ?>
