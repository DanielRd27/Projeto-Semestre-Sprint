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

        // $url = "https://api.themoviedb.org/3/search/tv?api_key=$apiKey&language=pt-BR&query=$query";
    
        // $response = file_get_contents($url);
        // $data = json_decode($response, true);

        // if (isset($data['results'])) {
        //     foreach ($data['results'] as $serie) {
        //         $resultados[] = [
        //         'id' => $serie['id'],
        //         'title' => $serie['name'],
        //         'overview' => $serie['overview']
        //     ];
        //     }
        // }

        return $resultados;
    }

    public function criarMidia($id, $titulo) {
        $apiKey = '72872d046ca2baa1e585a796cd99ccda';
        $tipo = ''; // Para sabermos se é filme ou série

        // Tentar buscar como filme
        $filmeUrl = "https://api.themoviedb.org/3/movie/{$id}?api_key={$apiKey}&language=pt-BR";
        $filmeJson = @file_get_contents($filmeUrl);

        if ($filmeJson !== false) {
            $filme = json_decode($filmeJson, true);
            if ($filme['title'] == $titulo) {
                $tipo = 'Filme';
            } else {
                $serieUrl = "https://api.themoviedb.org/3/tv/{$id}?api_key={$apiKey}&language=pt-BR";
                $serieJson = @file_get_contents($serieUrl);
                
                if ($serieJson !== false) {
                    $serie = json_decode($serieJson, true);
                    if ($serie['name'] == $titulo) {

                        $tipo = 'Serie';
                    } else {
                        return "ID não encontrado.\n";
                    }
                } 
            }
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
            $sinopse = $data['overview'] ?? 'Sinopse não disponivel';
            $duracao = $data['runtime'] ?? 0;
            $anoLancamento = !empty($data['release_date']) ? substr($data['release_date'], 0, 4) : 0;
            $generos = array_map(function($g) {
                return $g['name'];
            }, $data['genres']) ?? 'Generos indisponivels';

            $generosString = !empty($generos) ? implode(', ', $generos) : 'Gêneros indisponíveis';
            $posterPath = $data['poster_path'];

            if ($posterPath) {
                // 3. URL completa do pôster (tamanhos disponíveis: w200, w500, original, etc)
                $posterUrl = "https://image.tmdb.org/t/p/w500{$posterPath}";

                // 4. Baixar o pôster
                $posterContent = file_get_contents($posterUrl);
                file_put_contents("posters/poster_{$id}.jpg", $posterContent);

                $posterCaminho = "posters/poster_{$id}.jpg";
            } else {
                $posterCaminho = 'Poster Indisponível';
            }



            // Novo objeto filme
            $newFilme = new Filme($titulo, $posterCaminho, $sinopse, $anoLancamento, $generosString, 19.99, true, $duracao, 'Tipo');

            return $newFilme;

        } elseif ($tipo == 'Serie') {

            $apiUrl = "https://api.themoviedb.org/3/tv/{$id}?api_key={$apiKey}&language=pt-BR";

            // 1. Buscar dados do filme
            $response = file_get_contents($apiUrl);
            if ($response === FALSE) {
                die("Erro ao buscar dados do TMDB");
            }
            $data = json_decode($response, true);

            // 2. Extrair informações desejadas
            $titulo = $data['name'];
            $sinopse = $data['overview'] ?? 'Sinopse não disponivel';
            $anoLancamento = !empty($data['first_air_date']) ? substr($data['first_air_date'], 0, 4) : 0;
            $generos = array_map(function($g) {
                return $g['name'];
            }, $data['genres']) ?? 'Generos indisponivels';

            $generosString = !empty($generos) ? implode(', ', $generos) : 'Gêneros indisponíveis';
            $posterPath = $data['poster_path'];

            if ($posterPath) {
                // 3. URL completa do pôster (tamanhos disponíveis: w200, w500, original, etc)
                $posterUrl = "https://image.tmdb.org/t/p/w500{$posterPath}";

                // 4. Baixar o pôster
                $posterContent = file_get_contents($posterUrl);
                file_put_contents("posters/poster_{$id}.jpg", $posterContent);

                $posterCaminho = "posters/poster_{$id}.jpg";
            } else {
                $posterCaminho = 'Poster Indisponível';
            }

            // Temporadas
            $temporadasEpisodios = [];
            foreach ($data['seasons'] as $temporada) {
                $temporadaNumber = $temporada['season_number'];
                $temporadaName = $temporada['name'];

                // Buscar episodios da temporada

                $temporadaUrl = "https://api.themoviedb.org/3/tv/{$id}/season/{$temporadaNumber}?api_key={$apiKey}&language=pt-BR";
                $temporadaData = json_decode(file_get_contents($temporadaUrl), true);

                $episodios = [];
                foreach ($temporadaData['episodes'] as $episodio) {
                    $episodios[] = [
                        'temp_id' => 0,
                        'serie_id' => 0,
                        'numero' => $episodio['episode_number'],
                        'titulo' => $episodio['name']
                    ];
                }

                $temporadasEpisodios[] = [
                    'id' => 0,
                    'numero' => $temporadaNumber,
                    'episodios' => $episodios
                ];
            }

            // Novo objeto serie
            $newSerie = new Serie($titulo, $posterCaminho, $sinopse, $anoLancamento, $generosString, 19.99, true, $temporadasEpisodios,'Tipo');

            return $newSerie;
        } 
    }
}

?>