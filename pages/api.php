<?php
$btc_price = "Nog niet opgehaald";

if (isset($_POST['fetch_btc'])) {
    $url = "https://api.coindesk.com/v1/bpi/currentprice.json";
    $response = @file_get_contents($url);
    
    if ($response) {
        $data = json_decode($response, true);
        $btc_price = "$" . number_format($data['bpi']['USD']['rate_float'], 2);
    } else {
        $btc_price = "Fout bij ophalen";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Live Crypto Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 border border-gray-700 p-8 rounded-xl shadow-2xl max-w-md w-full text-center">
        <h1 class="text-3xl font-bold text-purple-500 mb-4">🪙 Bitcoin Tracker</h1>
        <p class="text-gray-400 text-sm mb-6">Haal de meest actuele live BTC koers op via de externe CoinDesk API.</p>
        
        <div class="bg-gray-900 border border-gray-750 p-6 rounded-lg mb-6">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-widest block mb-1">Huidige Waarde (USD)</span>
            <span class="text-3xl font-black text-green-400 tracking-tight"><?= $btc_price ?></span>
        </div>

        <form method="POST" class="space-y-4">
            <button type="submit" name="fetch_btc" class="w-full bg-purple-600 hover:bg-purple-700 py-3 rounded-lg font-bold transition shadow-lg">🔄 Haal Live Koers Op</button>
            <a href="games.php" class="block w-full bg-gray-700 hover:bg-gray-600 py-3 rounded-lg font-semibold transition">← Terug naar Games</a>
        </form>
    </div>
</body>
</html>