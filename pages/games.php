<?php
require_once '../config/Database.php';
require_once '../repository/GameRepository.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    $repo = new GameRepository($conn);

    // Alle games ophalen uit de database
    $games = $repo->getAll();
} catch (Exception $e) {
    die("Fout bij het ophalen van gegevens: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Game Overzicht</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .header-container { display: flex; align-items: center; margin-bottom: 20px; }
        .btn { padding: 10px 15px; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-weight: bold; display: inline-block; }
        .btn-add { background-color: #28a745; }
        .btn-api { background-color: #007bff; margin-left: 10px; }
        .btn-edit { background-color: #ffc107; color: black; font-size: 12px; padding: 5px 10px; }
        .btn-delete { background-color: #dc3545; font-size: 12px; padding: 5px 10px; margin-left: 5px; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #343a40; color: white; }
        tr:hover { background-color: #f1f1f1; }
    </style>
</head>
<body>

    <h1>🎮 Mijn Game Vault</h1>

    <div class="header-container">
        <a href="create.php" class="btn btn-add">➕ Game Toevoegen</a>
        
        <a href="api.php" class="btn btn-api">🌐 Externe API</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titel</th>
                <th>Beschrijving</th>
                <th>Release Datum</th>
                <th>Rating</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($games)): ?>
                <?php foreach ($games as $game): ?>
                    <tr>
                        <td><?php echo $game['game_id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($game['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($game['description']); ?></td>
                        <td><?php echo $game['released_at']; ?></td>
                        <td>⭐ <?php echo $game['personal_rating']; ?>/10</td>
                        <td>
                            <a href="edit.php?id=<?php echo $game['game_id']; ?>" class="btn btn-edit">Bewerken</a>
                            <a href="delete.php?id=<?php echo $game['game_id']; ?>" class="btn btn-delete" onclick="return confirm('Weet je zeker dat je deze game wilt verwijderen?');">Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #888;">Nog geen games gevonden in de database.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>