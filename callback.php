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
    'http://localhost/MusicIntl/callback.php'
);

$session->requestAccessToken($_GET['code']);

$accessToken = $session->getAccessToken();
$refreshToken = $session->getRefreshToken();

$conn = new DB();

$stmt = $conn->db->query("INSERT INTO users (accessToken, refreshToken) VALUES ('$accessToken', '$refreshToken')");

//if ($stmt) {
//    echo "Successful INSERT of data" . PHP_EOL;
//}

$url = "app.php?accessToken=$accessToken";

header("Location: " . $url );
die();