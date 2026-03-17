<?php 
$page_name = "Refer & Earn";
include 'include/header-main.php'; 
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
  
<script>
        $(document).ready(function() {
            // Remove "active" class from all <a> elements
            $('#slide-dashboard').removeClass("active");
            
            // Add "active" class to the specific element with ID "faq"
            $("#slide-refer").addClass("active");
        });
    </script> 
    <wc-toast id="tt" position="top-center"> </wc-toast>    
<script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/apexcharts.js"></script>
<div x-data="analytics">
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] before:mr-1 rtl:before:ml-1 ">
            <span>Refer & Earn</span>
        </li>
    </ul>
     
    <div class="panel mt-5">
     <h5 class="font-bold text-lg dark:text-white-light">Refer & Earn</h5> 
    <center><img src="https://png.pngtree.com/png-clipart/20230811/original/pngtree-refer-friend-loudspeaker-badge-picture-image_7857435.png" class="justify-center" height="220" width="250"></center>
    <div class="flex">
                <input id="upi_copy" type="text" value="<?php echo $site_data['web_url'];?>/register?ref_id=<?php echo $referwallet['own_code'];?>" class="form-input ltr:rounded-r-none rtl:rounded-l-none" readonly/>
                <div id="copyBtn" class="bg-[#eee] flex justify-center items-center ltr:rounded-r-md rtl:rounded-l-md px-3 font-semibold border border-transparent dark:border-[#17263c] dark:bg-[#1b2e4b]">
                    <i class="fa fa-clipboard text-2xl"></i>
                </div>
            </div>
              <h5 class="font-bold text-2xl dark:text-white-light text-center mt-2">Instructions</h5>
              <div class="mt-2 font-bold font-1xl">
              <p>1. Copy and Share Your link.</p>
              <p>2. Whenever your friend register using your link and add money .</p>
              <p>3. You will receive <?php echo $site_data['refer_percent'];?>% of deposit amount in your Refer wallet..</p>
              <p>4. You can directly use that balance to buy numbers.</p>
              </div>
      </div>
      </div>
              <div class="panel mt-3">
                <div class="mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Refer Data</h5>
                </div>
                <div class="space-y-4">
                    <div class="border border-[#ebedf2] rounded dark:bg-[#1b2e4b] dark:border-0">
                        <div class="flex items-center justify-between p-4 py-2">
                            <div class="grid place-content-center w-9 h-9 rounded-md bg-secondary-light dark:bg-secondary text-secondary dark:text-secondary-light">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                    <path d="M3.79424 12.0291C4.33141 9.34329 4.59999 8.00036 5.48746 7.13543C5.65149 6.97557 5.82894 6.8301 6.01786 6.70061C7.04004 6 8.40956 6 11.1486 6H12.8515C15.5906 6 16.9601 6 17.9823 6.70061C18.1712 6.8301 18.3486 6.97557 18.5127 7.13543C19.4001 8.00036 19.6687 9.34329 20.2059 12.0291C20.9771 15.8851 21.3627 17.8131 20.475 19.1793C20.3143 19.4267 20.1267 19.6555 19.9157 19.8616C18.7501 21 16.7839 21 12.8515 21H11.1486C7.21622 21 5.25004 21 4.08447 19.8616C3.87342 19.6555 3.68582 19.4267 3.5251 19.1793C2.63744 17.8131 3.02304 15.8851 3.79424 12.0291Z" stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5" d="M9 6V5C9 3.34315 10.3431 2 12 2C13.6569 2 15 3.34315 15 5V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path opacity="0.5" d="M9.1709 15C9.58273 16.1652 10.694 17 12.0002 17C13.3064 17 14.4177 16.1652 14.8295 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div class="ltr:ml-4 rtl:mr-4 flex items-start justify-between flex-auto font-semibold">
                                <h6 class="text-white-dark text-[13px] dark:text-white-dark">Total Referred User<span class="block text-base text-[#515365] dark:text-white-light"><?php echo $refer_users;?> </span></h6>
                             </div>
                        </div>
                    </div>
                    <div class="border border-[#ebedf2] rounded dark:bg-[#1b2e4b] dark:border-0">
                        <div class="flex items-center justify-between p-4 py-2">
                            <div class="grid place-content-center w-9 h-9 rounded-md bg-info-light dark:bg-info text-info dark:text-info-light">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                    <path d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z" stroke="currentColor" stroke-width="1.5" />
                                    <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2" transform="rotate(-45 8.60699 8.87891)" stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div class="ltr:ml-4 rtl:mr-4 flex items-start justify-between flex-auto font-semibold">
                                <h6 class="text-white-dark text-[13px] dark:text-white-dark">Total Transfer Amount <span class="block text-base text-[#515365] dark:text-white-light">₦<?php echo $referwallet['transfer'];?></span></h6>
                             </div>
                        </div>
                    </div>
              </div>
 
           
<?php 
include("include/custom_js.php");
?> 
<script type="module">
import { toast } from 'https://cdn.skypack.dev/wc-toast';
document.addEventListener("DOMContentLoaded", function() {
  const copyBtn = document.getElementById("copyBtn");
  const upiCopyElement = document.getElementById("upi_copy");

  if (copyBtn && upiCopyElement) {
    copyBtn.addEventListener("click", function() {
      const str = upiCopyElement.value.slice(0);
      const el = document.createElement('textarea');
      el.value = str;

      el.setAttribute('readonly', '');
      el.style.position = 'absolute';
      el.style.left = '-9999px';
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
    toast.success("Refer Link Copied");

      this.classList.add("ticked");

      // Remove the class after a short delay to reset the animation
      setTimeout(() => {
        this.classList.remove("ticked");
      }, 500);
    });
  }
});

</script>         
<?php include 'include/footer-main.php'; ?>
