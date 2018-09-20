<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/3/18
 * Time: 6:29 PM
 */

include __DIR__ . '/../inc.php';

$current_page = 'playlists';

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
            <title>The Caravan Admin</title>
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.css"/>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

            <style type="text/css">
                table.dataTable thead th {
                    border-bottom: 0;
                }
                table.dataTable.no-footer {
                    border-bottom: 0;
                }
            </style>
        </head>

        <body>

        <div class="container">

            <?php include_once 'navbar.php'; ?>

            <h2>Playlists</h2>
            <hr>

            <div align="right">
                <span style="font-weight: bold;">Create Playlist&nbsp;&nbsp;</span>
                <input type="text" name="new_playlist" id="new_playlist" placeholder="Name">&nbsp;&nbsp;
                <a href="#" id="new_playlist_button"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size: 1.6rem; vertical-align: middle; color: forestgreen;"></span></a>
                <hr>
            </div>

            <table id="playlists" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>No. of tracks</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $admin = new Playlist();
                $allPlaylists = $admin->getAllPlaylists();
                foreach ($allPlaylists as $row) {
                    $id = $row['id'];
                    $name = $row['name'];

                    // Figure out number of tracks in playlist
                    $playlist = new Playlist();
                    $tracks = $playlist->getPlaylistTracks($id);
                    $count = count($tracks);
                    ?>
                    <tr>
                        <td><a href="adminPlaylist.php?id=<?php echo $id; ?>"><?php echo $name; ?></a></td>
                        <td><?php echo $count; ?></td>
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
                $('#playlists').DataTable( {
                    "pageLength": 25
                } );
            })

            // Add new playlist logic
            $( "#new_playlist_button" ).click(function( event ) {
                event.preventDefault();
                if ( $('#new_playlist').val() ) {
                    var name = $('#new_playlist').val();
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/save_playlist.php',
                        data: { name: name },
                        success:function(data) {
                            if (data == 1) {
                                alert('There is already a playlist with this name');
                            } else if (data == 2) {
                                alert('Playlist saved successfully');
                                window.location.reload();
                            } else {
                                alert('Database Error');
                            }
                            $('#new_playlist').val('');
                        }
                    });
                } else {
                    alert('Please enter a name');
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

