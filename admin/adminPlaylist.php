<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/3/18
 * Time: 6:29 PM
 */

include __DIR__ . '/../inc.php';

$current_page = 'playlists';
$playlist_id = $_GET['id'];
$playlist = new Playlist();
$name = $playlist->getName($playlist_id);

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
            <title>The Caravan | Admin</title>
            <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700" rel="stylesheet">
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.css"/>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
            <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

            <style type="text/css">
                body {
                    font-family: 'Muli', sans-serif;
                }
                .ui-widget-header {
                    background-color: #FFFFFF;
                    border-top: none;
                    border-left: none;
                    border-right: none;
                }
                #tabs {
                    border: none;
                }
                .ui-widget-content a {
                    color: #337ab7;
                    text-decoration: none;
                }
                .ui-state-active, .ui-widget-content .ui-state-active,
                .ui-widget-header .ui-state-active, a.ui-button:active,
                .ui-button:active, .ui-button.ui-state-active:hover {
                    background-color: #337ab7;
                }

                table.dataTable thead th {
                    border-bottom: 0;
                }
                table.dataTable.no-footer {
                    border-bottom: 0;
                }
                input[type='checkbox'] {
                    -webkit-appearance: none;
                    width: 25px;
                    height: 25px;
                    background: #FFFFFF;
                    border-radius: 2px;
                    border: 1px solid #C4C4C4;
                }
                input[type='checkbox']:checked {
                    background: forestgreen;
                }
                button {
                    background-color: forestgreen;
                    color: #FFFFFF;
                }
                button:hover {
                    color: #FFFFFF !important;
                }
            </style>
        </head>

        <body>

        <div class="container">

            <?php include_once 'navbar.php'; ?>

            <h3><?= $name; ?> Playlist</h3>
            <hr>

            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Tracks</a>
                    </li>
                    <li><a href="#tabs-2">Add New Tracks</a>
                    </li>
                    <li><a href="#tabs-3">Push Playlist</a>
                    </li>
                    <li><a href="#tabs-4">Users</a>
                    </li>
                </ul>
                <div id="tabs-1">
                    <p>
                    <table id="current_tracks" class="display" style="width:100%">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Artist(s)</th>
                            <th>Preview</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $playlist = new Playlist();
                        $playlistTracks = $playlist->getPlaylistTracks($playlist_id);
                        foreach ($playlistTracks as $row) {
                            $id = $row['id'];
                            $title = $row['title'];
                            $artists = $row['artists'];
                            $spotify_url = $row['spotify_url'];
                            $preview_url = $row['preview_url'];
                            ?>
                            <tr>
                                <td><a href="<?php echo $spotify_url; ?>" target="_blank"><?php echo $title; ?></a></td>
                                <td><?php echo $artists; ?></td>
                                <td><a href="<?php echo $preview_url; ?>" target="_blank">Clip</a></td>
                            </tr>
                        <?php } //End foreach
                        ?>
                        </tbody>
                    </table>
                    </p>
                </div>
                <div id="tabs-2">
                    <p>
                    <table id="add_new_tracks" class="display" style="width:100%">
                        <!-- TODO: Should only show tracks that have not yet been added to this playlist (to avoid adding a track more than once) -->
                        <caption>Note: Tracks already added to playlist will not be listed here</caption>
                        <thead>
                        <tr>
                            <th>Add</th>
                            <th>Title</th>
                            <th>Artist(s)</th>
                            <th>Preview</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $playlist = new Playlist();
                        $tracks = $playlist->dontListTracksAlreadyOnPlaylist($playlist_id);
                        foreach ($tracks as $row) {
                            $id = $row['id'];
                            $title = $row['title'];
                            $artists = $row['artists'];
                            $spotify_url = $row['spotify_url'];
                            $preview_url = $row['preview_url'];
                            ?>
                            <tr>
                                <td>
                                    <div align="center">
                                        <input type="checkbox" id="checkbox" value="<?php echo $id; ?>" />
                                    </div>
                                </td>
                                <td><a href="<?php echo $spotify_url; ?>" target="_blank"><?php echo $title; ?></a></td>
                                <td><?php echo $artists; ?></td>
                                <td><a href="<?php echo $preview_url; ?>" target="_blank">Clip</a></td>
                            </tr>
                        <?php } //End foreach
                        ?>
                        </tbody>
                    </table>
                    </p>
                    <br />
                    <button type="button" class="btn" id="add_tracks_button">Add Tracks</button>
                </div>
                <div id="tabs-3">
                    <p>
                    <table id="users" class="display" style="width:100%">
                        <thead>
                        <tr>
                            <th>Add</th>
                            <th>Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $user = new User();
                        $allUsers = $user->getAllUsers();
                        foreach ($allUsers as $row) {
                            $id = $row['id'];
                            $email = $row['email'];
                            ?>
                            <tr>
                                <td>
                                    <div align="center">
                                        <input type="checkbox" id="users_checkbox" value="<?php echo $id; ?>" />
                                    </div>
                                </td>
                                <td><?php echo $email; ?></td>
                            </tr>
                        <?php } //End foreach
                        ?>
                        </tbody>
                    </table>
                    </p>
                    <br />
                    <button type="button" class="btn" id="push_playlist_button">Push Playlist</button>
                </div>
                <div id="tabs-4">
                    <p>
                    <table id="user_playlist" class="display" style="width:100%">
                        <thead>
                        <tr>
                            <th>Email</th>
                            <th>Date Pushed</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $playlist = new Playlist();
                        $playlist_users = $playlist->getPlaylistUsers($playlist_id);
                        foreach ($playlist_users as $row) {
                            $id = $row['id'];
                            $email = $row['email'];
                            $date_pushed = $row['date_pushed'];
                            $time_elapsed = AdminHelper::time_elapsed_string($date_pushed);
                            ?>
                            <tr>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $time_elapsed; ?></td>
                            </tr>
                        <?php } //End foreach
                        ?>
                        </tbody>
                    </table>
                    </p>
                </div>
            </div>

        </div>

        <br />
        <br />

        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

        </body>
        </html>

        <script type="application/javascript">

            $(document).ready(function() {
                // DataTable
                $('#current_tracks').DataTable( {
                    "pageLength": 25
                } );
            })

            $(document).ready(function() {
                // DataTable
                $('#add_new_tracks').DataTable( {
                    "pageLength": 25
                } );
            })

            $(document).ready(function() {
                // DataTable
                $('#users').DataTable( {
                    "pageLength": 25
                } );
            })

            $(document).ready(function() {
                // DataTable
                $('#user_playlist').DataTable( {
                    "pageLength": 25
                } );
            })

            $("#tabs").tabs({
                activate: function (event, ui) {
                    var active = $('#tabs').tabs('option', 'active');
                }
            });

            // Add Track(s) to playlist
            $( "#add_tracks_button" ).click(function( event ) {
                event.preventDefault();
                var playlist_id = <?php echo $playlist_id; ?>;

                // Retrieve all checked Track Id's
                // TODO: make this checkbox check specific to those in this table
                var track_array = $("#add_new_tracks input:checkbox:checked").map(function(){
                    return $(this).val();
                }).get();

                if (track_array.length > 0) {
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/manage_playlist.php',
                        data: { track_array: track_array, playlist_id: playlist_id },
                        success:function(data) {
                            if (data == 1) {
                                alert('Tracks successfully added to playlist');
                                window.location.reload();
                            } else {
                                alert('Unable to perform save, contact Angus');
                            }
                        }
                    });
                } else {
                    // If no Tracks have been checked
                    alert('Please select at least one Track');
                }
            });

            // Push Playlist to selected Users
            $( "#push_playlist_button" ).click(function( event ) {
                event.preventDefault();
                var playlist_id = <?php echo $playlist_id; ?>;
                var name = "<?php echo $name; ?>";

                // Retrieve all checked User Id's
                var user_array = $("#users input:checkbox:checked").map(function(){
                    return $(this).val();
                }).get();

                if (user_array.length > 0) {
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/push_playlist.php',
                        data: { user_array: user_array, playlist_id: playlist_id, name: name },
                        success:function(data) {
                            alert(data);
                            $('input[type=checkbox]').prop('checked',false);
                        }
                    });
                } else {
                    // If no Users have been checked
                    alert('Please select at least one User');
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

