<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/10/18
 * Time: 10:59 PM
 */

include __DIR__ . '/../inc.php';

$playlist_id = $_POST['playlist_id'];
$user_array = $_POST['user_array'];
$playlist_name = $_POST['name'];

$playlist = new Playlist();
echo $playlist->pushPlaylist($playlist_id, $user_array, $playlist_name);