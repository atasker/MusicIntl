<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/10/18
 * Time: 10:59 PM
 */

include __DIR__ . '/../inc.php';

$image = $_POST['image'];

$pattern = '/(?:https?:\/\/)?(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/';

// TODO: check if url is valid image url, currently a non image url like google.com would work

if(preg_match($pattern, $image)) {
    if (stripos($image, 'jpg') !== false || strpos($image, 'png') !== false) {
        echo $image;
    } else {
        echo 0;
    }
} else {
    echo 1;
}
