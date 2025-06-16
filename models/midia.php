<?php

abstract class Midia {
    protected string $titulo;
    protected string $imagem_path;
    protected string $sinopse;
    protected string $release_date;
    protected string $tipo;
    protected string $generos;
    protected float $preco;
    protected bool $disponivel;
    protected ?int $id = null;
    protected bool $carrinho = false;
    
    public function __construct(string $titulo, string $imagem_path, string $sinopse, int $release_date, string $generos, float $preco, bool $disponivel, string $tipo, ?int $id = null) {
        $this->titulo = $titulo;
        $this->imagem_path = $imagem_path;
        $this->sinopse = $sinopse;
        $this->release_date = $release_date;
        $this->generos = $generos;
        $this->preco = $preco;
        $this->disponivel = $disponivel;
        $this->id = $id;
        $this->tipo = $tipo;
    }

    public function getCarrinho(): bool {
        return $this->carrinho;
    }

    public function carrinhoOn(): void {
        $this->carrinho = True;
    }

    public function carrinhoOff(): void {
        $this->carrinho = False;
    }

    public function calcularAluguel(int $dias): float {
        return $this->preco * $dias;
    }

    public function getTipo(): string {
        return $this->tipo;
    }

    public function getTitulo(): string {
        return $this->titulo;
    }

    public function getImagemPath(): string {
        return $this->imagem_path;
    }

    public function getSinopse(): string {
        return $this->sinopse;
    }

    public function getEncurtaSinopse() {
        $sinopseResult = mb_strimwidth($this->sinopse, 0, 255, '...');
        return $sinopseResult;
    }

    public function getReleaseDate(): int {
        return $this->release_date;
    }

    public function getGeneros(): string {
        return $this->generos;
    }


    public function getPreco(): float {
        return $this->preco;
    }

    public function setPreco($newPreco): void {
        $this->preco = $newPreco;
    }

    public function isDisponivel(): bool {
        return $this->disponivel;
    }

    public function getId() {
        return $this->id;
    }

    public function setDisponivel(bool $disponivel): void {
        $this->disponivel = $disponivel;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }
}
?>