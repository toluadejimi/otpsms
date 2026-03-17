import { toast } from 'https://esm.sh/wc-toast';
//import { copyToClipboard } from 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js';
        var options1 = {
            searchable: true,
            placeholder: 'Select Service'
        };
     var op =  NiceSelect.bind(document.getElementById("service-id"), options1);
          var options2 = {
             placeholder: 'Select Server'
        };
     var op1 =  NiceSelect.bind(document.getElementById("server-id"), options2);

                
$(document).ready(function () {

    $('#server-id').change(function () {
        // ...
      if ($(this).val() == '') {
    $("#service-id").empty();
    var option = "<option value=\"\">SELECT SERVICE</option>"; // Escape the double quotes
    $("#service-id").append(option);
     op.update();    
    return;
}


        var token = $("#token").val();
        var params = { token: token, server: $(this).val() };
        $("#service-id").empty();  
    var option_loading = "<option value=\"\" selected disabled>Loading.....</option>"; // Escape the double quotes
    $("#service-id").append(option_loading);          
    

        // AJAX call for fetching services based on selected category
      
         $.ajax({
    type: "GET",
    url: "api/service/getService",
    data: params,
    dataType: "json", // Set the expected data type
    error: function (e) {
        console.log("AJAX error:", e);
    },
    success: function (data) {
        $("#service-id").empty();
  var option32 = "<option value=\"\" selected disabled>Select Service</option>"; // Escape the double quotes
    $("#service-id").append(option32);          
        data['service'].forEach(service => {
            var option33 = "<option value=" + service['id'] + ">" + service['service_name'] + ' - ' + '₦' + service['service_price'] + "</option>";
            $("#service-id").append(option33);
                    
        });
 op.update();    
     
    }
});

    });
 
    
  $("#buy-numbers").click(function () {
       var server = $("#server-id option:selected").val();  
         var service = $("#service-id option:selected").val();       
         var token = $("#token").val();
          if (server == "") {
    console.log("Select Server");
    toast.error('Please Select Server');        
    return;
  }
 
        if (service == '') {
            toast.error('Select Service');
            return; // Stop execution if email or password is blank
        }

        $('#buy-numbers').prop("disabled", true);
        $('#buy-numbers').html('<span class="animate-spin border-2 border-white border-l-transparent rounded-full w-4 h-4 ltr:mr-1 rtl:ml-1 inline-block align-middle"></span> Finding Number...');

        var params = {
            server: server,
            service: service,
            token: token,
        };
        $.ajax({
            type: "GET",
            url: "api/service/buynumber",
            data: params,
            error: function (e) {
                console.log(e);
                toast.error('An error occurred .');
                 $('#buy-numbers').html("<span class='fa fa-cart-plus' style='margin-right: 8px;'></span>Buy Number");
                   $('#buy-numbers').prop("disabled", false);
            },
            success: function (data) {
                $('#buy-numbers').html("<span class='fa fa-cart-plus' style='margin-right: 8px;'></span>Buy Number");
                $('#buy-numbers').prop("disabled", false);
                var json = JSON.parse(data);
                if (json.status === "200") {
                    toast.success(json.message);
                    checkOrder();
                  //  user_balance(token);    
                    } else {
                    toast.error(json.message);
                }
            }
        });
    });
           
             
    
    
    
    
});
var settime = 0; 

