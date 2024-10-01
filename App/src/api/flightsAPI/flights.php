<?php
require __DIR__."../../../config/connection.php";
require __DIR__."../../../../../index.php";

$api_key = $_ENV["ENV_API_KEY"];

$api_curl = curl_init();

curl_setopt($api_curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($api_curl);


$data = json_decode($response, true);

echo "<pre>";
print_r($data);
echo "</pre>";
curl_close($api_curl);