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
        $this->db = new PDO('mysql:host=localhost;dbname=music;charset=utf8mb4', 'root', 'thomas8185');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

}