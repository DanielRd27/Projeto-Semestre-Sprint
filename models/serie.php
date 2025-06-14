<?php

require "../midia.php";

class Series extends Midia {

    protected string $tipo = "Serie";

    public function __construct(string $titulo, string $imagem_path, string $sinopse, string $release_date, string $generos, float $preco, bool $disponivel, int $id, int $duracao_minutos) {
        parent::__construct($titulo, $imagem_path, $sinopse, $release_date, $generos, $preco, $disponivel, $id);
        $this->duracao_minutos = $duracao_minutos;
    
    }

    public function getDuracaoMinutos(): int {
        return $this->duracao_minutos;
    }

}
?>