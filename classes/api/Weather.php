<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 10/4/18
 * Time: 1:23 PM
 */

include __DIR__ . '/../../inc.php';

class Weather {

    private $coordinates;
    private $api_key;

    public function __construct($coordinates) {
        $this->coordinates = $coordinates;
        $this->api_key = getenv('DARK_SKY_API_KEY');
    }

    public function getWeather() {
        $dark_sky_url = "https://api.darksky.net/forecast/$this->api_key/$this->coordinates?exclude=flags,minutely,hourly,daily,alerts";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $dark_sky_url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}