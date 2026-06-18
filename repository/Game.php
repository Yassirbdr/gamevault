<?php

class Game
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // 1. READ (Alle games ophalen via LEFT JOIN)
    public function getAllGames(): array
    {
        $sql = "SELECT g.game_id, g.title, g.personal_rating, g.image, 
                       gen.name AS genre_name, 
                       plat.name AS platform_name 
                FROM games g
                LEFT JOIN genres gen ON g.genre_id = gen.genre_id
                LEFT JOIN platforms plat ON g.platform_id = plat.platform_id
                ORDER BY g.game_id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Extra READ voor bewerken
    public function getGameById(int $id): array
    {
        $sql = "SELECT * FROM games WHERE game_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    // 2. CREATE (Game toevoegen)
    public function createGame(string $title, string $rating, int $genre_id, int $platform_id, ?string $image): bool
    {
        $sql = "INSERT INTO games (title, personal_rating, genre_id, platform_id, image) 
                VALUES (:title, :rating, :genre_id, :platform_id, :image)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':rating' => $rating,
            ':genre_id' => $genre_id,
            ':platform_id' => $platform_id,
            ':image' => $image
        ]);
    }

    // 3. UPDATE (Game bijwerken)
    public function updateGame(int $id, string $title, string $rating, int $genre_id, int $platform_id, ?string $image): bool
    {
        if ($image) {
            $sql = "UPDATE games SET title = :title, personal_rating = :rating, genre_id = :genre_id, platform_id = :platform_id, image = :image WHERE game_id = :id";
            $params = [':title' => $title, ':rating' => $rating, ':genre_id' => $genre_id, ':platform_id' => $platform_id, ':image' => $image, ':id' => $id];
        } else {
            $sql = "UPDATE games SET title = :title, personal_rating = :rating, genre_id = :genre_id, platform_id = :platform_id WHERE game_id = :id";
            $params = [':title' => $title, ':rating' => $rating, ':genre_id' => $genre_id, ':platform_id' => $platform_id, ':id' => $id];
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // 4. DELETE (Game verwijderen)
    public function deleteGame(int $id): bool
    {
        $sqlTransactions = "DELETE FROM transactions WHERE game_id = :id";
        $stmtTransactions = $this->db->prepare($sqlTransactions);
        $stmtTransactions->execute([':id' => $id]);

        $sqlGame = "DELETE FROM games WHERE game_id = :id";
        $stmtGame = $this->db->prepare($sqlGame);
        return $stmtGame->execute([':id' => $id]);
    }
}