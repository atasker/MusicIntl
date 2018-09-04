<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/4/18
 * Time: 4:12 PM
 */

include '../inc.php';

class Admin {

    public $conn;

    public function __construct() {
        $this->conn = new DB();
    }

    public function getAllUsers() {
        $stmt = $this->conn->db->query("SELECT * FROM users");
        $results = $stmt->fetchAll();
        return $results;
    }

}