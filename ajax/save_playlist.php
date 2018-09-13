<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/10/18
 * Time: 10:59 PM
 */

include __DIR__ . '/../inc.php';

$name = $_POST['name'];

$playlist = new Playlist();
echo $playlist->savePlaylist($name);
