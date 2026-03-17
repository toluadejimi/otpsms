const toast = new Notyf({
    position: {x:'right',y:'top'}
});

//import { copyToClipboard } from 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js';
          var options1 = {
             searchable: true,
             placeholder: 'Select Country'
        };
     var op1 =  NiceSelect.bind(document.getElementById("country-id"), options2);
          var options2 = {
             searchable: true,
             placeholder: 'Select Service'
        };
     var op2 =  NiceSelect.bind(document.getElementById("service-id"), options2);

                
$(document).ready(function () {
    // ...
    var token = $("#token").val();
    var params = { token: token, server: "139" };
    $("#service-id").empty();  
    var option_loading = "<option value=\"\" selected disabled>Loading.....</option>"; // Escape the double quotes
    $("#service-id").append(option_loading);          
    
    // op2.disabled();
        // AJAX call for fetching services based on selected category
      
    $.ajax({
        type: "GET",
        url: "api/service/getService",
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
                var option33 = "<option value=" + service['id'] + ">" + service['service_name'] + ' - ' + '₦' + service['service_price'] + "</option>";
                $("#service-id").append(option33);
            });
            op2.update();
            $("#services-loader").addClass("d-none");
            $('#services-container').removeClass('d-none');
        }
    });
  
  $("#buy-numbers").click(function () {
       var server = $("#country-id option:selected").val();  
         var service = $("#service-id option:selected").val();       
         var token = $("#token").val();
          if (server === "") {
    console.log("Select Server");
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
                    // Sroll to section
                    $('#card-container')[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });;
                  //  user_balance(token);    
                    } else {
                    toast.error(json.message);
                }
            }
        });
    });
});

function applyPriceMultiplier(multiplier) {
    $("#service-id option").each(function () {
        var basePrice = parseFloat($(this).data('base-price'));

        if (!isNaN(basePrice)) {
            var updatedPrice = Math.round(basePrice * multiplier);
            var label = $(this).text().split(" - ")[0]; // Service name only
            $(this).text(`${label} - ₦${updatedPrice}`);
        }
    });

    // Refresh NiceSelect dropdown
    op2.update();
}

function updateSettingsImpact() {
    var totalImpact = 0;

    // Area Code
    var areaCode = $.trim($('#areaCode').val());
    if (areaCode.length === 3 && !isNaN(areaCode)) {
        $('#areaCodeImpact').show().text('+30% - Area code specified');
        totalImpact += 30;
    } else {
        $('#areaCodeImpact').hide().text('');
    }

    // Carrier
    var carrier = $('#carrier').val();
    if (carrier !== '') {
        $('#carrierImpact').show().text('+30% - Carrier preference set');
        totalImpact += 30;
    } else {
        $('#carrierImpact').hide().text('');
    }

    // Phone Number
    var phoneNo = $.trim($('#phoneNo').val());
    if (phoneNo.length === 10 && !isNaN(phoneNo)) {
        $('#phoneNoImpact').show().text('+30% - Specific number requested');
        totalImpact += 30;
    } else {
        $('#phoneNoImpact').hide().text('');
    }

    // Summary and Price Update
    if (totalImpact > 0) {
        var multiplier = (100 + totalImpact) / 100;
        $('#priceSummary').show();
        $('#priceBreakdown').html(`
            <div>Base price: 100%</div>
            <div>Additional charges: +${totalImpact}%</div>
            <div style="font-weight: 600; color: #ff6b35;">Total multiplier: ${multiplier.toFixed(2)}x</div>
        `);
        applyPriceMultiplier(multiplier);
    } else {
        $('#priceSummary').hide();
        $('#priceBreakdown').empty();
        applyPriceMultiplier(1); // Reset to base price
    }
}

$('#areaCode, #carrier, #phoneNo').on('input change', function () {
    updateSettingsImpact();
});

function clearAllSettings() {
    $('#areaCode').val('');
    $('#carrier').val('');
    $('#phoneNo').val('');
    updateSettingsImpact();
}

window.clearAllSettings = clearAllSettings;
var settime = 0; 

