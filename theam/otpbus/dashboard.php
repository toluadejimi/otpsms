<?php 
$page_name = "Dashboard";
include 'include/header-main.php'; 
?>

<script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/apexcharts.js"></script>
<div x-data="analytics">
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] before:mr-1 rtl:before:ml-1 ">
            <span>Main</span>
        </li>
    </ul>
       <div class="panel p-3 flex items-center text-primary overflow-x-auto whitespace-nowrap mt-3">
            <div class="ring-2 ring-primary/30 rounded-full bg-primary text-white p-1.5 ltr:mr-3 rtl:ml-3">

                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5">
                    <path d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z" stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <span class="ltr:mr-3 rtl:ml-3">Join Telegram For Latest Updates </span><a href="<?php echo $site_data['channel_url'];?>" target="_blank"><button type="button" class="btn btn-primary btn-sm">Join Now</button></a>
        </div>
        

                      
    <div class="pt-5">
            <div class="panel h-full overflow-hidden before:bg-[#1937cc] before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 grid grid-cols-1 content-between" style="background:linear-gradient(0deg,#00c6fb -227%,#005bea)!important;">
                <div class="flex items-start justify-between text-white-light mb-16 z-[7]">
                    <h5 class="font-semibold text-lg">Available Balance :</h5>

                    <div class="relative text-xl whitespace-nowrap">
                        ₦<?php echo number_format($data['balance'], 2);?>
                    </div>
                </div>
                <div class="flex items-center justify-between z-10">
                    <div class="flex items-center justify-between">
                        <a href="recharge" class="shadow-[0_0_2px_0_#bfc9d4] rounded p-1 text-white-light hover:bg-[#1937cc] place-content-center ltr:mr-2 rtl:ml-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </a>
                        <a href="transactions" class="shadow-[0_0_2px_0_#bfc9d4] rounded p-1 text-white-light hover:bg-[#1937cc] grid place-content-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z" stroke="currentColor" stroke-width="1.5"></path>
                                <path opacity="0.5" d="M10 16H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path opacity="0.5" d="M14 16H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path opacity="0.5" d="M2 10L22 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </a>
                    </div>
                    <a href="buy-number" class="shadow-[0_0_2px_0_#bfc9d4] rounded p-1 text-white-light hover:bg-[#4361ee] z-10">
                        Buy Number
                    </a>
                </div>
            </div>

        </div><br>


            <div class="panel mb-5">
                <div class="mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Summary</h5>
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
                                <h6 class="text-white-dark text-[13px] dark:text-white-dark">Recharge - Life Time <span class="block text-base text-[#515365] dark:text-white-light">₦<?php echo $data['total_recharge'];?></span></h6>
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
                                <h6 class="text-white-dark text-[13px] dark:text-white-dark">Number Purchased - Life Time <span class="block text-base text-[#515365] dark:text-white-light"><?php echo $data['total_otp'];?></span></h6>
                             </div>
                        </div>
                    </div>
                    <div class="border border-[#ebedf2] rounded dark:bg-[#1b2e4b] dark:border-0">
                        <div class="flex items-center justify-between p-4 py-2">
                            <div class="grid place-content-center w-9 h-9 rounded-md bg-warning-light dark:bg-warning text-warning dark:text-warning-light">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                    <path d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z" stroke="currentColor" stroke-width="1.5" />
                                    <path opacity="0.5" d="M10 16H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path opacity="0.5" d="M14 16H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path opacity="0.5" d="M2 10L22 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div class="ltr:ml-4 rtl:mr-4 flex items-start justify-between flex-auto font-semibold">
                                <h6 class="text-white-dark text-[13px] dark:text-white-dark">Refer Balance <span class="block text-base text-[#515365] dark:text-white-light">₦<?php echo $referwallet['balance'];?></span></h6>
                              </div>
                        </div>
                    </div>
                </div>
            </div>

<?php
if($recent_history){
?>
            <!-- Recent Transactions -->
            <div class="panel">
                <div class="mb-5 text-lg font-bold">Recent 15 Transactions</div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th class="ltr:rounded-l-md rtl:rounded-r-md">ID</th>
                                <th>DATE</th>
                                <th>TYPE</th>
                                <th>AMOUNT</th>
                                <th class="text-center ltr:rounded-r-md rtl:rounded-l-md">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php 
                                        $i = 1;
                                        foreach($recent_history as $transaction){  
                                        if($transaction['status'] == "1"){
                                           $img ='<span class="badge bg-success/20 text-success rounded-full hover:top-0">Success</span>';
                                           }elseif($transaction['status'] == "2"){
                                          $img ='<span class="badge bg-danger/20 text-warning rounded-full hover:top-0">Pending</span>';
                                          }else{
                                          $img ='<span class="badge bg-danger/20 text-danger rounded-full hover:top-0">Cancelled</span>';                                                                         
                                           }   
                                  $timestamp = strtotime($transaction['date']);
                                  $formattedDate = date("M d, Y - h:i A", $timestamp);
               
                                          ?>    
                            <tr>
                                <td class="font-semibold">#<?php echo $i;?></td>
                                <td class="whitespace-nowrap"><?php echo $formattedDate;?></td>
                                <td class="whitespace-nowrap"><?php echo $transaction['type'];?></td>
                                <td>₦<?php echo $transaction['amount'];?></td>
                                <td class="text-center">
                                  <?php echo $img;?> 
                                </td>
                            </tr>
                            <?php
                            $i++;
                            }
                            ?>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
 
<?php
}
?>
<div x-data="modal(false)" x-init="setTimeout(() => open = !open, 2000)">
    <div x-show="open" class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-full bg-black bg-black/10">
        <div  @click.outside="open = false; $store.app.updateHowTo()" class="relative p-4 w-full max-w-[580px] max-h-full animate__animated animate__zoomIn">
            <div class="bg-white dark:bg-[#1b2e4b] rounded-lg shadow-lg w-full mx-auto">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 dark:text-white-light">
                    <h3 class="text-xl font-semibold">
                        Notice to all Users!!
                    </h3>
                    <button x-on:click="open = !open; $store.app.updateHowTo()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-2">
                    <h2 class="text-xl font-semibold dark:text-white-light">The solution to the incorrect Code</h2>
                    <p class="text-gray-600 dark:text-white-light">
                        Once your purchase number and it shows incorrect code 

                        Request for another code via sms 
                        You will see another code 
                        And put it immediately 
                        
                        It will work ❤️
                    </p>
                </div>
            </div>
        </div>
    </div>
    <button x-on:click="open = !open" class="bg-primary rounded-full p-3 text-white font-semibold how-to-float shadow-sm">
        <div class="flex gap-1 justify-center items-center">
            <i x-show="open" class='bx bx-sm bx-x'></i>
            <i x-show="!open" class='bx bx-sm bx-question-mark'></i>
            <span class="text-sm">How to use</span>
        </div>
    </button>
</div>
<?php include 'include/footer-main.php'; ?>
