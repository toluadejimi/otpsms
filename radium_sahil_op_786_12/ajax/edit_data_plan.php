<?php
include("../auth.php");

if(isset($_SESSION['admin']) =="") {
        
echo"<script>  setTimeout(function(){
            window.location.href = 'index.php';
         }, 100;
</script>"; 
}

$query = mysqli_query($conn, "
    UPDATE data_plans SET
        network_id      = '".$_POST['network_id']."',
        plan_type       = '".$_POST['plan_type']."',
        plan_name       = '".$_POST['plan_name']."',
        api_plan_id     = '".$_POST['api_plan_id']."',
        cost_price      = '".$_POST['cost_price']."',
        selling_price   = '".$_POST['selling_price']."',
        validity        = '".$_POST['validity']."',
        status          = '".$_POST['status']."'
    WHERE id = '".$_POST['id']."'
");

if (!$query) {
    http_response_code(500);
    echo mysqli_error($conn);
    exit;
}

echo "success";