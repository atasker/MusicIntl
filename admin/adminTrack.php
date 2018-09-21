<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/3/18
 * Time: 6:29 PM
 */

include __DIR__ . '/../inc.php';

$current_page = 'tracks';
$track_id = $_GET['id'];
$track = new Track();
$track_info = $track->getTrack($track_id);
$track_features = new TrackFeatures();
$track_features_info = $track_features->getTrackFeatures($track_id);

$duration = $track_info['duration'];

// Convert milliseconds to minutes:seconds
$uSec = $duration % 1000;
$duration = floor($duration / 1000);

$seconds = $duration % 60;
$duration = floor($duration / 60);
$seconds_padded = sprintf("%02d", $seconds);

$minutes = $duration % 60;
$duration = floor($duration / 60);

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
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
            <style type="text/css">
                body {
                    font-family: 'Muli', sans-serif;
                }
            </style>
        </head>

        <body>

        <div class="container">

            <?php include_once 'navbar.php'; ?>

            <h2><?php echo $track_info['title']; ?></h2>
            <hr>

            <div class="row">

                <div class="col-md-6">
                    <p>
                        <h4><span style="font-weight: 700;">Internal ID:</span> <span style="font-weight: 300;"><?php echo $track_id; ?></span></h4>
                        <h4><span style="font-weight: 700;">Artist(s):</span> <span style="font-weight: 300;"><?php echo $track_info['artists']; ?></span></h4>
                        <h4><span style="font-weight: 700;">Spotify ID:</span> <span style="font-weight: 300;"><?php echo $track_info['spotify_id']; ?></span></h4>
                        <h4><span style="font-weight: 700;">Spotify URL:</span> <span style="font-weight: 300;"><a href="<?php echo $track_info['spotify_url']; ?>" target="_blank">Link</a></span></h4>
                        <h4><span style="font-weight: 700;">Preview URL:</span> <span style="font-weight: 300;"><a href="<?php echo $track_info['preview_url']; ?>" target="_blank">Link</a></span></h4>
                        <h4><span style="font-weight: 700;">Duration:</span> <span style="font-weight: 300;"><?php echo $minutes . ":" . $seconds_padded; ?></span></h4>
                    </p>
                </div>

                <div class="col-md-6">
                    <p><h4><span style="font-weight: 700;">Clip</span></h4></p>
                    <iframe src="<?php echo $track_info['preview_url']; ?>" height="100" width="300" style="border: none;"></iframe>
                </div>

            </div>

            <hr>

            <div class="row" style="padding-bottom: 10px;">

                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['danceability']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Danceability</h4>
                            <p class="card-text" style="font-size: 1rem;">Danceability describes how suitable a track is for dancing based on a combination of musical elements including tempo, rhythm stability, beat strength, and overall regularity. A value of 0.0 is least danceable and 1.0 is most danceable.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['energy']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Energy</h4>
                            <p class="card-text" style="font-size: 1rem;">Energy is a measure from 0.0 to 1.0 and represents a perceptual measure of intensity and activity. Typically, energetic tracks feel fast, loud, and noisy. For example, death metal has high energy, while a Bach prelude scores low on the scale.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['musical_key']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Key</h4>
                            <p class="card-text" style="font-size: 1rem;">The key the track is in. Integers map to pitches using standard Pitch Class notation . E.g. 0 = C, 1 = C♯/D♭, 2 = D, and so on.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['loudness']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Loudness</h4>
                            <p class="card-text" style="font-size: 1rem;">The overall loudness of a track in decibels (dB). Loudness values are averaged across the entire track and are useful for comparing relative loudness of tracks. Loudness is the quality of a sound that is the primary psychological correlate of physical strength (amplitude). Values typical range between -60 and 0 db.</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row" style="padding-bottom: 10px;">

                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['mode']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Mode</h4>
                            <p class="card-text" style="font-size: 1rem;">Mode indicates the modality (major or minor) of a track, the type of scale from which its melodic content is derived. Major is represented by 1 and minor is 0.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['speechiness']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Speechiness</h4>
                            <p class="card-text" style="font-size: 1rem;">The more exclusively speech-like the recording (e.g. talk show, audio book), the closer to 1.0 the value. Values above 0.66 describe tracks that are probably entirely spoken words. Values between 0.33 and 0.66 describe tracks that may contain both music and speech (rap). Values below 0.33 most likely represent music and other non-speech-like tracks.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['acousticness']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Acousticness</h4>
                            <p class="card-text" style="font-size: 1rem;">A confidence measure from 0.0 to 1.0 of whether the track is acoustic. 1.0 represents high confidence the track is acoustic.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['instrumentalness']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Instrumentalness</h4>
                            <p class="card-text" style="font-size: 1rem;">Predicts whether a track contains no vocals. “Ooh” and “aah” sounds are treated as instrumental in this context. Rap or spoken word tracks are clearly “vocal”. The closer the instrumentalness value is to 1.0, the greater likelihood the track contains no vocal content. Values above 0.5 are intended to represent instrumental tracks.</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row" style="padding-bottom: 10px;">

                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['liveness']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Liveness</h4>
                            <p class="card-text" style="font-size: 1rem;">Detects the presence of an audience in the recording. Higher liveness values represent an increased probability that the track was performed live. A value above 0.8 provides strong likelihood that the track is live.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['valence']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Valence</h4>
                            <p class="card-text" style="font-size: 1rem;">A measure from 0.0 to 1.0 describing the musical positiveness conveyed by a track. Tracks with high valence sound more positive (e.g. happy, cheerful, euphoric), while tracks with low valence sound more negative (e.g. sad, depressed, angry).</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_features_info['tempo']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Tempo</h4>
                            <p class="card-text" style="font-size: 1rem;">The overall estimated tempo of a track in beats per minute (BPM). In musical terminology, tempo is the speed or pace of a given piece and derives directly from the average beat duration.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center" style="width: 20rem; border: 0.5px solid #C4C4C4; padding: 25px; height: 30rem;">
                        <div class="card-body">
                            <h1><?php echo $track_info['popularity']; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Popularity</h4>
                            <p class="card-text" style="font-size: 1rem;">The popularity of a track is a value between 0 and 100, with 100 being the most popular. The popularity is calculated by algorithm and is based, in the most part, on the total number of plays the track has had and how recent those plays are.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <br />
        <br />

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        </body>
        </html>

        <?php

    } else {

        header('WWW-Authenticate: Basic realm="Secure Site"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'This page requires authentication.';
        exit;

    }

}

