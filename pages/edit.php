<?php
require_once '../config/Database.php';
require_once '../repository/Game.php';

$database = new Database();
$pdo = $database->getConnection();

$gameObject = new Game($pdo);

$id = (int)($_GET['id'] ?? 0);
$game = $gameObject->getGameById($id);

if (!$game) {
    header("Location: games.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $rating = $_POST['personal_rating'] ?? '0.0';
    $genre_id = (int)($_POST['genre_id'] ?? 0);
    $platform_id = (int)($_POST['platform_id'] ?? 0);
    
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath);
    }

    if ($gameObject->updateGame($id, $title, $rating, $genre_id, $platform_id, $imageName)) {
        header("Location: games.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Game Bewerken</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans min-h-screen flex items-center justify-center p-6">
    <div class="bg-gray-800/50 border border-gray-800 p-8 rounded-2xl max-w-md w-full backdrop-blur-md">
        <h2 class="text-2xl font-black text-white mb-6 uppercase tracking-wider">✏️ Game Bewerken</h2>
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Titel</label>
                <input type="text" name="title" value="<?= htmlspecialchars($game['title']) ?>" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Beoordeling (0.0 - 5.0)</label>
                <input type="number" name="personal_rating" step="0.1" min="0" max="5" value="<?= htmlspecialchars($game['personal_rating']) ?>" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Genre</label>
                <select name="genre_id" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-blue-500 outline-none appearance-none cursor-pointer">
                    <option value="1" <?= $game['genre_id'] == 1 ? 'selected' : '' ?>>Action</option>
                    <option value="2" <?= $game['genre_id'] == 2 ? 'selected' : '' ?>>Adventure</option>
                    <option value="3" <?= $game['genre_id'] == 3 ? 'selected' : '' ?>>RPG</option>
                    <option value="4" <?= $game['genre_id'] == 4 ? 'selected' : '' ?>>Shooter</option>
                    <option value="5" <?= $game['genre_id'] == 5 ? 'selected' : '' ?>>Sports</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Platform</label>
                <select name="platform_id" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-blue-500 outline-none appearance-none cursor-pointer">
                    <option value="1" <?= $game['platform_id'] == 1 ? 'selected' : '' ?>>PC</option>
                    <option value="2" <?= $game['platform_id'] == 2 ? 'selected' : '' ?>>PlayStation 5</option>
                    <option value="3" <?= $game['platform_id'] == 3 ? 'selected' : '' ?>>Xbox Series X</option>
                    <option value="4" <?= $game['platform_id'] == 4 ? 'selected' : '' ?>>Nintendo Switch</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Nieuwe Cover Afbeelding (optioneel)</label>
                <input type="file" name="image" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-gray-400 focus:border-blue-500 outline-none">
            </div>
            <div class="flex space-x-3 pt-4">
                <a href="games.php" class="w-1/2 text-center bg-gray-900 border border-gray-700 hover:border-gray-500 py-3 rounded-xl font-bold text-sm transition-colors text-gray-300">Annuleren</a>
                <button type="submit" class="w-1/2 bg-blue-600 hover:bg-blue-500 py-3 rounded-xl font-bold text-sm transition-colors text-white shadow-lg shadow-blue-600/20">Bijwerken</button>
            </div>
        </form>
    </div>
</body>
</html>