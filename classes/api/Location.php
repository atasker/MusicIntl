<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 10/5/18
 * Time: 11:04 PM
 */

include __DIR__ . '/../../inc.php';

/**
 * Location will determine if a users location is nearby any of our musical landmarks
 */

class Location {

    public $conn;

    public function __construct() {
        $this->conn = new DB();
    }

    // Will return distance (in meters)
    private static function distanceApart(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return round($angle * $earthRadius);
    }

    public function nearbyLandmark($lat, $long) {
        $stmt = $this->conn->db->query("SELECT * FROM landmarks");
        $landmarks = $stmt->fetchAll();
        $result = [];
        foreach ($landmarks as $row) {
            $distance = self::distanceApart($lat, $long, $row['lat'], $row['long']);
            // 400 meters = quarter of a mile, the current distance we check for
            if ($distance < 400) {
                $location_array = ['location' => $row['location'], 'description' => $row['description'], 'distance' => $distance];
                array_push($result, $location_array);
            }
            //$miles = $distance * 0.00062137;
            //print "User is " . round($miles) . " miles from " . $row['location'] . "<br />";
        }
        return $result;
    }

}