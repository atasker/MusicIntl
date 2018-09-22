<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/22/18
 * Time: 12:14 AM
 */

include __DIR__ . '/../inc.php';

use Ridvanbaluyos\Face\FaceDetection as FaceDetection;
$face = new FaceDetection("http://img2.timeinc.net/people/i/2014/database/140831/justin-bieber-300.jpg");
$analyze = $face->analyzeAll();
print_r($analyze);
die();

//$face = new FaceDetect("http://img2.timeinc.net/people/i/2014/database/140831/justin-bieber-300.jpg");
//$analyze = $face->analyzeFace();
//print_r($face);
//die();
//$gender = $analyze[0]['faceAttributes']['gender'];
//$age = $analyze[0]['faceAttributes']['age'];
//$emotion = $analyze[0]['faceAttributes']['emotion']; // Array
//$smile = $analyze[0]['faceAttributes']['smile'];
//$hair = $analyze[0]['faceAttributes']['hair']; // Array
//$facialHair = $analyze[0]['faceAttributes']['facialHair']; // Array
//$glasses = $analyze[0]['faceAttributes']['glasses'];
//$exposure = $analyze[0]['faceAttributes']['exposure']; // Array
//$makeup = $analyze[0]['faceAttributes']['makeup']; // Array
//$accessories = $analyze[0]['faceAttributes']['accessories']; // Array
//$occlusion = $analyze[0]['faceAttributes']['occlusion']; // Array

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
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700" rel="stylesheet">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
            <style type="text/css">
                body {
                    font-family: 'Muli', sans-serif;
                }
            </style>

            <script type="text/javascript">
                google.charts.load('current', {'packages':['bar']});
                google.charts.setOnLoadCallback(drawStuff);

                function drawStuff() {
                    var emotions_array = <?php echo json_encode($emotion); ?>;

                    var data = new google.visualization.arrayToDataTable([
                        ['Emotion', 'Score'],
                        ["Anger", emotions_array['anger']],
                        ["Contempt", emotions_array['contempt']],
                        ["Disgust", emotions_array['disgust']],
                        ["Fear", emotions_array['fear']],
                        ['Happiness', emotions_array['happiness']],
                        ['Neutral', emotions_array['neutral']],
                        ['Sadness', emotions_array['sadness']],
                        ['Surprise', emotions_array['surprise']]
                    ]);

                    var options = {
                        width: 800,
                        legend: { position: 'none' },
                        chart: {
                            title: 'Emotional Analysis',
                            subtitle: 'Scored from 0 to 1, 1 being the most dominant' },
                        axes: {
                            x: {
                                0: { side: 'top', label: 'Emotion'} // Top x-axis.
                            }
                        },
                        bar: { groupWidth: "90%" }
                    };

                    var chart = new google.charts.Bar(document.getElementById('top_x_div'));
                    // Convert the Classic options to Material options.
                    chart.draw(data, google.charts.Bar.convertOptions(options));
                };
            </script>
        </head>

        <body>

        <div class="container">

            <?php include_once 'navbar.php'; ?>

            <h2>Face Analysis</h2>
            <hr>

            <div class="row">

                <div class="col-md-12">
                    <div id="top_x_div" style="width: 800px; height: 600px;"></div>
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