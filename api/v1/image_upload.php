<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/25/18
 * Time: 2:45 PM
 */

include __DIR__ . '/../../inc.php';

$request_method = $_SERVER["REQUEST_METHOD"];

$return_id = [];

switch ($request_method) {
    case 'POST':
        if (isset($_POST["encoded_image"])) {

            $received = $_POST["encoded_image"];
            $length = strlen($received);
            $return = ["Character count:" => $length];
            echo json_encode($return);

//            $unique_id = time();
//            $encoded_image = $_POST["encoded_image"];
//            $upload_directory = __DIR__ . "/../../upload/images/";
//            $upload_path = $upload_directory . $unique_id . ".png";
//
//            // Decode base64 image
//            $encoded_image = str_replace('data:image/png;base64,', '', $encoded_image);
//            $encoded_image = str_replace(' ', '+', $encoded_image);
//            $decoded_image = base64_decode($encoded_image);
//
//            // Save reference to images table
//            $conn = new DB();
//            $stmt = $conn->db->prepare("INSERT INTO image_uploads (name) VALUES (:name)");
//            $stmt->bindValue(':name', $unique_id, PDO::PARAM_STR);
//            $stmt->execute();
//
//            // Save image file to uploads folder
//            file_put_contents($upload_path, $decoded_image);
//
//            // Send unique ID back to Xcode for use in main API
//            $return_id['image_id'] = $unique_id;

        } else {
            $return = ["POSTSET" => "No"];
            echo json_encode($return);
        }
        //echo json_encode($return_id);
        break;
    default:
    // Invalid Request Method
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}