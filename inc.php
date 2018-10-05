<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 8/29/18
 * Time: 3:43 PM
 */

$production = getenv('PRODUCTION_ENV');
if (!empty($production)) {
    echo $production;
}

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
require_once 'classes/api/API.php';