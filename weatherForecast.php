<?php

if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(sprintf('%s=%s', trim($name), trim($value)));
    }
}

$apiKey = getenv('OPENWEATHER_API_KEY');

if (!$apiKey) {
    die("Error: OPENWEATHER_API_KEY belum diatur di variable environment\n");
}

$city = "Jakarta";
$url = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&appid={$apiKey}&units=metric";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

$data = json_decode($response, true);

if (!isset($data['list'])) {
    die("Error: Tidak dapat mengambil data cuaca\n" . ($data['message'] ?? 'Unknown error') . "\n");
}

$dailyTemps = [];

foreach ($data['list'] as $entry) {
    $date = date('D, d M Y', $entry['dt']);
    $temp = $entry['main']['temp'];

    if (!isset($dailyTemps[$date])) {
        $dailyTemps[$date] = [];
    }
    $dailyTemps[$date][] = $temp;
}

echo "Weather Forecast:\n";

$count = 0;
foreach ($dailyTemps as $date => $temps) {
    if ($count >= 5)
        break;

    $avgTemp = array_sum($temps) / count($temps);

    $formattedTemp = number_format($avgTemp, 2, '.', '');

    echo "{$date}: {$formattedTemp}°C\n";
    $count++;
}