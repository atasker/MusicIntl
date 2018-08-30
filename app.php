<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 8/30/18
 * Time: 12:08 PM
 */

include 'inc.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();
$accessToken = $_GET['accessToken'];

$api->setAccessToken($accessToken);

$user = $api->me();
$email = $user->email;

$conn = new DB();

$stmt = $conn->db->query("UPDATE users SET email = '$email' WHERE accessToken = '$accessToken';");

if ($stmt) {
    echo "User has been successfully authenticated.</br>" . PHP_EOL;
    echo "Account information stored in DB.</br>" . PHP_EOL;
    echo "<b>Email:</b> $email</br>" . PHP_EOL;
    echo "<b>ID:</b> $user->id" . PHP_EOL;
}
