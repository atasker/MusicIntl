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
        // Need to add refresh logic
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
                $session = new SpotifyWebAPI\Session(
                    '13ebd10f15714843aea76c5c7259e516',
                    '93b62230ebd64bcb8640329caaf9c90d',
                    'https://musicintl.herokuapp.com/callback.php'
                    //'http://localhost/MusicIntl/callback.php'
                );
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

    public function getAllUserTracks($id) {
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $accessToken = $this->getOrRefreshToken($id);
        $api->setAccessToken($accessToken);
        $tracks = $api->getMySavedTracks(['limit' => 50]);
        return $tracks;
    }

    public function getRecentTracks($id) {
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $accessToken = $this->getOrRefreshToken($id);
        $api->setAccessToken($accessToken);
        $tracks = $api->getMyRecentTracks(['limit' => 50]);
        return $tracks;
    }

}