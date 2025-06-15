<?php

require "../config/connectDB.php";

function carregarSeries(): void{
        $stmt = $this->db->query("SELECT * FROM serie");
        $serieDb = $stmt->fetchAll();
        
        $this->series = []; // Limpa a lista antes de carregar
        
        foreach ($serieDb as $dado) {
            $temporadas_Episodios = []; // reinicia o array para cada série

            $stmt = $this->db->query("SELECT * FROM temporadas WHERE serie_id = ?");
            $stmt->execute([$dado['id']]);
            $temporadas = $stmt->fetchAll();

            foreach ($temporadas as $temporada) {

                $stmt = $this->db->prepare("SELECT * FROM episodios WHERE temporada_id = ?");
                $stmt->execute([$temporada['id']]);
                $episodios = $stmt->fetchAll();

                $temporadas_Episodios[$temporada] = $episodios;
            }

            $serie = new Serie(

                    $dado['titulo'], 
                    $dado['imagem_path'], 
                    $dado['sinopse'], 
                    $dado['release_date'], 
                    $dado['generos'], 
                    $dado['preco'], 
                    (bool)$dado['disponivel'],
                    $dado['id'],
                    $temporadas_Episodios
                );

            $this->series[] = $serie;
        }
}