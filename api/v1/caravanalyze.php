<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/25/18
 * Time: 2:45 PM
 */

/**
 * This file will serve as the Caravan API
 * We receive all data from the iOS app; and calculate music recommendations based on those parameters
 * Coordinates - Used to get weather and location
 * Voice
 * Spotify listening behavior
 * Image - Used to get emotions
 * Date & Time
 * News
 * User ID - Used to get shares and likes/dislikes
 */

include __DIR__ . '/../../inc.php';

$request_method = $_SERVER["REQUEST_METHOD"];

$final_response = [];

switch ($request_method) {
    case 'GET':
        if (isset($_GET["lat"]) && isset($_GET["long"]) && isset($_GET["image_id"])) {

            $lat = $_GET["lat"];
            $long = $_GET["long"];
            $image_id = $_GET["image_id"];

            // Analyze image
            $image_url = "musicintl.herokuapp.com/upload/images/$image_id.jpg";
            $image = ['url' => $image_url];
            $face = new Face($image);
            $get_face = $face->analyzeAll()->getFaces();
            $analyze = json_decode($get_face, true);

            // Return current emotional state, age & gender
            if (!empty($analyze)) {
                $age = $analyze[0]['faceAttributes']['age'];
                $gender = $analyze[0]['faceAttributes']['gender'];
                $emotion = $analyze[0]['faceAttributes']['emotion']; // Array

                $face_data = ['age' => $age, 'gender' => $gender, 'emotions' => $emotion];
                $final_response['face_data'] = $face_data;
            }

            // Get temperature of users location
            $weather_obj = new Weather($lat, $long);
            $weather_response = $weather_obj->getWeather();
            if ($weather_response != false) {
                $weather_array = json_decode($weather_response, true);
                // If array has the key 'code', it means it returned an error
                if (!array_key_exists('code', $weather_array)) {
                    $temperature = round($weather_array['currently']['temperature']);
                    $final_response['temperature'] = $temperature;
                }
            }

            // Check for proximity to a musical landmark
            $location = new Location();
            $landmarks = $location->nearbyLandmark($lat, $long);
            if (!empty($landmarks)) {
                $distances = array_column($landmarks, 'distance');
                $closest = $landmarks[array_search(min($distances), $distances)];
                $landmark = ['location' => $closest['location'], 'description' => $closest['description']];
                $final_response['landmark'] = $landmark;
            }

        }
        echo json_encode($final_response);
    break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
    break;
}