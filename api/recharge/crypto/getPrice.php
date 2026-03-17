<?php

$apiUrl = 'https://api.coingecko.com/api/v3/simple/price?ids=tron,tether&vs_currencies=inr';

$ch = curl_init($apiUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Someting Went Wrong';
} else {
    $data = json_decode($response, true);
    $trxToInrRate = $data['tron']['inr'];
    $usdtToInrRate = $data['tether']['inr'];
    echo'{"trx":'.$trxToInrRate.',"usdt":'.$usdtToInrRate.'}';
}

curl_close($ch);

?>
