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
    $message = "It looks like you've already enrolled!";
} else {
    $conn2 = new DB();
    $stmt2 = $conn2->db->query("INSERT INTO users (email, accessToken, refreshToken) VALUES ('$email', '$accessToken', '$refreshToken')");
    if ($stmt2) {
        $message = "Thanks for enrolling!";
    }
}

?>

<script type="application/javascript">

    window.close();

    window.opener.location.replace("welcome.php?message=<?php echo $message; ?>");

</script>
