<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/20/18
 * Time: 4:21 PM
 */

include __DIR__ . '/../inc.php';

class TrackFeatures {

    public $conn;

    public function __construct() {
        $this->conn = new DB();
    }

    public function saveTrackFeatures($api, $spotify_id) {
        $features = $api->getAudioFeatures($spotify_id);
        $stmt = $this->conn->db->prepare("INSERT INTO track_features
                                                    (track_id, danceability, energy, musical_key, loudness, mode, speechiness,
                                                    acousticness, instrumentalness, liveness, valence, tempo)
                                                    VALUES (
                                                    (SELECT id FROM tracks WHERE spotify_id = :spotify_id),
                                                    :danceability, :energy, :musical_key, :loudness, :mode, :speechiness,
                                                    :acousticness, :instrumentalness, :liveness, :valence, :tempo
                                                    )");
        $stmt->bindValue(':spotify_id', $spotify_id, PDO::PARAM_STR);
        $stmt->bindValue(':danceability', $features->audio_features[0]->danceability, PDO::PARAM_STR);
        $stmt->bindValue(':energy', $features->audio_features[0]->energy, PDO::PARAM_STR);
        $stmt->bindValue(':musical_key', $features->audio_features[0]->key, PDO::PARAM_INT);
        $stmt->bindValue(':loudness', $features->audio_features[0]->loudness, PDO::PARAM_STR);
        $stmt->bindValue(':mode', $features->audio_features[0]->mode, PDO::PARAM_INT);
        $stmt->bindValue(':speechiness', $features->audio_features[0]->speechiness, PDO::PARAM_STR);
        $stmt->bindValue(':acousticness', $features->audio_features[0]->acousticness, PDO::PARAM_STR);
        $stmt->bindValue(':instrumentalness', $features->audio_features[0]->instrumentalness, PDO::PARAM_STR);
        $stmt->bindValue(':liveness', $features->audio_features[0]->liveness, PDO::PARAM_STR);
        $stmt->bindValue(':valence', $features->audio_features[0]->valence, PDO::PARAM_STR);
        $stmt->bindValue(':tempo', $features->audio_features[0]->tempo, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getTrackFeatures($track_id) {
        $stmt = $this->conn->db->query("SELECT * FROM track_features WHERE track_id = $track_id");
        $result = $stmt->fetch();
        return $result;
    }

}