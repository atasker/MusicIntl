<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 8/24/18
 * Time: 10:30 AM
 */

include 'inc.php';

$session = new SpotifyWebAPI\Session(
    '13ebd10f15714843aea76c5c7259e516',
    '93b62230ebd64bcb8640329caaf9c90d',
    'https://musicintl.herokuapp.com/callback.php'
    //'http://localhost/MusicIntl/callback.php'
);

$options = [
    'scope' => [
        'user-read-email',
        'user-read-currently-playing',
        'playlist-modify-public',
        'user-library-read',
        'user-read-recently-played',
    ],
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();