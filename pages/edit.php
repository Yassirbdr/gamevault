<?php
require_once '../config/Database.php';
require_once '../repository/GameRepository.php';

$message = "";
$game = null;

try {
    $db = new Database();
    $conn = $db->getConnection();
    $repo = new GameRepository($conn);

    // 1. Haal de huidige gegevens van de game op basis van het ID in de URL
    if (isset($_GET['id'])) {
        $game = $repo->getById($_GET['id']);
    }

    if (!$game) {
        die("Game niet gevonden.");
    }

    // 2. Verwerk het formulier als er op 'Bijwerken' wordt geklikt
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $game_id = $_POST['game_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $released_at = $_POST['released_at'];
        $personal_rating = $_POST['personal_rating'];

        if ($repo->update($game_id, $title, $description, $released_at, $personal_rating)) {
            header("Location: games.php");
            exit();
        } else {
            $message = "<p style='color:red;'>❌ Bewerken mislukt.</p>";
        }
    }
} catch (Exception $e) {
    $message = "<p style='color:red;'>❌ Fout: " . $e->getMessage() . "</p>";
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Game Bewerken</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .form-container { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="date"], input[type="number"], textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 10px 15px; background-color: #ffc107; color: black; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: bold; }
        .btn-cancel { background-color: #6c757d; color: white; margin-left: 10px; }
    </style>
</head>
<body>

    <h1>🎮 Game Bewerken</h1>
    
    <?php echo $message; ?>

    <div class="form-container">
        <form action="edit.php?id=<?php echo $game['game_id']; ?>" method="POST">
            <input type="hidden" name="game_id" value="<?php echo $game['game_id']; ?>">

            <div class="form-group">
                <label for="title">Titel:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($game['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Beschrijving:</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($game['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="released_at">Release Datum:</label>
                <input type="date" id="released_at" name="released_at" value="<?php echo $game['released_at']; ?>" required>
            </div>

            <div class="form-group">
                <label for="personal_rating">Persoonlijke Rating (1-10):</label>
                <input type="number" id="personal_rating" name="personal_rating" min="1" max="10" value="<?php echo $game['personal_rating']; ?>" required>
            </div>

            <button type="submit" class="btn">Bijwerken</button>
            <a href="games.php" class="btn btn-cancel">Annuleren</a>
        </form>
    </div>

</body>
</html>