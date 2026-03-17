<?php 
$page_name = "Api Tool";
include 'include/header-main.php'; 
?>
<script src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/simple-datatables.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script defer src="<?php echo WEBSITE_URL; ?>/theam/otpbus/assets/js/apexcharts.js"></script>
<script>
        $(document).ready(function() {
            // Remove "active" class from all <a> elements
            $('#slide-dashboard').removeClass("active");
            
            // Add "active" class to the specific element with ID "faq"
            $("#slider-api-tool").addClass("active");
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
            <span>Api Tool</span>
        </li>
    </ul>

    <div class="panel mt-5">
     <h5 class="font-bold text-lg dark:text-white-light">Api tool</h5> 
     <p class="font-bold mt-3 dark:text-white-light">Every API key is a unique 32-bit alphanumeric string, serving as a secure identifier. An API tool facilitates your application's access to our services via the Application Programming Interface, ensuring seamless interaction and functionality.</p>
      <center>
                    <label for="name" class="form-label mt-3">Your Api Key</label>
                </center>
       <div style="position: relative; margin-top: 8px; text-align: left;"> <!-- Adjusted for left alignment -->
            <div class="flex">
                         <input type="hidden" id="tokens" value="<?php echo $_SESSION['token'];?>">                                                                                                                   
                <input type="text" id="api_value" value="<?php echo $api_data["api_key"];?>" class="form-input ltr:rounded-r-none rtl:rounded-l-none" readonly/>
              </div>
            <button id="show_api" onclick="new_api()" type="button" class="btn btn-primary mt-4">Generate New Key</button>
     </div>      
</div>                                                                                           
   <ul class="flex mt-4 space-x-2 rtl:space-x-reverse">
        <li>
            <a class="text-primary text-xl">Documentation</a>
        </li>
    </ul>
    <div class="space-y-5 mt-5" x-data="{ active: null }">
            <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md bg-white dark:bg-[#0e1726] ">
                <div class="flex font-semibold p-5 rounded-t-md  cursor-pointer" :class="{'bg-primary/20 text-primary' : active === 1}" x-on:click="active === 1 ? active = null : active = 1">
                    <span class="text-primary">Balance Request</span>
                    <div class="ltr:ml-auto  rtl:mr-auto flex">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :class="{ 'rotate-180': active === 1 }">
                            <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <div x-cloak x-show="active === 1" x-collapse>
                    <div class="p-3 text-white-dark font-semibold "><div class="pl-5 " style="background:#eaf1ff; padding-left:5px; border-top-left-radius:8px; border-top-right-radius:8px;"> Request Method -> Get</div>
                <pre class="code overflow-auto !bg-[#191e3a]  text-white pt-2 pb-2" style="border-bottom-left-radius:8px; border-bottom-right-radius:8px; color:#805dca"> <?php echo $website_url;?>/stubs/handler_api.php?api_key=$api_key&action=getBalance</pre>
                         </div>
                                <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:20px">Parameters:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">$api_key</p> - Your API key. (Required)
                                </div>  
                              <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:18px">Possible errors:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:12px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">BAD_KEY</p> - Incorrect API key
                                </div>              
                </div>
             </div>
                 <div class="space-y-5 mt-5" x-data="{ active: null }">
            <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md bg-white dark:bg-[#0e1726] ">
                <div class="flex font-semibold p-5 rounded-t-md  cursor-pointer" :class="{'bg-primary/20 text-primary' : active === 1}" x-on:click="active === 1 ? active = null : active = 1">
                    <span class="text-primary">Get Number</span>
                    <div class="ltr:ml-auto  rtl:mr-auto flex">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :class="{ 'rotate-180': active === 1 }">
                            <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <div x-cloak x-show="active === 1" x-collapse>
                    <div class="p-3 text-white-dark font-semibold "><div class="pl-5 " style="background:#eaf1ff; padding-left:5px; border-top-left-radius:8px; border-top-right-radius:8px;"> Request Method -> Get</div>
                <pre class="code overflow-auto !bg-[#191e3a]  text-white pt-2 pb-2" style="border-bottom-left-radius:8px; border-bottom-right-radius:8px; color:#805dca"> <?php echo $website_url;?>/stubs/handler_api.php?action=getNumber&api_key=$api_key&service=$service&country=$country</pre>
                         </div>
                                <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:20px">Parameters:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$api_key</p> - Your API key. (Required)<br>
                                 <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$service</p> - It is a Unique Service Id for every service. (Required)<br>
                                 <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$country</p> - It is a Server id . (Required)
                                </div> 
                          <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:18px">Possible errors:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:12px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">BAD_KEY</p> - Incorrect API key<br>
                                 <p style="background:#eaf1ff; display: inline-block; font-size:12px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">NO_NUMBERS</p> - No numbers, try again later<br>
                                  <p style="background:#eaf1ff; display: inline-block; font-size:12px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">NO_BALANCE</p> - Low balance<br>
                                </div>                   
                </div>
             </div>
                       <div class="space-y-5 mt-5" x-data="{ active: null }">
            <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md bg-white dark:bg-[#0e1726] ">
                <div class="flex font-semibold p-5 rounded-t-md  cursor-pointer" :class="{'bg-primary/20 text-primary' : active === 1}" x-on:click="active === 1 ? active = null : active = 1">
                    <span class="text-primary">Get Activation Status</span>
                    <div class="ltr:ml-auto  rtl:mr-auto flex">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :class="{ 'rotate-180': active === 1 }">
                            <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <div x-cloak x-show="active === 1" x-collapse>
                    <div class="p-3 text-white-dark font-semibold "><div class="pl-5 " style="background:#eaf1ff; padding-left:5px; border-top-left-radius:8px; border-top-right-radius:8px;"> Request Method -> Get</div>
                <pre class="code overflow-auto !bg-[#191e3a]  text-white pt-2 pb-2" style="border-bottom-left-radius:8px; border-bottom-right-radius:8px; color:#805dca"> <?php echo $website_url;?>/stubs/handler_api.php?action=getStatus&api_key=$api_key&id=$id</pre>
                         </div>
                                <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:20px">Parameters:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$api_key</p> - Your API key. (Required)<br>
                                 <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$id</p> - Activation id. (Required)<br>
                                 </div> 
                          <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:18px">Possible errors:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:12px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">BAD_KEY</p> - Incorrect API key<br>
                                 <p style="background:#eaf1ff; display: inline-block; font-size:12px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">NO_ACTIVATION</p> - There is no activation<br>
                                  </div>                   
                </div>
             </div>
                              <div class="space-y-5 mt-5" x-data="{ active: null }">
            <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md bg-white dark:bg-[#0e1726] ">
                <div class="flex font-semibold p-5 rounded-t-md  cursor-pointer" :class="{'bg-primary/20 text-primary' : active === 1}" x-on:click="active === 1 ? active = null : active = 1">
                    <span class="text-primary">Change the activation status</span>
                    <div class="ltr:ml-auto  rtl:mr-auto flex">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :class="{ 'rotate-180': active === 1 }">
                            <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <div x-cloak x-show="active === 1" x-collapse>
                    <div class="p-3 text-white-dark font-semibold "><div class="pl-5 " style="background:#eaf1ff; padding-left:5px; border-top-left-radius:8px; border-top-right-radius:8px;"> Request Method -> Get</div>
                <pre class="code overflow-auto !bg-[#191e3a]  text-white pt-2 pb-2" style="border-bottom-left-radius:8px; border-bottom-right-radius:8px; color:#805dca"> <?php echo $website_url;?>/stubs/handler_api.php?action=setStatus&api_key=$api_key&id=$id&status=$status</pre>
                         </div>
                                <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:20px">Parameters:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$api_key</p> - Your API key. (Required)<br>
                                 <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$id</p> - Activation id. (Required)<br>
                                 <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$status</p> - activation status (8 - cancel activation, 3 - Request another SMS). (Required)<br>                  
                                 </div> 
                          <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:18px">Possible errors:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:12px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">BAD_KEY</p> - Incorrect API key<br>
                                 <p style="background:#eaf1ff; display: inline-block; font-size:12px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">NO_ACTIVATION</p> - There is no activation<br>
                                  </div>                   
                </div>
             </div>
                         <div class="space-y-5 mt-5" x-data="{ active: null }">                         
                    <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md bg-white dark:bg-[#0e1726] ">
                <div class="flex font-semibold p-5 rounded-t-md  cursor-pointer" :class="{'bg-primary/20 text-primary' : active === 1}" x-on:click="active === 1 ? active = null : active = 1">
                    <span class="text-primary">Get list of all countries/Servers</span>
                    <div class="ltr:ml-auto  rtl:mr-auto flex">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :class="{ 'rotate-180': active === 1 }">
                            <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <div x-cloak x-show="active === 1" x-collapse>
                    <div class="p-3 text-white-dark font-semibold "><div class="pl-5 " style="background:#eaf1ff; padding-left:5px; border-top-left-radius:8px; border-top-right-radius:8px;"> Request Method -> Get</div>
                <pre class="code overflow-auto !bg-[#191e3a]  text-white pt-2 pb-2" style="border-bottom-left-radius:8px; border-bottom-right-radius:8px; color:#805dca"> <?php echo $website_url;?>/stubs/handler_api.php?action=getCountries&api_key=$api_key</pre>
                         </div>
                                <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:20px">Parameters:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$api_key</p> - Your API key. (Required)<br>
                                    </div> 
                          <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:18px">Possible errors:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:12px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">BAD_KEY</p> - Incorrect API key<br>
                                  </div>                   
                </div>
             </div>
                          <div class="space-y-5 mt-5" x-data="{ active: null }">                         
                    <div class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md bg-white dark:bg-[#0e1726] ">
                <div class="flex font-semibold p-5 rounded-t-md  cursor-pointer" :class="{'bg-primary/20 text-primary' : active === 1}" x-on:click="active === 1 ? active = null : active = 1">
                    <span class="text-primary">Get list of all services</span>
                    <div class="ltr:ml-auto  rtl:mr-auto flex">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :class="{ 'rotate-180': active === 1 }">
                            <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <div x-cloak x-show="active === 1" x-collapse>
                    <div class="p-3 text-white-dark font-semibold "><div class="pl-5 " style="background:#eaf1ff; padding-left:5px; border-top-left-radius:8px; border-top-right-radius:8px;"> Request Method -> Get</div>
                <pre class="code overflow-auto !bg-[#191e3a]  text-white pt-2 pb-2" style="border-bottom-left-radius:8px; border-bottom-right-radius:8px; color:#805dca"> <?php echo $website_url;?>/stubs/handler_api.php?action=getServices&api_key=$api_key&country=$country</pre>
                         </div>
                                <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:20px">Parameters:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$api_key</p> - Your API key. (Required)<br>
                                 <p style="background:#eaf1ff; display: inline-block; font-size:15px; padding:5px; border-radius:10px; margin-top:5px">$country</p> - Server id . (Required)<br>                           
                                    </div> 
                          <div class="p-2 text-white-dark font-semibold "><div class="pl-5 " style="padding-left:5px; font-size:18px">Possible errors:</div>
                                <p style="background:#eaf1ff; display: inline-block; font-size:12px; padding:5px; border-radius:10px; margin-top:5px; font-weight:bold">BAD_KEY</p> - Incorrect API key<br>
                                  </div>                   
                </div>
             </div>
<?php include 'include/footer-main.php'; ?>
   <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
   
<script>
var notyf = new Notyf();

function new_api(){
       var token = $("#tokens").val();
       

        $('#show_api').prop("disabled", true);
        $('#show_api').html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span>Generating...');

        var params = {
            token: token,
         };
        $.ajax({
            type: "POST",
            url: "api/auth/generateApi",
            data: params,
            error: function (e) {
                console.log(e);
                notyf.error('An error occurred during Connection.');
                $('#show_api').html("Generate New Key");
                $('#usdt_btn').prop("disabled", false);
            },
            success: function (data) {
                $('#show_api').html("Generate New Key");
                $('#show_api').prop("disabled", false);
                var json = JSON.parse(data);
                if (json.status === "200") {
               document.getElementById("api_value").value = json.api_key;
                 notyf.success(json.msg);
                } else {
                    notyf.error(json.msg);
                }
            }
        });
}   
</script>
