<?php
require_once '../config/Database.php';
require_once '../repository/Game.php'; // Verwijst naar je nieuwe bestand!

$database = new Database();
$pdo = $database->getConnection(); // Haalt de pure PDO-connectie op

$gameObject = new Game($pdo); // Maakt het object aan volgens de schoolstijl

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // Aanroep via het nieuwe $gameObject!
    $gameObject->deleteGame($id);
}

// Direct weer terug naar het dashboard na het wissen
header("Location: games.php");
exit();
?>