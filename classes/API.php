<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/25/18
 * Time: 2:37 PM
 */

include __DIR__ . '/../inc.php';

class API {

    public static function get_last_name($name = null) {
        if ($name != null) {
            $name = strtolower($name);
            if ($name == "angus") {
                $response = ["last_name" => "Tasker"];
                header('Content-Type: application/json');
                echo json_encode($response);
            } elseif ($name == "kristen") {
                $response = ["last_name" => "Schwartz"];
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                $response = ["last_name" => "Unknown"];
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } else {
            $response = ["last_name" => "Default - No name supplied"];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

}