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
$refreshToken = $_GET['refreshToken'];

$api->setAccessToken($accessToken);

$user = $api->me();
$email = $user->email;

$conn = new DB();

$stmt = $conn->db->query("SELECT * FROM users WHERE email = '$email'");

$results = $stmt->fetchAll();

if (count($results) >= 1) {
    echo 'This user has already been authenticated';
} else {
    $conn2 = new DB();
    $stmt2 = $conn2->db->query("INSERT INTO users (email, accessToken, refreshToken) VALUES ('$email', '$accessToken', '$refreshToken')");
    if ($stmt2) {
        echo "User has been successfully authenticated.</br>" . PHP_EOL;
        echo "Account information stored in DB.</br>" . PHP_EOL;
        echo "<b>Email:</b> $email</br>" . PHP_EOL;
        echo "<b>ID:</b> $user->id" . PHP_EOL;
    }
}
