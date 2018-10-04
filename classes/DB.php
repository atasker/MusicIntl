<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 1/27/18
 * Time: 1:59 PM
 */

include __DIR__ . '/../inc.php';

class DB {

    public $db;

    public function __construct() {
        $this->db = new PDO(getenv('DATABASE_DSN'), getenv('DATABASE_USERNAME'), getenv('DATABASE_PASSWORD'));
        //$this->db = new PDO(getenv('DB_LOCAL_DSN'), getenv('DB_LOCAL_USERNAME'), getenv('DB_LOCAL_PASSWORD'));
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

}