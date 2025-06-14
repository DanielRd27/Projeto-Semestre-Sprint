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
                    $dado['preco'], 
                    (bool)$dado['disponivel'],
                    $dado['id'],
                    $dado['duracao_minutos']
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

            $stmt = $this->db->prepare("SELECT * FROM filme WHERE id = ?");
            $stmt->execute([$dado['filme_id']]);
            $filme = $stmt->fetch();

            $filmeAlugado = new FilmeAlugado(
                    $filme['titulo'], 
                    $filme['imagem_path'], 
                    $filme['sinopse'], 
                    $filme['release_date'], 
                    $filme['generos'], 
                    $filme['preco'], 
                    (bool)$filme['disponivel'],
                    $filme['id'], // ID DO FILME
                    $filme['duracao_minutos'],
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
                    $serie['titulo'], 
                    $serie['imagem_path'], 
                    $serie['sinopse'], 
                    $serie['release_date'], 
                    $serie['generos'], 
                    $serie['preco'], 
                    (bool)$serie['disponivel'],
                    $serie['id'], // ID DO SERIE
                    $dado['data_aluguel'],
                    $dado['expira_em'],
                    $dado['preco_pago'],
                    $dado['usuario_id']
                );

            $this->seriesAlugados[] = $serieAlugado;
        }
    }

    // Insert

    public function adicionarMidia(Midia $midia): bool {
        $tipo = ($midia instanceof Filme) ? 'Filme' : 'Serie';
        
        try {
            if ($tipo == 'Filme') {
                $stmt = $this->db->prepare("
                INSERT INTO filme (titulo, imagem_path, sinopse, release_date, generos, duracao_minutos, preco, disponivel) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $result = $stmt->execute([
                    $midia->getTitulo(),
                    $midia->getImagemPath(),
                    $midia->getSinopse(),
                    $midia->getReleaseDate(),
                    $midia->getGeneros(),
                    $midia->getDuracaoMinutos(),
                    $midia->getPreco(),
                    $midia->isDisponivel(),
                ]);
                
                if ($result) {
                    // Define o ID gerado no objeto
                    $midia->setId($this->db->lastInsertId());
                    $this->filmes[] = $midia;
                }
                
                return $result;
            } elseif ($tipo == 'Serie') {
                $stmt = $this->db->prepare("
                INSERT INTO serie (titulo, imagem_path, sinopse, release_date, generos, preco, disponivel) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                
                $result = $stmt->execute([
                    $midia->getTitulo(),
                    $midia->getImagemPath(),
                    $midia->getSinopse(),
                    $midia->getReleaseDate(),
                    $midia->getGeneros(),
                    $midia->getPreco(),
                    $midia->isDisponivel(),
                ]);
                
                if ($result) {
                    // Define o ID gerado no objeto
                    $midia->setId($this->db->lastInsertId());
                    $this->series[] = $midia;
                }
                
                return $result;
            } else {
                return false;
            }

        } catch (\PDOException $e) {
            // Em caso de erro, como placa duplicada
            return false;
        }
    }

}

?>