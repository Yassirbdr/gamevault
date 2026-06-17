<?php
require_once '../config/Database.php';
require_once '../repository/GameRepository.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        // HIER GING HET MIS: We moeten $conn meegeven aan de Repository!
        $repo = new GameRepository($conn);

        // Data ophalen uit het formulier
        $title = $_POST['title'];
        $description = $_POST['description'];
        $released_at = $_POST['released_at'];
        $personal_rating = $_POST['personal_rating'];

        // Game toevoegen via de repository
        if ($repo->create($title, $description, $released_at, $personal_rating)) {
            // Succes? Stuur de gebruiker terug naar het overzicht
            header("Location: games.php");
            exit();
        } else {
            $message = "<p style='color:red;'>❌ Er is iets misgegaan bij het opslaan.</p>";
        }
    } catch (Exception $e) {
        $message = "<p style='color:red;'>❌ Fout: " . $e->getMessage() . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Game Toevoegen</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .form-container { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="date"], input[type="number"], textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-cancel { background-color: #6c757d; margin-left: 10px; }
    </style>
</head>
<body>

    <h1>🎮 Nieuwe Game Toevoegen</h1>
    
    <?php echo $message; ?>

    <div class="form-container">
        <form action="create.php" method="POST">
            <div class="form-group">
                <label for="title">Titel:</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Beschrijving:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="released_at">Release Datum:</label>
                <input type="date" id="released_at" name="released_at" required>
            </div>

            <div class="form-group">
                <label for="personal_rating">Persoonlijke Rating (1-10):</label>
                <input type="number" id="personal_rating" name="personal_rating" min="1" max="10" required>
            </div>

            <button type="submit" class="btn">Opslaan</button>
            <a href="games.php" class="btn btn-cancel">Annuleren</a>
        </form>
    </div>

</body>
</html>