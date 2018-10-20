<?php

$url = "http://musicintl.herokuapp.com/api/v1/image_upload.php";

$param = array(
    'encoded_image' => "something"
);

$postvars = http_build_query($param);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, count($param));
curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);

$result = curl_exec($ch);

curl_close($ch);
