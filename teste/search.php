<?php
$apiKey = '72872d046ca2baa1e585a796cd99ccda';
$idioma = 'pt-BR';
$totalPages = 5; // Limite para evitar sobrecarga
$titulos = [];

for ($page = 1; $page <= $totalPages; $page++) {
    $url = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=$idioma&page=$page";
    $json = file_get_contents($url);
    $data = json_decode($json, true);

    foreach ($data['results'] as $filme) {
        $titulos[] = $filme['title'];
    }
}

// Adiciona também séries populares
for ($page = 1; $page <= $totalPages; $page++) {
    $url = "https://api.themoviedb.org/3/tv/popular?api_key=$apiKey&language=$idioma&page=$page";
    $json = file_get_contents($url);
    $data = json_decode($json, true);

    foreach ($data['results'] as $serie) {
        $titulos[] = $serie['name'];
    }
}


file_put_contents('filmes.json', json_encode($titulos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
echo "Arquivo filmes.json gerado com " . count($titulos) . " títulos.\n";
