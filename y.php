<?php

include "inc.php";

// Test script

// Image uploading API

//$url = "http://musicintl.herokuapp.com/api/v1/image_upload.php";
//$local = "localhost/musicintl/api/v1/image_upload.php";
//
//$param = array(
//    'encoded_image' => "some_random_string==//dfdf//+l+jjkki"
//);
//
//$postvars = http_build_query($param);
//
//$ch = curl_init();
//
//curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_POST, count($param));
//curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
//
//$result = curl_exec($ch);
//
//curl_close($ch);

// Send email (to be implemented with use sessions and other admin alerts)

$from = new SendGrid\Email(null, "robot@caravan.com");
$subject = "Test email from Caravan using SendGrid";
$to = new SendGrid\Email(null, "atasker2@gmail.com");
$content = new SendGrid\Content("text/plain", "Message body.");
$mail = new SendGrid\Mail($from, $subject, $to, $content);

$sendgrid_api_key = getenv('SENDGRID_API_KEY');
$sg = new \SendGrid($sendgrid_api_key);

$response = $sg->client->mail()->send()->post($mail);

//echo $response->statusCode();
//echo $response->headers();
//echo $response->body();
