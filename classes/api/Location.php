<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 10/5/18
 * Time: 11:04 PM
 */

/**
 * Location will determine if a users location is nearby any of our musical landmarks
 */

class Location {

    private $coordinates;

    public function __construct($coordinates) {
        $this->coordinates = $coordinates;
    }

    // Will return distance (in meters)
    public static function vincentyGreatCircleDistance(
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

}