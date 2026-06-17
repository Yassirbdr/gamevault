<?php
require_once '../config/Database.php';
require_once '../repository/GameRepository.php';

$database = new Database();
$gameRepo = new GameRepository($database);
$games = $gameRepo->getAllGames();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>GameVault | Overzicht</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8 border-b border-gray-800 pb-4">
            <h1 class="text-4xl font-extrabold text-blue-500 tracking-wide">🎮 GameVault</h1>
            <div class="space-x-4">
                <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold transition shadow-lg">＋ Game Toevoegen</a>
                <a href="api.php" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-semibold transition shadow-lg">🪙 Live BTC Tracker</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($games as $game): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-2xl flex flex-col justify-between">
                    <div>
                        <?php if (!empty($game['image']) && file_exists('../uploads/' . $game['image'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($game['image']) ?>" class="w-full h-48 object-cover border-b border-gray-700" alt="Game cover">
                        <?php else: ?>
                            <div class="w-full h-48 bg-gray-700 flex items-center justify-center text-gray-400">Geen afbeelding</div>
                        <?php endif; ?>
                        
                        <div class="p-5">
                            <h2 class="text-2xl font-bold text-white mb-2"><?= htmlspecialchars($game['title']) ?></h2>
                            <span class="inline-block bg-blue-900 text-blue-300 text-xs px-2.5 py-1 rounded-full font-semibold uppercase tracking-wider mb-4">
                                <?= htmlspecialchars($game['genre_name'] ?? 'Onbekend') ?>
                            </span>
                            <div class="text-xl text-yellow-400 font-bold">⭐ <?= htmlspecialchars($game['rating']) ?> / 5</div>
                        </div>
                    </div>
                    
                    <div class="p-5 bg-gray-850 border-t border-gray-750 flex space-x-3">
                        <a href="edit.php?id=<?= $game['id'] ?>" class="w-1/2 text-center bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg font-medium transition">Bewerken</a>
                        <a href="delete.php?id=<?= $game['id'] ?>" onclick="return confirm('Weet je het zeker?')" class="w-1/2 text-center bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition">Verwijderen</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>