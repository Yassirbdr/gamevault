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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameVault | Premium Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans min-h-screen selection:bg-blue-500 selection:text-white">
    <div class="container mx-auto px-6 py-10">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 border-b border-gray-800 pb-6 gap-4">
            <div>
                <h1 class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500 tracking-wider uppercase">🎮 GameVault</h1>
                <p class="text-gray-400 text-sm mt-1">Beheer je persoonlijke gaming collectie en live statistieken</p>
            </div>
            <div class="flex space-x-4">
                <a href="create.php" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5">＋ Game Toevoegen</a>
                <a href="api.php" class="bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-500 hover:to-indigo-600 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg hover:shadow-purple-500/20 hover:-translate-y-0.5">🪙 Live BTC Tracker</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($games)): ?>
                <div class="col-span-full bg-gray-800 border border-gray-700 rounded-2xl p-12 text-center">
                    <p class="text-gray-400 text-lg">Er zijn nog geen games toegevoegd aan je Vault.</p>
                    <a href="create.php" class="inline-block mt-4 text-blue-400 hover:underline font-semibold">Voeg nu je eerste game toe →</a>
                </div>
            <?php else: ?>
                <?php foreach ($games as $game): ?>
                    <div class="bg-gray-800 border border-gray-750 rounded-2xl overflow-hidden shadow-2xl flex flex-col justify-between transition-all duration-300 hover:border-gray-600 hover:shadow-indigo-500/5 group">
                        <div>
                            <?php if (!empty($game['image']) && file_exists('../uploads/' . $game['image'])): ?>
                                <div class="overflow-hidden h-52 border-b border-gray-700">
                                    <img src="../uploads/<?= htmlspecialchars($game['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Game cover">
                                </div>
                            <?php else: ?>
                                <div class="w-full h-52 bg-gradient-to-br from-gray-700 to-gray-800 flex flex-col items-center justify-center text-gray-400 border-b border-gray-700">
                                    <span class="text-4xl mb-2">📸</span>
                                    <span class="text-xs uppercase tracking-widest font-semibold text-gray-500">Geen afbeelding</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <span class="inline-block bg-blue-950/60 border border-blue-800 text-blue-400 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-widest mb-3">
                                    <?= htmlspecialchars($game['genre_name'] ?? 'Algemeen') ?>
                                </span>
                                <h2 class="text-2xl font-extrabold text-white tracking-tight leading-tight mb-2 group-hover:text-blue-400 transition-colors"><?= htmlspecialchars($game['title']) ?></h2>
                                <div class="flex items-center space-x-1 text-xl text-yellow-400 font-black mt-3">
                                    <span>⭐</span>
                                    <span><?= htmlspecialchars($game['rating']) ?></span>
                                    <span class="text-gray-500 text-sm font-normal">/ 5</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 bg-gray-850 border-t border-gray-750 flex space-x-4">
                            <a href="edit.php?id=<?= $game['id'] ?>" class="w-1/2 text-center bg-gray-700 hover:bg-gray-600 text-white py-3 rounded-xl font-bold transition-colors">Bewerken</a>
                            <a href="delete.php?id=<?= $game['id'] ?>" onclick="return confirm('Weet je zeker dat je deze game wilt verwijderen uit je Vault?')" class="w-1/2 text-center bg-red-600/20 hover:bg-red-600 border border-red-500/30 text-red-400 hover:text-white py-3 rounded-xl font-bold transition-all">Verwijderen</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>