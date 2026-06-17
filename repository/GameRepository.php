<?php
class GameRepository {
    private $conn;

    // We geven de database verbinding mee via de constructor
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // 1. READ ALL
    public function getAll() {
        $query = "SELECT * FROM games";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. READ SINGLE
    public function getById($game_id) {
        $query = "SELECT * FROM games WHERE game_id = :game_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':game_id', $game_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. CREATE (Met werkende genre_id en platform_id koppeling)
    public function create($title, $description, $released_at, $personal_rating, $genre_id = 1, $platform_id = 1) {
        $query = "INSERT INTO games (title, description, released_at, personal_rating, genre_id, platform_id) 
                  VALUES (:title, :description, :released_at, :personal_rating, :genre_id, :platform_id)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':released_at', $released_at);
        $stmt->bindParam(':personal_rating', $personal_rating);
        $stmt->bindParam(':genre_id', $genre_id);
        $stmt->bindParam(':platform_id', $platform_id);
        
        return $stmt->execute();
    }

    // 4. UPDATE (Met werkende genre_id en platform_id koppeling)
    public function update($game_id, $title, $description, $released_at, $personal_rating, $genre_id = 1, $platform_id = 1) {
        $query = "UPDATE games SET title = :title, description = :description, 
                  released_at = :released_at, personal_rating = :personal_rating,
                  genre_id = :genre_id, platform_id = :platform_id 
                  WHERE game_id = :game_id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':game_id', $game_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':released_at', $released_at);
        $stmt->bindParam(':personal_rating', $personal_rating);
        $stmt->bindParam(':genre_id', $genre_id);
        $stmt->bindParam(':platform_id', $platform_id);
        
        return $stmt->execute();
    }

    // 5. DELETE
    public function delete($game_id) {
        $query = "DELETE FROM games WHERE game_id = :game_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':game_id', $game_id);
        return $stmt->execute();
    }
}
?>