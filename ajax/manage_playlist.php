<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/10/18
 * Time: 10:59 PM
 */

include __DIR__ . '/../inc.php';

$playlist_id = $_POST['playlist_id'];
$track_array = $_POST['track_array'];

$playlist = new Playlist();
echo $playlist->saveTracksToPlaylist($playlist_id, $track_array);