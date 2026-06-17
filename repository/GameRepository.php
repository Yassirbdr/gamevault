<?php
class GameRepository {
    private $db;

    public function __construct($database) {
        $this->db = $database->getConnection();
    }

    public function getAllGames() {
        $sql = "SELECT games.*, genres.name AS genre_name FROM games 
                LEFT JOIN genres ON games.genre_id = genres.genre_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGameById($id) {
        $sql = "SELECT * FROM games WHERE game_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createGame($title, $rating, $genre_id, $image) {
        $sql = "INSERT INTO games (title, personal_rating, genre_id, image) VALUES (:title, :rating, :genre_id, :image)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':rating' => $rating,
            ':genre_id' => $genre_id,
            ':image' => $image
        ]);
    }

    public function updateGame($id, $title, $rating, $genre_id, $image) {
        if ($image) {
            $sql = "UPDATE games SET title = :title, personal_rating = :rating, genre_id = :genre_id, image = :image WHERE game_id = :id";
            $params = [':title' => $title, ':rating' => $rating, ':genre_id' => $genre_id, ':image' => $image, ':id' => $id];
        } else {
            $sql = "UPDATE games SET title = :title, personal_rating = :rating, genre_id = :genre_id WHERE game_id = :id";
            $params = [':title' => $title, ':rating' => $rating, ':genre_id' => $genre_id, ':id' => $id];
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteGame($id) {
        $sql = "DELETE FROM games WHERE game_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}