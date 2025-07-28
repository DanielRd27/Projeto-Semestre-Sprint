<?php

require_once "../models/filmes.php";


class FilmeAlugado extends Filme {
    protected int $id_user;

    public function __construct(string $titulo, string $imagem_path, string $sinopse, int $release_date, string $generos, float $preco, bool $disponivel, int $id_user, string $tipo, ?int $id = null) {
        parent::__construct($titulo, $imagem_path, $sinopse, $release_date, $generos, $preco, $disponivel, $tipo ,$id);
        $this->id_user = $id_user;
    
    }

    public function getUserId(): int {
        return $this->id_user;
    }

}
?>