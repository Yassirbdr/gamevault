<?php
require_once '../config/Database.php';
require_once '../repository/GameRepository.php';

$database = new Database();
$db = $database->getConnection();
$gameRepo = new GameRepository($database);

if (!isset($_GET['id'])) {
    header("Location: games.php");
    exit();
}

$id = $_GET['id'];
$game = $gameRepo->getGameById($id);

$genreStmt = $db->query("SELECT * FROM genres");
$genres = $genreStmt->fetchAll(PDO::FETCH_ASSOC);

$platformStmt = $db->query("SELECT * FROM platforms");
$platforms = $platformStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $rating = $_POST['rating'];
    $genre_id = $_POST['genre_id'];
    $platform_id = $_POST['platform_id'];
    
    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $imageName);
    }

    $gameRepo->updateGame($id, $title, $rating, $genre_id, $platform_id, $imageName);
    header("Location: games.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Game Vault | Bewerken</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans min-h-screen flex items-center justify-center py-12 px-4">
    <div class="bg-gray-800 border border-gray-700 p-8 rounded-2xl shadow-2xl max-w-md w-full">
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500 mb-6 text-center uppercase tracking-wide">✏️ Game Bewerken</h1>
        
        <form action="edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label class="block text-sm font-bold uppercase tracking-wider text-gray-400 mb-2">Titel van de game</label>
                <input type="text" name="title" value="<?= htmlspecialchars($game['title'] ?? '') ?>" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold uppercase tracking-wider text-gray-400 mb-2">Rating (1-5)</label>
                    <input type="number" name="rating" min="1" max="5" step="0.1" value="<?= htmlspecialchars($game['personal_rating'] ?? '') ?>" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold uppercase tracking-wider text-gray-400 mb-2">Genre</label>
                    <select name="genre_id" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors cursor-pointer">
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['genre_id'] ?>" <?= (isset($game['genre_id']) && $genre['genre_id'] == $game['genre_id']) ? 'selected' : '' ?>><?= htmlspecialchars($genre['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold uppercase tracking-wider text-gray-400 mb-2">Platform</label>
                <select name="platform_id" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors cursor-pointer">
                    <?php foreach ($platforms as $platform): ?>
                        <option value="<?= $platform['platform_id'] ?>" <?= (isset($game['platform_id']) && $platform['platform_id'] == $game['platform_id']) ? 'selected' : '' ?>><?= htmlspecialchars($platform['name'] ?? $platform['platform_name'] ?? 'Platform ' . $platform['platform_id']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold uppercase tracking-wider text-gray-400 mb-2">Nieuwe Cover Afbeelding (optioneel)</label>
                <div class="border-2 border-dashed border-gray-700 rounded-xl p-4 text-center bg-gray-900/50 hover:bg-gray-900 transition-colors cursor-pointer relative">
                    <input type="file" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <p class="text-sm text-gray-400">Klik om te wijzigen</p>
                </div>
            </div>

            <div class="pt-4 flex space-x-4">
                <a href="games.php" class="w-1/2 text-center bg-gray-700 hover:bg-gray-600 py-3.5 rounded-xl font-bold transition-colors">Annuleren</a>
                <button type="submit" class="w-1/2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 py-3.5 rounded-xl font-bold transition-all shadow-lg">Wijzigingen Opslaan</button>
            </div>
        </form>
    </div>
</body>
</html>