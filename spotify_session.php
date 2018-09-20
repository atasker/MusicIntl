<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/10/18
 * Time: 1:48 PM
 */

$session = new SpotifyWebAPI\Session(
    '13ebd10f15714843aea76c5c7259e516',
    '93b62230ebd64bcb8640329caaf9c90d',
    //'https://musicintl.herokuapp.com/callback.php'
    'http://localhost/MusicIntl/callback.php'
);