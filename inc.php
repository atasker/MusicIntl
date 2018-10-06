<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 8/29/18
 * Time: 3:43 PM
 */

// Display PHP Errors

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include Classes

require 'vendor/autoload.php';
require_once 'classes/DB.php';
require_once 'classes/Playlist.php';
require_once 'classes/Track.php';
require_once 'classes/User.php';
require_once 'classes/AdminHelper.php';
require_once 'classes/TrackFeatures.php';
require_once 'classes/api/Face.php';
require_once 'classes/api/Weather.php';
require_once 'classes/api/Location.php';

// Load Environment Variables
// In production we're using preset heroku config variables

if (getenv('APP_ENV_PRODUCTION') == false) {
    // We're local, so use dotenv
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}