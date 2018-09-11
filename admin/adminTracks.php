<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/3/18
 * Time: 6:29 PM
 */

include __DIR__ . '/../inc.php';

$current_page = 'tracks';

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

            <h2>Tracks</h2>
            <hr>

            <div align="right">
                <span style="font-weight: bold;">Add Track&nbsp;&nbsp;</span>
                <input type="text" name="new_track" id="new_track" placeholder="Spotify ID (20 chars)">&nbsp;&nbsp;
                <a href="#" id="new_track_button"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size: 1.6rem; vertical-align: middle; color: forestgreen;"></span></a>
                <hr>
            </div>

            <table id="tracks" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Artist(s)</th>
                    <th>Spotify ID</th>
                    <th>Preview</th>
                    <th>Duration</th>
                    <th>Popularity</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $admin = new Admin();
                $allTracks = $admin->getAllTracks();
                foreach ($allTracks as $row) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $artists = $row['artists'];
                    $spotify_id = $row['spotify_id'];
                    $spotify_url = $row['spotify_url'];
                    $preview_url = $row['preview_url'];
                    $duration = $row['duration'];
                    $popularity = $row['popularity'];
                    ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><a href="<?php echo $spotify_url; ?>" target="_blank"><?php echo $title; ?></a></td>
                        <td><?php echo $artists; ?></td>
                        <td><?php echo $spotify_id; ?></td>
                        <td><a href="<?php echo $preview_url; ?>" target="_blank">Clip</a></td>
                        <td><?php echo date("H:i:s", $duration);; ?></td>
                        <td><?php echo $popularity; ?></td>
                    </tr>
                <?php } //End foreach
                ?>
                </tbody>
            </table>

        </div>

        <br />
        <br />

        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        </body>
        </html>

        <script type="application/javascript">

            $(document).ready(function() {
                // DataTable
                var table = $('#tracks').DataTable();
            })

            // Add new track logic
            $( "#new_track_button" ).click(function( event ) {
                event.preventDefault();
                if ( $('#new_track').val() ) {
                    var input_id = $('#new_track').val();
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/save_track.php',
                        data: { input_id: input_id },
                        success:function(data) {
                            if (data == 1) {
                                alert('This track has already been saved');
                            } else if (data == 2) {
                                alert('Track saved successfully');
                                window.location.reload();
                            } else {
                                alert('Incorrect Spotify ID or database error');
                            }
                            $('#new_track').val('');
                        }
                    });
                } else {
                    alert('Please enter an ID');
                }
            });

        </script>

        <?php

    } else {

        header('WWW-Authenticate: Basic realm="Secure Site"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'This page requires authentication.';
        exit;

    }

}

