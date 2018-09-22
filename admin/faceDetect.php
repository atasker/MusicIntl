<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/22/18
 * Time: 12:14 AM
 */

include __DIR__ . '/../inc.php';

$image = ['url' => 'https://media.glamour.com/photos/5696d70301ed531c6f00b97d/master/w_1280,c_limit/sex-love-life-2015-05-woman-1-main.jpg'];
$face = new FaceDetect($image);
$get_face = $face->analyzeAll()->getFaces();
$analyze = json_decode($get_face, true);
$gender = $analyze[0]['faceAttributes']['gender'];
$age = $analyze[0]['faceAttributes']['age'];
$emotion = $analyze[0]['faceAttributes']['emotion']; // Array
$smile = $analyze[0]['faceAttributes']['smile'];
$glasses = $analyze[0]['faceAttributes']['glasses'];
$facialHair = $analyze[0]['faceAttributes']['facialHair']; // Array
// Computing dominant hair color (highest confidence rating)
$hair = $analyze[0]['faceAttributes']['hair']['hairColor']; // Array
$max = ['', 0];
foreach ($hair as $color) {
    if ($color['confidence'] > $max[1]) {
        $max[0] = $color['color'];
        $max[1] = $color['confidence'];
    }
}
$makeup = $analyze[0]['faceAttributes']['makeup']; // Array
$accessories = $analyze[0]['faceAttributes']['accessories']; // Array
$exposure = $analyze[0]['faceAttributes']['exposure']; // Array
$occlusion = $analyze[0]['faceAttributes']['occlusion']; // Array

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
                .circle {
                    height: 100px;
                    width: 100px;
                    border-radius: 50%;
                    display: inline-block;
                }
            </style>

            <script type="text/javascript">
                google.charts.load('current', {'packages':['bar']});
                google.charts.setOnLoadCallback(drawStuff);

                function drawStuff() {
                    var emotions_array = <?php echo json_encode($emotion); ?>;

                    var data = new google.visualization.arrayToDataTable([
                        ['Emotion', 'Score'],
                        ["Anger" + " " + emotions_array['anger'], emotions_array['anger']],
                        ["Contempt" + " " + emotions_array['contempt'], emotions_array['contempt']],
                        ["Disgust" + " " + emotions_array['disgust'], emotions_array['disgust']],
                        ["Fear" + " " + emotions_array['fear'], emotions_array['fear']],
                        ['Happiness' + " " + emotions_array['happiness'], emotions_array['happiness']],
                        ['Neutral' + " " + emotions_array['neutral'], emotions_array['neutral']],
                        ['Sadness' + " " + emotions_array['sadness'], emotions_array['sadness']],
                        ['Surprise' + " " + emotions_array['surprise'], emotions_array['surprise']]
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

            <div align="right">
                <span style="font-weight: bold;">Image URL&nbsp;&nbsp;</span>
                <input type="text" name="face_url" id="face_url" placeholder="">&nbsp;&nbsp;
                <a href="#" id="face_url_button"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size: 1.6rem; vertical-align: middle; color: forestgreen;"></span></a>
                <hr>
            </div>

            <div class="row" style="padding-bottom: 40px;">

                <div class="col-md-6">
                    <div id="top_x_div" style="width: 800px; height: 400px;"></div>
                </div>

                <div class="col-md-6" align="right">
                    <img src="<?php echo $image['url']; ?>" height="200" style="" />
                    <h5><span style="font-weight: 700;">Gender:</span> <span style="font-weight: 300;"><?php echo ucfirst($gender); ?></span></h5>
                    <h5><span style="font-weight: 700;">Age:</span> <span style="font-weight: 300;"><?php echo $age; ?></span></h5>
                </div>

            </div>

            <div class="row" style="padding-bottom: 10px;">

                <div class="col-md-6">
                    <div class="card text-center" style="width: 100%; height: 15rem; border: 0.5px solid #C4C4C4; padding: 25px;">
                        <div class="card-body">
                            <h1><?php echo ($smile == 1 ? "Yes" : $smile); ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Smile</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center" style="width: 100%; height: 15rem; border: 0.5px solid #C4C4C4; padding: 25px;">
                        <div class="card-body">
                            <h1><?php echo (strpos($glasses, 'No') !== false) ? "No" : "Yes"; ?></h1>
                            <h4 class="card-title" style="font-weight: 700;">Glasses</h4>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row" style="padding-bottom: 10px;">

                <div class="col-md-6">
                    <h2>Hair</h2>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h3><?php echo ($max[0] == 'blond' ? ucfirst($max[0]) . "e" : ucfirst($max[0])); ?></h3>
                        </div>
                        <div class="col-md-6">
                            <span class="circle" style="background-color: <?php echo ($max[0] == 'blond' ? "#FFFACD" : $max[0]); ?>"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h2>Facial Hair</h2>
                    <hr>
                    <?php
                    foreach ($facialHair as $key => $value) {
                        echo "<div class='row'>";
                        echo "    <div class='col-md-6'>";
                        echo "        <h4>" . ucfirst($key) . ":" .  "</h4>";
                        echo "    </div>";
                        echo "    <div class='col-md-6'>";
                        echo "        <h4>" . $value . "</h4>";
                        echo "    </div>";
                        echo "</div>";
                    }
                    ?>
                </div>

            </div>

            <div class="row" style="padding-bottom: 10px;">

                <div class="col-md-6">
                    <h2>Makeup</h2>
                    <hr>
                    <?php
                    if (!empty($makeup)) {
                        foreach ($makeup as $key => $value) {
                            echo "<div class='row'>";
                            echo "    <div class='col-md-6'>";
                            echo "        <h4>" . ucfirst($key) . ":" .  "</h4>";
                            echo "    </div>";
                            echo "    <div class='col-md-6'>";
                            echo "        <h4>" . $value . "</h4>";
                            echo "    </div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<h4>No Makeup Detected</h4>";
                    }
                    ?>
                </div>

                <div class="col-md-6">
                    <h2>Accessories</h2>
                    <hr>
                    <?php
                    if (!empty($accessories)) {
                        foreach ($accessories as $accessory) {
                            echo "<div class='row'>";
                            echo "    <div class='col-md-6'>";
                            echo "        <h4>" . ucfirst($accessory['type']) . ":" .  "</h4>";
                            echo "    </div>";
                            echo "    <div class='col-md-6'>";
                            echo "        <h4>" . $accessory['confidence'] . "</h4>";
                            echo "    </div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<h4>No Accessories Detected</h4>";
                    }
                    ?>
                </div>

            </div>

            <div class="row" style="padding-bottom: 10px;">

                <div class="col-md-6">
                    <h2>Exposure</h2>
                    <hr>
                    <?php
                    if (!empty($exposure)) {
                        foreach ($exposure as $key => $value) {
                            echo "<div class='row'>";
                            echo "    <div class='col-md-6'>";
                            echo "        <h4>" . ucfirst($key) . ":" .  "</h4>";
                            echo "    </div>";
                            echo "    <div class='col-md-6'>";
                            echo "        <h4>" . $value . "</h4>";
                            echo "    </div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<h4>No Exposure Data</h4>";
                    }
                    ?>
                </div>

                <div class="col-md-6">
                    <h2>Occlusion</h2>
                    <hr>
                    <?php
                    if (!empty($occlusion)) {
                        foreach ($occlusion as $key => $value) {
                            echo "<div class='row'>";
                            echo "    <div class='col-md-6'>";
                            echo "        <h4>" . ucfirst($key) . ":" .  "</h4>";
                            echo "    </div>";
                            echo "    <div class='col-md-6'>";
                            echo "        <h4>" . (empty($value) ? "No" : $value) . "</h4>";
                            echo "    </div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<h4>No Occlusion Data</h4>";
                    }
                    ?>
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