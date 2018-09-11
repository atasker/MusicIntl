<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/10/18
 * Time: 10:59 PM
 */

include __DIR__ . '/../inc.php';

$input_id = $_POST['input_id'];

$admin = new Admin();
echo $admin->saveTrack($input_id);
