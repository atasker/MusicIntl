<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/25/18
 * Time: 2:45 PM
 */

include __DIR__ . '/../../inc.php';

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (isset($_GET["coordinates"])) {
            $coordinates = $_GET["coordinates"];
            $weather_obj = new Weather($coordinates);
            $weather_response = $weather_obj->getWeather();
            echo $weather_response;
        } else {
            $no_weather = ['Error' => 'Unable to get weather'];
            echo json_encode($no_weather);
        }
    break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
    break;
}