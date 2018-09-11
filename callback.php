<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 8/29/18
 * Time: 4:04 PM
 */

include 'inc.php';

include_once 'spotify_session.php';

$session->requestAccessToken($_GET['code']);

$accessToken = $session->getAccessToken();
$refreshToken = $session->getRefreshToken();

$url = "app.php?accessToken=$accessToken&refreshToken=$refreshToken";

header("Location: " . $url );