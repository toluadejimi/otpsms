<?php
include("auth.php");

if (!isset($_SESSION['admin'])) {
  header('location: ../');
  return;
}


$api_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($api_id <= 0) {
    die("Invalid API provider ID.");
}

// Fetch API provider
$sql_api = mysqli_query($conn, "SELECT * FROM boosting_api_providers WHERE id = '$api_id' AND status = 1");
$api_data = mysqli_fetch_assoc($sql_api);
if (!$api_data) {
    die("API provider not found or inactive.");
}

$api_key = $api_data['api_key'];
$api_url = rtrim($api_data['api_url'], '/');

// Fetch existing service IDs from our DB for this API provider
$existing_service_ids = [];
$res = mysqli_query($conn, "SELECT api_service_id FROM boosting_services WHERE api_provider_id = '$api_id' AND status = 1");
while ($row = mysqli_fetch_assoc($res)) {
    $existing_service_ids[] = (int)$row['api_service_id'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Import Boosting Services</title>
  <?php include("include/head.php"); ?>
  <style>
    .accordion { margin-bottom: 1em; }
    .accordion .header { background: #eee; padding: 0.5em; cursor: pointer; }
    .accordion .content { display: none; padding: 0.5em; border: 1px solid #ccc; }
    .service-table { width: 100%; border-collapse: collapse; }
    .service-table th, .service-table td { border: 1px solid #ccc; padding: 0.5em; }
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .service-table {
        width: 100%;
        min-width: 800px; /* Minimum width to allow scrolling on smaller screens */
        border-collapse: collapse;
    }

  </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("include/slidebar.php"); ?>
        <!-- Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include("include/topbar.php"); ?>
                <!-- Topbar -->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="boosting_api_home">Boosting Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Import Boosting Services</li>
                        </ol>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h3>Import Services for API #<?= $api_data['id'] ?> - <?= $api_data['api_name'] ?> </h3>
                        <div>
                            <button id="btnImport" class="btn btn-sm btn-primary"><span class="spinner-border spinner-border-sm me-1" id="importSpinner" style="display: none;" role="status" aria-hidden="true"></span> Import Selected Services</button>
                            <button id="btnRefresh" class="btn btn-sm btn-secondary">Refresh from API</button>
                        </div>
                    </div>
                    <form id="importForm">
                        <div id="loader" style="display: none; text-align:center; margin: 20px 0;">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>Loading services from API...</p>
                        </div>
                        <div id="accordionContainer">
                            <!-- dynamic accordions will go here -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
   </div>
  <?php include("include/script.php"); ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(function(){
    const apiUrl = "<?= $api_url ?>/api/v2";
    const apiKey = "<?= $api_key ?>";
    const apiId = <?= $api_id ?>;
    const apiRate = <?= (float)$api_data['api_rate'] ?>;
    const apiIncrease = <?= (float)$api_data['api_percentage_increase'] ?>;
    const apiCurrency = "<?= $api_data['currency'] ?>";


    // Pass existing IDs to JS
    const existingIds = <?= json_encode($existing_service_ids) ?>;

    function showLoader() {
        $("#loader").show();

        $("#accordionContainer").html("");
    }

    function hideLoader() {
        $("#loader").hide();
    }


    function fetchServices() {
        showLoader();

        $.post("ajax/get_boosting_api_services.php", { api_id: apiId }, function(resp) {
            hideLoader();

            if (resp.error) {
            alert(resp.error);
            return;
            }
            renderAccordion(resp.services);
        }).fail(function(xhr, status, err){
            hideLoader();
            alert("Error fetching services: " + err);
        });
    }


    function renderAccordion(services) {
      let catMap = {};
      services.forEach(s => {
        if (!catMap[s.category]) catMap[s.category] = [];
        catMap[s.category].push(s);
      });

      let html = "";
      for (let category in catMap) {
        let servicesArr = catMap[category];

        const baseRateHeader = apiCurrency === "USD"
            ? "Rate (USD)"
            : "Rate (₦)";

        const convertedRateHeader = apiCurrency === "USD"
            ? "Rate (₦)"
            : "Final Price (₦)";


        html += `<div class="accordion">
          <div class="header">${category}</div>
          <div class="content">
            <div class="table-responsive">
                <table class="service-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="checkAll" /></th>
                        <th>Service ID</th>
                        <th>Name</th>
                        <th>${baseRateHeader}</th>
                        <th>${convertedRateHeader}</th>
                        <th>Price + Increase (₦)</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Refill</th>
                        <th>Cancel</th>
                    </tr>
                </thead>
                <tbody>`;
        servicesArr.forEach(s => {
            let sid = parseInt(s.service);
            let isExisting = existingIds.includes(sid);
            let chkAttr = isExisting ? "checked disabled" : "";

            // Calculate prices
            let baseRate = parseFloat(s.rate);
            let rateNgn, increasedNgn;

            if (apiCurrency === "USD") {
                // USD → NGN conversion
                rateNgn = (baseRate * apiRate).toFixed(2);
                let increasedUsd = baseRate + ((apiIncrease / 100) * baseRate);
                increasedNgn = (increasedUsd * apiRate).toFixed(2);
            } else {
                // NGN or other currencies → already final
                rateNgn = baseRate.toFixed(2);
                increasedNgn = (baseRate + ((apiIncrease / 100) * baseRate)).toFixed(2);
            }


            html += `<tr>
            <td><input type="checkbox" name="services[]" value="${sid}" ${chkAttr} /></td>
            <td>${sid}</td>
            <td>${s.name}</td>
            <td>
                ${apiCurrency === "USD" ? "$" + baseRate.toFixed(4) : "₦" + baseRate.toFixed(2)}
            </td>
            <td>₦${rateNgn}</td>
            <td>₦${increasedNgn}</td>
            <td>${s.min}</td>
            <td>${s.max}</td>
            <td>${s.refill ? 'Yes' : 'No'}</td>
            <td>${s.cancel ? 'Yes' : 'No'}</td>
            </tr>`;

        });
        html += `</tbody></table>
            </div>
          </div>
        </div>`;
      }

      $("#accordionContainer").html(html);

      $(".accordion .header").click(function(){
        $(this).next(".content").slideToggle();
      });
      $(".checkAll").change(function(){
        let checked = $(this).is(":checked");
        $(this).closest("table").find("tbody input[type=checkbox]:not(:disabled)").prop("checked", checked);
      });
    }

    // Initial fetch
    fetchServices();

    $("#btnRefresh").click(function(e){
      e.preventDefault();
      fetchServices();
    });

    $("#importForm").submit(function(e){
        e.preventDefault();

        let selected = $("input[name='services[]']:not(:disabled):checked").map(function(){
            return this.value;
        }).get();

        if (selected.length === 0) {
            alert("No new services selected.");
            return;
        }

        // Show spinner & disable button
        $("#btnImport").prop("disabled", true);
        $("#importSpinner").show();

        $.ajax({
            url: "ajax/import_boosting_services.php",
            method: "POST",
            data: {
            api_id: apiId,
            services: selected
            },
            success: function(res) {
                alert(res);
                setTimeout(() => {
                    location.reload();
                }, 1500)
            },
            error: function(xhr, status, err) {
                alert("Import error: " + err);
            },
            complete: function() {
                $("#btnImport").prop("disabled", false);
                $("#importSpinner").hide();
            }
        });
    });


    // Manually trigger form submission from the button outside the form
    $("#btnImport").click(function(e) {
    e.preventDefault();
    $("#importForm").submit();
    });

  });
  </script>
</body>
</html>
