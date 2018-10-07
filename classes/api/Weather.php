<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 10/4/18
 * Time: 1:23 PM
 */

include __DIR__ . '/../../inc.php';

class Weather {

    private $lat;
    private $long;
    private $api_key;

    public function __construct($lat, $long) {
        $this->lat = $lat;
        $this->long = $long;
        $this->api_key = getenv('DARK_SKY_API_KEY');
    }

    public function getWeather() {
        $dark_sky_url = "https://api.darksky.net/forecast/$this->api_key/$this->lat,$this->long?exclude=flags,minutely,hourly,daily,alerts";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $dark_sky_url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}