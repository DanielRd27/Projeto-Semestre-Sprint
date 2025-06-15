<?php

require_once "../models/midia.php";

class Serie extends Midia {

    protected string $tipo = "Serie";
    protected array $temporadas_Episodios = [];

    public function __construct(string $titulo, string $imagem_path, string $sinopse, int $release_date, string $generos, float $preco, bool $disponivel, array $temporadas_Episodios, ?int $id = null) {
        parent::__construct($titulo, $imagem_path, $sinopse, $release_date, $generos, $preco, $disponivel, $id);
        $this->temporadas_Episodios = $temporadas_Episodios;
    
    }

    public function getTemporadasEpisodios(): array {
        return $this->temporadas_Episodios;
    }
}
?>