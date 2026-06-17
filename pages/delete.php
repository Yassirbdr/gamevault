<?php
require_once '../config/Database.php';
require_once '../repository/GameRepository.php';

// Check of er een ID is meegegeven in de URL
if (isset($_GET['id'])) {
    try {
        $db = new Database();
        $conn = $db->getConnection();
        $repo = new GameRepository($conn);

        $game_id = $_GET['id'];

        // Verwijder de game
        $repo->delete($game_id);
    } catch (Exception $e) {
        // Mocht er een fout zijn, kun je die hier eventueel opvangen
    }
}

// Stuur de gebruiker altijd direct weer terug naar het overzicht
header("Location: games.php");
exit();
?>