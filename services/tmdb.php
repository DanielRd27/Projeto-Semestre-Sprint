<?php

class Tmdb {

    protected $apiKey = '72872d046ca2baa1e585a796cd99ccda';
    

    public function pesquisar($query) {
        $apiKey = '72872d046ca2baa1e585a796cd99ccda';

        $resultados = [];

        $url = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&language=pt-BR&query=$query";
    
        $response = file_get_contents($url);
        $data = json_decode($response, true);
    
        if (isset($data['results'])) {
            foreach ($data['results'] as $filme) {
                $resultados[] = [
                'id' => $filme['id'],
                'title' => $filme['title'],
                'overview' => $filme['overview']
            ];
            }
        }

        return $resultados;
    }

    public function criarMidia($id) {
        $apiKey = '72872d046ca2baa1e585a796cd99ccda';
        $tipo = ''; // Para sabermos se é filme ou série

        // Tentar buscar como filme
        $filmeUrl = "https://api.themoviedb.org/3/movie/{$id}?api_key={$apiKey}&language=pt-BR";
        $filmeJson = @file_get_contents($filmeUrl);

        if ($filmeJson !== false) {
            $filme = json_decode($filmeJson, true);
            $tipo = 'Filme';
        } else {
            // Tentar buscar como série
            $serieUrl = "https://api.themoviedb.org/3/tv/{$id}?api_key={$apiKey}&language=pt-BR";
            $serieJson = @file_get_contents($serieUrl);
            
            if ($serieJson !== false) {
                $serie = json_decode($serieJson, true);
                $tipo = 'Serie';
            } else {
                return "ID não encontrado.\n";
            }
        }

        // Criar objeto Filme
        if ($tipo == 'Filme') {
            
            $apiUrl = "https://api.themoviedb.org/3/movie/{$id}?api_key={$apiKey}&language=pt-BR";

            // 1. Buscar dados do filme
            $response = file_get_contents($apiUrl);
            if ($response === FALSE) {
                die("Erro ao buscar dados do TMDB");
            }
            $data = json_decode($response, true);

            // 2. Extrair informações desejadas
            $titulo = $data['title'];
            $sinopse = $data['overview'];
            $duracao = $data['runtime'];
            $anoLancamento = substr($data['release_date'], 0, 4);
            $generos = array_map(function($g) {
                return $g['name'];
            }, $data['genres']);

            $generosString = implode(', ', $generos);
            $posterPath = $data['poster_path'];

            // 3. URL completa do pôster (tamanhos disponíveis: w200, w500, original, etc)
            $posterUrl = "https://image.tmdb.org/t/p/w500{$posterPath}";

            // 4. Baixar o pôster
            $posterContent = file_get_contents($posterUrl);
            file_put_contents("posters/poster_{$id}.jpg", $posterContent);

            // Novo objeto filme
            $newFilme = new Filme($titulo, "posters/poster_{$id}.jpg", $sinopse, $anoLancamento, $generosString, 19.99, true, $duracao,);

            return $newFilme;

            
        } elseif ($tipo == 'Serie') {

        } 
    }
}

?>