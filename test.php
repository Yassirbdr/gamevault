<?php
// Test Database connectie
require_once 'config/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    echo "<p style='color:green;'>✅ Database connectie GELUKT!</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Database fout: " . $e->getMessage() . "</p>";
    exit();
}

// Test Repository
require_once 'repository/GameRepository.php';

try {
    // We geven hier $conn mee tussen de haakjes
    $repo = new GameRepository($conn);
    $games = $repo->getAll();

    echo "<h2>Aantal games gevonden: " . count($games) . "</h2>";

    if (!empty($games)) {
        echo "<pre>";
        print_r($games[0]);   // toon eerste game
        echo "</pre>";
    } else {
        echo "<p style='color:orange;'>Geen games gevonden (tabel is leeg).</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Repository fout: " . $e->getMessage() . "</p>";
}
?>