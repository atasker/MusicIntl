<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/5/18
 * Time: 11:53 AM
 */

?>

<!-- Static navbar -->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">The Caravan | Admin</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="../index.php">Home</a></li>
                <li class="<?= $current_page == 'users' ? 'active' : ''; ?>"><a href="adminUsers.php">Users</a></li>
                <li class="<?= $current_page == 'tracks' ? 'active' : ''; ?>"><a href="adminTracks.php">Tracks</a></li>
                <li class="<?= $current_page == 'playlists' ? 'active' : ''; ?>"><a href="adminPlaylists.php">Playlists</a></li>
                <li class="<?= $current_page == 'face' ? 'active' : ''; ?>"><a href="faceDetect.php">Face (BETA)</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
