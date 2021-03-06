<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/3/18
 * Time: 6:29 PM
 */

include __DIR__ . '/../inc.php';

$current_page = 'users';

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

            <style type="text/css">
                body {
                    font-family: 'Muli', sans-serif;
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

            <h2>Users</h2>
            <hr>

            <table id="users" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>Email (click for music preferences)</th>
                    <th>No. of Playlists pushed</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $admin = new User();
                $allUsers = $admin->getAllUsers();
                foreach ($allUsers as $row) {
                    $id = $row['id'];
                    $email = $row['email'];
                    $playlist_count = $admin->userPlaylistCount($id);
                ?>
                    <tr>
                        <td><a href="adminPreferences.php?id=<?= $id; ?>"><?php echo $email; ?></a></td>
                        <td><?php echo $playlist_count; ?></td>
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
                $('#users').DataTable( {
                    "pageLength": 25
                } );
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

