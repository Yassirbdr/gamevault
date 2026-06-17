<?php
require_once '../config/Database.php';
require_once '../repository/GameRepository.php';

if (isset($_GET['id'])) {
    $database = new Database();
    $gameRepo = new GameRepository($database);
    $gameRepo->deleteGame($_GET['id']);
}

header("Location: games.php");
exit();