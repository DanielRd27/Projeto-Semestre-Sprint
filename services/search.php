<!-- Pesquisa de filme e coleta dados com a API - TMDB -->

<?php

$apiKey = '72872d046ca2baa1e585a796cd99ccda';
$termo = 'Matrix'; // isso viria de um $_GET ou $_POST

$url = "https://api.themoviedb.org/3/search/multi?api_key=$apiKey&language=pt-BR&query=" . urlencode($termo);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true
]);

$response = curl_exec($curl);
curl_close($curl);

$resultados = json_decode($response, true);

foreach ($resultados['results'] as $item) {
    $tipo = $item['media_type']; // movie ou tv
    $titulo = $item['title'] ?? $item['name'];
    $id = $item['id'];

    echo "$tipo: $titulo (ID: $id)<br>";
}

