<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/10/18
 * Time: 1:48 PM
 */

$session = new SpotifyWebAPI\Session(
    getenv('SPOTIFY_CLIENT_ID'),
    getenv('SPOTIFY_CLIENT_SECRET'),
    getenv('SPOTIFY_REDIRECT_URI')
);