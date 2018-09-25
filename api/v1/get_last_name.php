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
        if (isset($_GET["name"])) {
            $name = $_GET["name"];
            API::get_last_name($name);
        } else {
            API::get_last_name();
        }
    break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
    break;
}