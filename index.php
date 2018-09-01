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

$auth_url = $session->getAuthorizeUrl($options);

?>

<!DOCTYPE html>
<html>
<head>
    <title>InterTracks</title>
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700" rel="stylesheet">
    <style type="text/css">
        body {
            font-family: 'Muli', sans-serif;
        }
        .hero-text {
            font-size: 2.4rem;
            font-weight: bold;
            letter-spacing: .1rem;
            padding-bottom: 15px;
        }
        .beta-link {
            text-decoration: none;
            font: inherit;
            color: inherit;
            size: inherit;
        }
        .beta-button {
            margin: 0 auto;
            font-size: 1.5rem;
            width: 50%;
            font-weight: 300;
            border: 1px solid black;
            padding: 10px;
        }
        @media (max-width: 1000px) {
            .center-inner {
                left:25%;
                top:25%;
                position:absolute;
                width:50%;
                height:300px;
                text-align:center;
                max-width:500px;
                max-height:500px;
            }
        }
        @media (min-width: 1000px) {
            .center {
                left:50%;
                top:25%;
                position:absolute;
            }
            .center-inner {
                width:500px;
                height:100%;
                margin-left:-250px;
                height:300px;
                text-align:center;
                max-width:500px;
                max-height:500px;
            }
        }
    </style>
</head>
<body>

<div class="center">
    <div class="center-inner">
        <div class="hero-text">
            InterTracks
        </div>
        <a id="beta-button" class="beta-link" href="#">
            <div class="beta-button">
                Enroll in Beta
            </div>
        </a>
    </div>
</div>

</body>
</html>

<script type="application/javascript">

    function oauth() {
        window.open('<?php echo $auth_url; ?>', 'SpotifyOAuth', 'width=320,height=550');
    }
    window.onload = function () {
        document.getElementById('beta-button').onclick = function link() {
            oauth();
        }
    }

</script>