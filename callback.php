<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 8/29/18
 * Time: 4:04 PM
 */

include 'inc.php';

$session = new SpotifyWebAPI\Session(
    '13ebd10f15714843aea76c5c7259e516',
    '93b62230ebd64bcb8640329caaf9c90d',
    'https://musicintl.herokuapp.com/callback.php'
    //'http://localhost/MusicIntl/callback.php'
);

$session->requestAccessToken($_GET['code']);

$accessToken = $session->getAccessToken();
$refreshToken = $session->getRefreshToken();

$url = "app.php?accessToken=$accessToken&refreshToken=$refreshToken";

header("Location: " . $url );