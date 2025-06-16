<?php

require_once "../models/midia.php";


class Filme extends Midia {
    protected int $duracao_minutos;
    protected string $tipo = "Filme";

    public function __construct(string $titulo, string $imagem_path, string $sinopse, int $release_date, string $generos, float $preco, bool $disponivel, int $duracao_minutos, string $tipo, ?int $id = null) {
        parent::__construct($titulo, $imagem_path, $sinopse, $release_date, $generos, $preco, $disponivel, $tipo ,$id);
        $this->duracao_minutos = $duracao_minutos;
    
    }

    public function getDuracaoMinutos(): int {
        return $this->duracao_minutos;
    }

    public function teste(): int {
        return 2;
    }

}
?>