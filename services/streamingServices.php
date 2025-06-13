<?php
// <!-- Arquivo destinado para criação de funções e class para gerenciar o DB -->

require "../models/filme.php";
require "../models/serie.php";
require "../models/filmeAlugado.php";
require "../models/serieAlugado.php";
require "../models/user.php";
require "../config/connectDB.php";

class Streaming {

    // contruct, listar filmes, listar series, adicionar item, remover item, editar item, adicionar usuario, remover usuario, alugar item, devolver item

    private $db;
    private array $filmes = [];
    private array $series = [];
    private array $filmesAlugados = [];
    private array $seriesAlugados = [];

    public function __construct () {
        $this->db = getConnection();
        $this->carregarSeries();
        $this->carregarFilmes();  
        $this->carregarFilmesAlugados();  
        $this->carregarSeriesAlugados();  
    }

    public function carregarFilmes(): void{
        $stmt = $this->db->query("SELECT * FROM filme");
        $filmeDb = $stmt->fetchAll();

        $this->filmes = []; // Limpa a lista antes de carregar
        
        foreach ($filmeDb as $dado) {
            $filme = new Filme(
                    $dado['titulo'], 
                    $dado['imagem_path'], 
                    $dado['sinopse'], 
                    $dado['release_date'], 
                    $dado['generos'], 
                    $dado['duracao_minutos'], 
                    $dado['preco'], 
                    (bool)$dado['disponivel'],
                    $dado['id']
                );

            $this->filmes[] = $filme;
        }
    }

    public function carregarSeries(): void{
        $stmt = $this->db->query("SELECT * FROM serie");
        $serieDb = $stmt->fetchAll();
        
        $this->series = []; // Limpa a lista antes de carregar
        
        foreach ($serieDb as $dado) {
            $serie = new Serie(
                    $dado['titulo'], 
                    $dado['imagem_path'], 
                    $dado['sinopse'], 
                    $dado['release_date'], 
                    $dado['generos'], 
                    $dado['preco'], 
                    (bool)$dado['disponivel'],
                    $dado['id']
                );

            $this->series[] = $serie;
        }
    }

    public function carregarFilmesAlugados(): void{
        $stmt = $this->db->query("SELECT * FROM filme_alugados");
        $filmesAlugadosDb = $stmt->fetchAll();
        
        $this->filmesAlugados = []; // Limpa a lista antes de carregar
        
        foreach ($filmesAlugadosDb as $dado) {

            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$dado['usuario_id']]);
            $usuario = $stmt->fetch();

            $stmt = $this->db->prepare("SELECT * FROM filme WHERE id = ?");
            $stmt->execute([$dado['filme_id']]);
            $filme = $stmt->fetch();

            $filmeAlugado = new FilmeAlugado(
                    $dado['titulo'], 
                    $dado['imagem_path'], 
                    $dado['sinopse'], 
                    $dado['release_date'], 
                    $dado['generos'], 
                    $dado['duracao_minutos'],
                    $dado['preco'], 
                    (bool)$dado['disponivel'],
                    $dado['id'],
                    $dado['data_aluguel'],
                    $dado['expira_em'],
                    $dado['preco_pago'],
                    $dado['usuario_id']
                );

            $this->filmesAlugados[] = $filmeAlugado;
        }
    }

    public function carregarSeriesAlugados(): void{
        $stmt = $this->db->query("SELECT * FROM serie_alugados");
        $seriesAlugadosDb = $stmt->fetchAll();
        
        $this->seriesAlugados = []; // Limpa a lista antes de carregar
        
        foreach ($seriesAlugadosDb as $dado) {

            $stmt = $this->db->prepare("SELECT * FROM serie WHERE id = ?");
            $stmt->execute([$dado['serie_id']]);
            $serie = $stmt->fetch();

            $serieAlugado = new SerieAlugado(
                    $dado['titulo'], 
                    $dado['imagem_path'], 
                    $dado['sinopse'], 
                    $dado['release_date'], 
                    $dado['generos'], 
                    $dado['preco'], 
                    (bool)$dado['disponivel'],
                    $dado['id'],
                    $dado['data_aluguel'],
                    $dado['expira_em'],
                    $dado['preco_pago'],
                    $dado['usuario_id']
                );

            $this->seriesAlugados[] = $serieAlugado;
        }
    }
}

?>