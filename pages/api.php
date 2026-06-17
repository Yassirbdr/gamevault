<?php
require_once '../config/Database.php';
require_once '../repository/GameRepository.php';

// 1. API URL uit de workshop (Haalt live Bitcoin prijs op)
$url = "https://api.binance.com/api/v3/ticker/price?symbol=BTCUSDT";

$message = "";
$prijs = "Onbekend";

try {
    // Data live ophalen van de API
    $json = @file_get_contents($url);
    if ($json === false) {
        throw new Exception("Kon geen live data ophalen van de API.");
    }

    // JSON omzetten naar een PHP array
    $data = json_decode($json, true);
    $prijs = round($data["price"], 2);
} catch (Exception $e) {
    $message = "<p style='color:red;'>❌ API Fout: " . $e->getMessage() . "</p>";
}

// 2. Als de gebruiker op de knop klikt om de data lokaal op te slaan
if (isset($_POST['save_to_db'])) {
    try {
        $db = new Database();
        $conn = $db->getConnection();
        $repo = new GameRepository($conn);

        // We slaan de API-data op in de games tabel
        $title = "Bitcoin Live Tracker";
        $description = "Automatisch gegenereerde data vanuit de Binance API. Actuele koerswaarde ten tijde van opslaan.";
        $released_at = date('Y-m-d');
        $personal_rating = 10; 

        if ($repo->create($title, $description, $released_at, $personal_rating)) {
            $message = "<p style='color:green;'>✅ API data succesvol lokaal opgeslagen in de database!</p>";
        } else {
            $message = "<p style='color:red;'>❌ Opslaan van API data mislukt.</p>";
        }
    } catch (Exception $e) {
        $message = "<p style='color:red;'>❌ Database Fout: " . $e->getMessage() . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>API Externe Koppeling</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .api-box { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-top: 20px; }
        .btn { padding: 10px 15px; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: bold; }
        .btn-save { background-color: #007bff; }
        .btn-back { background-color: #6c757d; margin-top: 20px; display: inline-block; }
        h2 { color: #28a745; }
    </style>
</head>
<body>

    <h1>🌐 Externe API Data Koppeling</h1>
    <p>Deze pagina haalt live gegevens op via een externe API-verbinding (REST/JSON).</p>

    <?php echo $message; ?>

    <div class="api-box">
        <h3>Live data van Binance API:</h3>
        <p>Actuele Bitcoin (BTC/USDT) Koers:</p>
        <h2>$ <?php echo $prijs; ?></h2>

        <form action="api.php" method="POST">
            <button type="submit" name="save_to_db" class="btn btn-save">📥 Sla API data lokaal op</button>
        </form>
    </div>

    <a href="games.php" class="btn btn-back">⬅ Terug naar Overzicht</a>

</body>
</html>