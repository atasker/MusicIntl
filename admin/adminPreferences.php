<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/3/18
 * Time: 6:29 PM
 */

include '../inc.php';

$current_page = 'preferences';
$user_id = $_GET['id'];
$admin = new Admin();
$email = $admin->getEmail($user_id);

if (!isset($_SERVER['PHP_AUTH_USER'])) {

    header('WWW-Authenticate: Basic realm="Secure Site"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'This page requires authentication.';
    exit;

} else {

    $conn = new DB();
    $stmt = $conn->db->query("SELECT username, password_hash FROM credentials WHERE username = 'admin'");
    $results = $stmt->fetch(PDO::FETCH_NUM);
    $username = $results[0];
    $hash = $results[1];

    if (password_verify($_SERVER['PHP_AUTH_PW'], $hash)) {
        $authorized_password = true;
    } else {
        $authorized_password = false;
    }

    if ($_SERVER['PHP_AUTH_USER'] === $results[0] && $authorized_password) {

        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>InterTracks Admin</title>
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.css"/>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        </head>

        <body>

        <div class="container">

            <?php include_once 'navbar.php'; ?>

            <h3>Music Preferences for <?= $email; ?></h3>
            <hr>

            <table id="tracks" class="display" style="width:100%">
                <caption>Saved Tracks</caption>
                <thead>
                <tr>
                    <th>Track</th>
                    <th>Artist</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $admin = new Admin();
                $tracks = $admin->getAllUserTracks($user_id);
                foreach ($tracks->items as $track) {
                    $track = $track->track;
                    ?>
                    <tr>
                        <td><?php echo '<a href="' . $track->external_urls->spotify . '">' . $track->name . '</a>'; ?></td>
                        <td><?php echo $track->artists[0]->name; ?></td>
                    </tr>
                <?php } //End foreach
                ?>
                </tbody>
            </table>

        </div>

        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        </body>
        </html>

        <script type="application/javascript">

            $(document).ready(function() {
                // DataTable
                var table = $('#tracks').DataTable();
            })

        </script>

        <?php

    } else {

        header('WWW-Authenticate: Basic realm="Secure Site"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'This page requires authentication.';
        exit;

    }

}

