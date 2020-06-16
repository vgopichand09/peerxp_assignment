<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://desk.zoho.in/api/v1/tickets",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "orgId: 60001280952",
    "Authorization: 2e4740934d006ac74de79025ce3ed073",
    "Cookie: 36c0577de3=690d2a30f9f88c5e7b1a40e86903e18a; crmcsr=427886ca-2e61-4e4a-8ad9-c65a36cb044b"
  ),
));

$response = curl_exec($curl);
$error = curl_error($curl);
echo $error;
curl_close($curl);
print_r($response);
