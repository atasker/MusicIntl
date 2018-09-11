<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/4/18
 * Time: 4:12 PM
 */

include __DIR__ . '/../inc.php';

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

    public function getAllTracks() {
        $stmt = $this->conn->db->query("SELECT * FROM tracks");
        $results = $stmt->fetchAll();
        return $results;
    }

    public function getRandomUserId() {
        $stmt = $this->conn->db->query("SELECT id FROM users ORDER BY RAND() LIMIT 1");
        $results = $stmt->fetch();
        return $results['id'];
    }

    public function duplicateTrack($spotify_id) {
        $stmt = $this->conn->db->query("SELECT * FROM tracks WHERE spotify_id = '$spotify_id'");
        $results = $stmt->fetch();
        if ($results) {
            // Duplicate
            return true;
        } else {
            // Unique
            return false;
        }
    }

    public function saveTrack($input_id) {
        if ($this->duplicateTrack($input_id)) {
            // 1 = Duplicate Track
            return 1;
        } else {
            $api = new SpotifyWebAPI\SpotifyWebAPI();
            $random_id = $this->getRandomUserId();
            $accessToken = $this->getOrRefreshToken($random_id);
            $api->setAccessToken($accessToken);
            $track = $api->getTrack($input_id);

            // Track name
            $title = $track->name;

            // Artists
            $artists_array = [];
            foreach ($track->artists as $artist) {
                $artists_array[] = $artist->name;
            }
            $artists_string = implode(",", $artists_array);

            // Spotify ID
            $spotify_id = $track->id;

            // Spotify URL
            $spotify_url = $track->external_urls->spotify;

            // Preview URL
            $preview_url = $track->preview_url;

            // Duration (ms)
            $duration = $track->duration_ms;

            // Popularity
            $popularity = $track->popularity;

            $stmt = $this->conn->db->prepare("INSERT INTO tracks (title, artists, spotify_id, spotify_url, preview_url, duration, popularity) VALUES (:title, :artists_string, :spotify_id, :spotify_url, :preview_url, :duration, :popularity)");
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':artists_string', $artists_string, PDO::PARAM_STR);
            $stmt->bindValue(':spotify_id', $spotify_id, PDO::PARAM_STR);
            $stmt->bindValue(':spotify_url', $spotify_url, PDO::PARAM_STR);
            $stmt->bindValue(':preview_url', $preview_url, PDO::PARAM_STR);
            $stmt->bindValue(':duration', $duration, PDO::PARAM_STR);
            $stmt->bindValue(':popularity', $popularity, PDO::PARAM_INT);
            if ($stmt->execute()) {
                // 2 = Track save successful
                return 2;
            } else {
                // 3 = Incorrect Spotify ID or database error
                return 3;
            }
        }
    }

}