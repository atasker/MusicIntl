<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/4/18
 * Time: 4:12 PM
 */

include __DIR__ . '/../inc.php';

class Track {

    public $conn;

    public function __construct() {
        $this->conn = new DB();
    }

    public function getAllUserTracks($id) {
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $user = new User();
        $accessToken = $user->getOrRefreshToken($id);
        $api->setAccessToken($accessToken);
        $tracks = $api->getMySavedTracks(['limit' => 50]);
        return $tracks;
    }

    public function getRecentTracks($id) {
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $user = new User();
        $accessToken = $user->getOrRefreshToken($id);
        $api->setAccessToken($accessToken);
        $tracks = $api->getMyRecentTracks(['limit' => 50]);
        return $tracks;
    }

    public function getAllTracks() {
        $stmt = $this->conn->db->query("SELECT * FROM tracks");
        $results = $stmt->fetchAll();
        return $results;
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
            $user = new User();
            $random_id = $user->getRandomUserId();
            $accessToken = $user->getOrRefreshToken($random_id);
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
                // Notify user if this fails, currently nothing happens (or there'll be a fatal error).
                $track_features = new TrackFeatures();
                $track_features->saveTrackFeatures($api, $spotify_id);
                // 2 = Track save successful
                return 2;
            } else {
                // 3 = Incorrect Spotify ID or database error
                return 3;
            }
        }
    }

    public function getTrack($track_id) {
        $stmt = $this->conn->db->query("SELECT * FROM tracks WHERE id = $track_id");
        $result = $stmt->fetch();
        return $result;
    }

}