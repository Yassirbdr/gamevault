<?php
$btc_price = "Nog niet opgevraagd";
$last_updated = "";

if (isset($_POST['fetch_btc'])) {
    $url = "https://api.coindesk.com/v1/bpi/currentprice.json";
    $response = @file_get_contents($url);
    
    if ($response) {
        $data = json_decode($response, true);
        $btc_price = "$" . number_format($data['bpi']['USD']['rate_float'], 2);
        $last_updated = date("H:i:s");
    } else {
        $btc_price = "API Verbindingsfout";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Live Market Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans min-h-screen flex items-center justify-center p-4">
    <div class="bg-gray-800 border border-gray-700 p-8 rounded-2xl shadow-2xl max-w-md w-full text-center">
        <div class="w-16 h-16 bg-purple-950/50 border border-purple-500 text-purple-400 text-3xl flex items-center justify-center rounded-full mx-auto mb-4 shadow-lg shadow-purple-500/10">🪙</div>
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400 uppercase tracking-wide mb-2">Live BTC Tracker</h1>
        <p class="text-gray-400 text-sm mb-6">Verifieer externe API koppelingen in real-time</p>
        
        <div class="bg-gray-900 border border-gray-750 p-6 rounded-xl mb-6 relative overflow-hidden">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-2">Actuele Waarde (USD)</span>
            <span class="text-4xl font-black text-green-400 tracking-tight block my-2"><?= $btc_price ?></span>
            <?php if (!empty($last_updated)): ?>
                <span class="text-[10px] font-mono text-gray-500 block mt-2">Laatste update om: <?= $last_updated ?></span>
            <?php endif; ?>
        </div>

        <form method="POST" class="space-y-4">
            <button type="submit" name="fetch_btc" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white py-3.5 rounded-xl font-bold transition-all shadow-lg shadow-purple-500/20">🔄 Haal Live Koers Op</button>
            <a href="games.php" class="block w-full bg-gray-700 hover:bg-gray-600 py-3.5 rounded-xl font-bold transition-colors">← Terug naar Dashboard</a>
        </form>
    </div>
</body>
</html>