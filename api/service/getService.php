<?php
require_once __DIR__ . '/../../include/config.php';

if (!isset($_GET['server']) || $_GET['server'] == "") {
echo"Invalid Server";
} elseif (!isset($_GET['token']) || $_GET['token'] == "") {
echo"Invalid Token";
} else {
$token = mysqli_real_escape_string($conn,$_GET['token']); 
$find_token = new radiumsahil();
$check_token = $find_token->check_token($token);
$find_token->closeConnection();
if($check_token === false){
echo'Token Expired Please Logout And Login Again';
}else{
$server = $_GET['server'];
$sql = "SELECT * FROM service WHERE server_id = '" . $server . "'";
$result = mysqli_query($conn, $sql);
$final = array();

if ($result) { // Check if the query was successful
    while ($row = $result->fetch_array()) {
        array_push($final, array(
            'id' => $row['service_id'],
            'service_name' => $row['service_name'], // Corrected column name
            'service_price' => $row['service_price'],
            'server_id' => $row['server_id'],
        ));
    }
}

$data = array('service' => $final);

// Convert the PHP array to a JSON string
echo json_encode($data);

}        
}        