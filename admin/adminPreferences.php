<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/3/18
 * Time: 6:29 PM
 */

include __DIR__ . '/../inc.php';

$current_page = 'preferences';
$user_id = $_GET['id'];
$admin = new User();
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
                    color: mediumblue;
                    text-decoration: none;
                }
                .ui-state-active, .ui-widget-content .ui-state-active,
                .ui-widget-header .ui-state-active, a.ui-button:active,
                .ui-button:active, .ui-button.ui-state-active:hover {
                    background-color: mediumblue;
                }

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

            <h3>Music Preferences for <?= $email; ?></h3>
            <hr>

            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Saved Tracks</a>
                    </li>
                    <li><a href="#tabs-2">Recently Played Tracks</a>
                    </li>
                </ul>
                <div id="tabs-1">
                    <p>
                        <table id="saved_tracks" class="display" style="width:100%">
                            <caption>Saved Tracks</caption>
                            <thead>
                            <tr>
                                <th>Track</th>
                                <th>Artist</th>
                                <th>Added at</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $admin = new Track();
                            $tracks = $admin->getAllUserTracks($user_id);
                            foreach ($tracks->items as $track) {
                                $added_at = date('F j, Y, g:i a', strtotime($track->added_at));
                                $track = $track->track;
                                ?>
                                <tr>
                                    <td><?php echo '<a target="_blank" href="' . $track->external_urls->spotify . '">' . $track->name . '</a>'; ?></td>
                                    <td><?php echo $track->artists[0]->name; ?></td>
                                    <td><?php echo $added_at; ?></td>
                                </tr>
                            <?php } //End foreach
                            ?>
                            </tbody>
                        </table>
                    </p>
                </div>
                <div id="tabs-2">
                    <p>
                        <table id="recent_tracks" class="display" style="width:100%">
                            <caption>Recently Played Tracks</caption>
                            <thead>
                            <tr>
                                <th>Track</th>
                                <th>Artist</th>
                                <th>Played at</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $admin = new Track();
                            $tracks = $admin->getRecentTracks($user_id);
                            foreach ($tracks->items as $track) {
                                $played_at = date('F j, Y, g:i a', strtotime($track->played_at));
                                $track = $track->track;
                                ?>
                                <tr>
                                    <td><?php echo '<a href="' . $track->external_urls->spotify . '">' . $track->name . '</a>'; ?></td>
                                    <td><?php echo $track->artists[0]->name; ?></td>
                                    <td><?php echo $played_at; ?></td>
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
                $('#saved_tracks').DataTable( {
                    "pageLength": 25
                } );
            })

            $(document).ready(function() {
                // DataTable
                $('#recent_tracks').DataTable( {
                    "pageLength": 25
                } );
            })

            $("#tabs").tabs({
                activate: function (event, ui) {
                    var active = $('#tabs').tabs('option', 'active');
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

