const toast = new Notyf({
    position: {x:'right',y:'top'}
});

//import { copyToClipboard } from 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js';
        var options1 = {
            searchable: true,
            placeholder: 'Select Country'
        };
     var op1 =  NiceSelect.bind(document.getElementById("country-id"), options1);
          var options2 = {
             searchable: true,
             placeholder: 'Select Service'
        };
     var op2 =  NiceSelect.bind(document.getElementById("service-id"), options2);

                
$(document).ready(function () {
    
    $('#country-id').change(function () {
        // ...
      if ($(this).val() === '') {
            $("#service-id").empty();
            var option = "<option value=\"\">Select Service</option>"; // Escape the double quotes
            $("#service-id").append(option);
            op2.update();    
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
            url: "api/service/getServiceInt.php",
            data: params,
            dataType: "json", // Set the expected data type
            beforeSend: function() { // function to execute before sending the request
                // Show loading indicator
                $("#services-loader").removeClass("d-none");
                $('#services-container').addClass('d-none');
            },
            error: function (e) {
                console.log("AJAX error:", e);
            },
            success: function (data) {
                $("#service-id").empty();
                var option32 = "<option value=\"\" selected disabled>Select Service</option>"; // Escape the double quotes
                $("#service-id").append(option32);          
                data['service'].forEach(service => {
                    var option33 = "<option value=" + service['id'] + (service['pool_id'] === '' ? "" : "," + service['pool_id']) + ">" + service['service_name'] + ' ' + (service['pool_id'] === '' ? "" : " (Pool - " + service['pool_id'] + ")")  + "</option>";
                    $("#service-id").append(option33);
                });
                op2.update();
                $("#services-loader").addClass("d-none");
                $('#services-container').removeClass('d-none');
            }
        });

    });
 
      $("#service-id").change(function () {
        if ($("#server_id").val() == "") return;
    
        var server = $("#country-id option:selected").val();
        var service = $("#service-id option:selected").val();
    
        var token = $("#token").val();
        var params = { token: token, server: server, service: service};
        
        console.log(params);
    
        $.ajax({
          type: "GET",
          url: "api/service/getServiceIntPrice",
          data: params,
          dataType: "json", // Set the expected data type
          beforeSend: function () {
            // function to execute before sending the request
            // Show loading indicator
            $("#buy-numbers").attr("disabled", true);
            $("#service-price-loader").html("");
            $("#service-price-loader").removeClass("d-none");
          },
          error: function (e) {
            console.log("AJAX error:", e);
          },
          success: function (data) {
            if (data.status === "200") {
              $("#pricing-information-view").html(data.message);
              $("#buy-numbers").attr("disabled", false);
              $("#service-price-loader").addClass("hidden");
            } else {
                $("#pricing-information-view").html("");
              $("#service-price-loader").addClass("hidden");
              toast.error(data.message);
            }
          },
        });
      });
  
  $("#buy-numbers").click(function () {
       var server = $("#country-id option:selected").val();  
         var service = $("#service-id option:selected").val();       
         var token = $("#token").val();
          if (server === "") {
            toast.error('Please Select Server');        
            return;
          }
 
        if (service === '') {
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
                $('#buy-numbers').html("<i class='ri-shopping-cart-2-line me-2'></i> Purchase Number");
                $('#buy-numbers').prop("disabled", false);
            },
            success: function (data) {
                $('#buy-numbers').html("<i class='ri-shopping-cart-2-line me-2'></i> Purchase Number");
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

