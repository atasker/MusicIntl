<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 8/30/18
 * Time: 12:08 PM
 */

include 'inc.php';

$message = $_GET['message'];

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
        .welcome-message {
            margin: 0 auto;
            font-size: 1.5rem;
            width: 50%;
            font-weight: 300;
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

<div class="admin-link">
    <a href="admin/adminUsers.php" style="text-decoration: none; color: #FFF; position: fixed; top: 0; right: 0;">Admin</a>
</div>

<div class="center">
    <div class="center-inner">
        <div class="hero-text">
            InterTracks
        </div>
        <div class="welcome-message">
            <?php echo $message; ?>
        </div>
    </div>
</div>

</body>
</html>
