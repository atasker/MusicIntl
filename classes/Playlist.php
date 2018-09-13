<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/12/18
 * Time: 12:13 PM
 */

include __DIR__ . '/../inc.php';

class Playlist {

    public $conn;

    public function __construct() {
        $this->conn = new DB();
    }

    public function getAllPlaylists() {
        $stmt = $this->conn->db->query("SELECT * FROM playlists");
        $results = $stmt->fetchAll();
        return $results;
    }

    public function duplicatePlaylist($name) {
        $stmt = $this->conn->db->query("SELECT * FROM playlists WHERE name = '$name'");
        $results = $stmt->fetch();
        if ($results) {
            // Duplicate
            return true;
        } else {
            // Unique
            return false;
        }
    }

    public function savePlaylist($name) {
        if ($this->duplicatePlaylist($name)) {
            // 1 = Duplicate
            return 1;
        } else {
            $stmt = $this->conn->db->prepare("INSERT INTO playlists (name) VALUES (:name)");
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            if ($stmt->execute()) {
                // 2 = Save successful
                return 2;
            } else {
                // 3 = Database error
                return 3;
            }
        }
    }

    public function getName($id) {
        $stmt = $this->conn->db->query("SELECT name FROM playlists WHERE id = $id");
        $results = $stmt->fetch();
        if ($results) {
            return $results[0];
        } else {
            return "No name associated with playlist";
        }
    }

    public function saveTracksToPlaylist($playlist_id, $track_array) {
        $playlist_id = (int)$playlist_id;
        $success = [];
        foreach ($track_array as $track_id) {
            $track_id = (int)$track_id;
            $stmt = $this->conn->db->prepare("INSERT INTO track_playlist (track_id, playlist_id) VALUES (:track_id, :playlist_id)");
            $stmt->bindValue(':track_id', $track_id, PDO::PARAM_INT);
            $stmt->bindValue(':playlist_id', $playlist_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                array_push($success, 1);
            } else {
                array_push($success, 0);
            }
        }
        if (!in_array(0, $success)) {
            // Save successful
            return 1;
        } else {
            // Unable to save
            return 0;
        }
    }

    public function getPlaylistTracks($playlist_id) {
        $stmt = $this->conn->db->query("SELECT tr.id, tr.title, tr.artists, tr.spotify_url, tr.preview_url 
                                                    FROM tracks AS tr LEFT JOIN track_playlist AS tp ON tr.id = tp.track_id
                                                    LEFT JOIN playlists AS pl ON tp.playlist_id = pl.id WHERE pl.id = $playlist_id");
        $results = $stmt->fetchAll();
        return $results;
    }

    public function dontListTracksAlreadyOnPlaylist($playlist_id) {
        $stmt = $this->conn->db->query("SELECT tr.id, tr.title, tr.artists, tr.spotify_url, tr.preview_url FROM tracks AS tr
                                                  WHERE tr.id NOT IN (SELECT tp.track_id FROM track_playlist AS tp WHERE tp.playlist_id = $playlist_id)");
        $results = $stmt->fetchAll();
        return $results;
    }

}