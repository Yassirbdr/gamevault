<?php
require_once '../config/Database.php';
require_once '../repository/GameRepository.php';

$database = new Database();
$db = $database->connect();

// Genres ophalen voor het keuzemenu
$genreStmt = $db->query("SELECT * FROM genres");
$genres = $genreStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $rating = $_POST['rating'];
    $genre_id = $_POST['genre_id'];
    
    // Afbeelding upload verwerken
    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $imageName);
    }

    $gameRepo = new GameRepository($database);
    $gameRepo->createGame($title, $rating, $genre_id, $imageName);
    
    header("Location: games.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Game Toevoegen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans min-h-screen flex items-center justify-center py-12 px-4">
    <div class="bg-gray-800 border border-gray-700 p-8 rounded-xl shadow-2xl max-w-md w-full">
        <h1 class="text-3xl font-bold text-blue-500 mb-6 text-center">🎮 Game Toevoegen</h1>
        
        <form action="create.php" method="POST" enctype="multipart/form-data" class="space-y-5">
            <div>
                <label class="block text-sm font-medium mb-2">Titel van de game</label>
                <input type="text" name="title" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Rating (1 t/m 5)</label>
                <input type="number" name="rating" min="1" max="5" step="0.1" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Genre</label>
                <select name="genre_id" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-500">
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?= $genre['id'] ?>"><?= htmlspecialchars($genre['name']) ?></option>
                    <?php endjoin; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Cover Afbeelding</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
            </div>

            <div class="pt-4 flex space-x-4">
                <a href="games.php" class="w-1/2 text-center bg-gray-700 hover:bg-gray-600 py-3 rounded-lg font-semibold transition">Annuleren</a>
                <button type="submit" class="w-1/2 bg-blue-600 hover:bg-blue-700 py-3 rounded-lg font-semibold transition shadow-lg">Opslaan</button>
            </div>
        </form>
    </div>
</body>
</html>