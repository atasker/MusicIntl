<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 1/27/18
 * Time: 1:59 PM
 */

require 'vendor/autoload.php';

class DB {

    public $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=us-cdbr-iron-east-01.cleardb.net;dbname=heroku_39aa6b411223895;charset=utf8mb4', 'b5694a083b8e31', 'ba6f9a41');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

}