<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/12/18
 * Time: 12:00 PM
 */

include __DIR__ . '/../inc.php';

class User {

    public $conn;

    public function __construct() {
        $this->conn = new DB();
    }

    public function getAllUsers() {
        $stmt = $this->conn->db->query("SELECT * FROM users");
        $results = $stmt->fetchAll();
        return $results;
    }

    public function getEmail($id) {
        $stmt = $this->conn->db->query("SELECT email FROM users WHERE id = $id");
        $results = $stmt->fetch();
        if ($results) {
            return $results[0];
        } else {
            return "No email on file";
        }
    }

    public function getOrRefreshToken($id) {
        $stmt = $this->conn->db->query("SELECT accessToken, refreshToken FROM users WHERE id = $id");
        $results = $stmt->fetch();
        if ($results) {
            // Check if access token is still valid
            $api = new SpotifyWebAPI\SpotifyWebAPI();
            $accessToken = $results[0];
            $refreshToken = $results[1];
            $api->setAccessToken($accessToken);
            try {
                $api->me();
                // Access Token still valid
                return $accessToken;
            } catch (Exception $e) {
                // Access Token expired
                // Is this new session necessary?
                global $session;
                include_once __DIR__ . '/../spotify_session.php';
                // Get new Access Token
                $session->refreshAccessToken($refreshToken);
                $newAccessToken = $session->getAccessToken();
                // Save new Access Token
                $stmt2 = $this->conn->db->query("UPDATE users SET accessToken = '$newAccessToken' WHERE id = $id");
                if ($stmt2->execute()) {
                    return $newAccessToken;
                } else {
                    return 'Could not refresh token.';
                }
            }
        } else {
            return 'No Access Token on file';
        }
    }

    public function getRandomUserId() {
        $stmt = $this->conn->db->query("SELECT id FROM users ORDER BY RAND() LIMIT 1");
        $results = $stmt->fetch();
        return $results['id'];
    }

    public function userPlaylistCount($id) {
        $stmt = $this->conn->db->query("SELECT COUNT(*) FROM user_playlist WHERE user_id = $id");
        $results = $stmt->fetch();
        return $results[0];
    }

}