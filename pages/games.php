<?php
require_once '../config/Database.php';
require_once '../repository/Game.php'; // Verwijst nu naar je nieuwe bestand!

$database = new Database();
$pdo = $database->getConnection(); // Haalt de pure PDO-connectie op

$gameObject = new Game($pdo); // Maakt het object aan volgens de schoolstijl
$games = $gameObject->getAllGames();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Game Vault | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans min-h-screen">

    <header class="bg-gray-800/50 border-b border-gray-800 backdrop-blur-md sticky top-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500 tracking-wider uppercase">🎮 GameVault</h1>
                <p class="text-xs text-gray-400 mt-0.5">Beheer je persoonlijke gaming collectie en live statistieken</p>
            </div>
            <div class="flex space-x-4">
                <a href="create.php" class="bg-blue-600 hover:bg-blue-500 font-bold px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-600/20 flex items-center gap-2">
                    <span>+</span> Game Toevoegen
                </a>
                <a href="api.php" class="bg-purple-600 hover:bg-purple-500 font-bold px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-purple-600/20 flex items-center gap-2">
                    🪙 Live BTC Tracker
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <?php if (empty($games)): ?>
            <div class="bg-gray-800 border border-gray-800 rounded-2xl p-12 text-center max-w-xl mx-auto">
                <p class="text-gray-400 text-lg mb-4">Je Vault is momenteel nog leeg. Voeg snel je eerste game toe!</p>
                <a href="create.php" class="inline-block bg-blue-600 hover:bg-blue-500 font-bold px-6 py-3 rounded-xl transition-colors">Start Je Collectie</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($games as $game): ?>
                    <div class="bg-gray-800/40 border border-gray-800 rounded-2xl overflow-hidden hover:border-gray-700 transition-all group flex flex-col justify-between">
                        
                        <div class="aspect-[16/10] w-full bg-gray-900 flex items-center justify-center border-b border-gray-800 relative overflow-hidden">
                            <?php if (!empty($game['image']) && file_exists('../uploads/' . $game['image'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($game['image']) ?>" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php else: ?>
                                <div class="text-center p-6 text-gray-600">
                                    <span class="text-3xl block mb-2">📸</span>
                                    <span class="text-xs font-bold uppercase tracking-wider">Geen Afbeelding</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                <span class="bg-blue-950/80 border border-blue-500/50 text-blue-400 text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md backdrop-blur-sm">
                                    <?= htmlspecialchars($game['genre_name'] ?? 'Onbekend') ?>
                                </span>
                                <?php if (!empty($game['platform_name'])): ?>
                                <span class="bg-purple-950/80 border border-purple-500/50 text-purple-400 text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md backdrop-blur-sm">
                                    <?= htmlspecialchars($game['platform_name']) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-extrabold text-white mb-4 tracking-tight group-hover:text-blue-400 transition-colors">
                                    <?= htmlspecialchars($game['title']) ?>
                                </h3>
                                
                                <div class="flex items-center space-x-2 bg-gray-900/50 border border-gray-800 w-fit px-3 py-1.5 rounded-xl mb-6">
                                    <span class="text-yellow-500 text-lg">⭐</span>
                                    <span class="text-sm font-black text-gray-200">
                                        <?= htmlspecialchars($game['personal_rating'] ?? '0.0') ?>
                                    </span>
                                    <span class="text-xs text-gray-500 font-bold">/ 5.0</span>
                                </div>
                            </div>

                            <div class="flex space-x-3 border-t border-gray-800/80 pt-4">
                                <a href="edit.php?id=<?= $game['game_id'] ?>" class="w-1/2 text-center bg-gray-900 border border-gray-700 hover:border-gray-500 py-2.5 rounded-xl font-bold text-sm transition-colors text-gray-300 hover:text-white">
                                    ✏️ Bewerken
                                </a>
                                <a href="delete.php?id=<?= $game['game_id'] ?>" onclick="return confirm('Weet je zeker dat je deze game wilt verwijderen?')" class="w-1/2 text-center bg-red-950/20 border border-red-900/50 hover:bg-red-900/40 py-2.5 rounded-xl font-bold text-sm transition-colors text-red-400">
                                    🗑️ Wissen
                                </a>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>