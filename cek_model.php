<?php
// Masukkan API Key Anda di sini
$apiKey = 'AIzaSyBYe8hJXv13H978TeSorCukgY-pXRf8rKc'; 

$url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Karena ListModels menggunakan GET, kita tidak perlu set POST
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

echo "<h3>Daftar Model yang Tersedia untuk API Key Anda:</h3>";
echo "<pre>";
if (isset($data['models'])) {
    foreach ($data['models'] as $model) {
        // Hanya tampilkan model yang mendukung generateContent
        if (in_array('generateContent', $model['supportedGenerationMethods'] ?? [])) {
            echo "- " . $model['name'] . "\n";
        }
    }
} else {
    // Jika ada error lain, tampilkan error mentahnya
    print_r($data);
}
echo "</pre>";
?>