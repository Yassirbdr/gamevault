<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$btc_price = "Nog niet opgevraagd";
$last_updated = "";

if (isset($_POST['fetch_btc'])) {
    $url = "https://api.coinbase.com/v2/prices/BTC-USD/spot";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'); 
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $btc_price = "$64,250.00 (Simulatie)";
        $last_updated = date("H:i:s") . " (Offline Modus)";
    } else {
        if ($response) {
            $data = json_decode($response, true);
            
            if (isset($data['data']['amount'])) {
                $btc_price = "$" . number_format((float)$data['data']['amount'], 2, '.', ',');
                $last_updated = date("H:i:s");
            } else {
                $btc_price = "Structuur matcht niet";
            }
        } else {
            $btc_price = "Geen respons van API";
        }
    }
    curl_close($ch);
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
        <div class="w-16 h-16 bg-purple-950/50 border border-purple-500 text-purple-400 text-3xl flex items-center justify-center rounded-full mx-auto mb-4 shadow-lg">🪙</div>
        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400 uppercase tracking-wide mb-2">Live BTC Tracker</h1>
        <p class="text-gray-400 text-sm mb-6">Verifieer externe API koppelingen in real-time</p>
        
        <div class="bg-gray-900 border border-gray-750 p-6 rounded-xl mb-6">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-2">Actuele Waarde (USD)</span>
            <span class="text-4xl font-black text-green-400 tracking-tight block my-2"><?= $btc_price ?></span>
            <?php if (!empty($last_updated)): ?>
                <span class="text-mono text-gray-500 block mt-2 text-xs">Status: <?= $last_updated ?></span>
            <?php endif; ?>
        </div>

        <form method="POST" class="space-y-4">
            <button type="submit" name="fetch_btc" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white py-3.5 rounded-xl font-bold transition-all shadow-lg">🔄 Haal Live Koers Op</button>
            <a href="games.php" class="block w-full bg-gray-700 hover:bg-gray-600 py-3.5 rounded-xl font-bold transition-colors">← Terug naar Dashboard</a>
        </form>
    </div>
</body>
</html>