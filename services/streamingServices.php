<?php
// <!-- Arquivo destinado para criação de funções e class para gerenciar o DB -->

require "../models/filme.php";
require "../models/serie.php";
// require "../models/filmeAlugado.php";
// require "../models/serieAlugado.php";
require "../models/user.php";
require "../config/connectDB.php";

ini_set('log_errors', 1);
ini_set('error_log', '/php-error.log');
error_reporting(E_ALL);

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
                    $dado['duracao_minutos'],
                    'Filme',
                    $dado['id'],
                );

            $this->filmes[] = $filme;
        }
    }

    public function getFilmes(): array {
        return $this->filmes;
    }

    public function carregarSeries(): void{
        $stmt = $this->db->query("SELECT * FROM serie");
        $serieDb = $stmt->fetchAll();
        
        $this->series = []; // Limpa a lista antes de carregar
        
        foreach ($serieDb as $dado) {
            $temporadas_Episodios = []; // reinicia o array para cada série

            $stmt = $this->db->query("SELECT * FROM temporada WHERE serie_id = ?");
            $stmt->execute([$dado['id']]);
            $temporadas = $stmt->fetchAll();

            foreach ($temporadas as $temporada) {

                $stmt = $this->db->prepare("SELECT * FROM episodio WHERE temporada_id = ?");
                $stmt->execute([$temporada['id']]);
                $episodios = $stmt->fetchAll();

                $temporadas_Episodios[] = [
                    "temporada" => $temporada['number'], // ou 'numero', como quiser
                    "episodios" => $episodios
                ];
            }

     
            $serie = new Serie(

                    $dado['titulo'], 
                    $dado['imagem_path'], 
                    $dado['sinopse'], 
                    $dado['release_date'], 
                    $dado['generos'], 
                    $dado['preco'], 
                    (bool)$dado['disponivel'],
                    $temporadas_Episodios,
                    'Serie',
                    $dado['id'],
                );

            $this->series[] = $serie;
        }
    }

    public function getSeries(): array {
        return $this->series;
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

    public function getFilmesAlugados(): array {
        return $this->filmesAlugados;
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
                    $serie['id'], // ID DO SERIE
                    $dado['data_aluguel'],
                    $dado['expira_em'],
                    $dado['preco_pago'],
                    $dado['usuario_id']
                );

            $this->seriesAlugados[] = $serieAlugado;
        }
    }

    public function getSeriesAlugados(): array {
        return $this->seriesAlugados;
    }

    // Insert

    public function adicionarMidia(Midia $midia): bool {
        $tipo = ($midia instanceof Filme) ? 'Filme' : 'Serie';
        $serieId = null;
        
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
                
                $resultSerie = $stmt->execute([
                    $midia->getTitulo(),
                    $midia->getImagemPath(),
                    $midia->getSinopse(),
                    $midia->getReleaseDate(),
                    $midia->getGeneros(),
                    $midia->getPreco(),
                    $midia->isDisponivel(),
                ]);

                if (!$resultSerie) return false;

                $serieId = $this->db->lastInsertId();

                $temporadas_Episodios = $midia->getTemporadasEpisodios();

                foreach ($temporadas_Episodios as $temporada) {
                    $stmt = $this->db->prepare("
                    INSERT INTO temporada (serie_id, number) 
                    VALUES (?, ?)
                    ");

                    $resultTemporada = $stmt->execute([
                        $serieId,
                        $temporada['numero']
                    ]);

                    $temporadaId = $this->db->lastInsertId();

                    foreach ($temporada['episodios'] as $ep) {
                        $stmt = $this->db->prepare("
                        INSERT INTO episodio (titulo, temporada_id	) 
                        VALUES (?, ?)
                        ");

                        $resultEpisodio = $stmt->execute([
                            $ep['titulo'],
                            $temporadaId
                        ]);
                    }   
                }
                
                if ($resultSerie) {
                    // Define o ID gerado no objeto
                    $midia->setId($serieId);
                    $this->series[] = $midia;
                }
                
                return $result;
            } else {
                return false;
            }

        } catch (\PDOException $e) {
            error_log("Erro ao inserir mídia: " . $e->getMessage());
            return false;
        }
    }

    public function deletarVeiculo(string $tipo, int $id): string {
        if ($tipo == 'Filme') {
            foreach ($this->filmes as $filme) {
                if ($filme->getId() == $id) {
                    unset($this->filmes[$filme]);
                    $this->filmes = array_values($this->filmes);
                }
            }

            if ($id !== null) {
                $stmt = $this->db->prepare("DELETE FROM filme WHERE id = ?");
                if ($stmt->execute([$id])) {
                    return "Filmes '{$modelo}' removido com sucesso!";
                }
            }

        } elseif ($tipo == 'Serie'){
            foreach ($this->series as $serie) {
                if ($serie->getId() == $id) {
                    unset($this->series[$serie]);
                    $this->series = array_values($this->series);
                }
            }

            if ($id !== null) {
                // Deletar serie
                $stmt = $this->db->prepare("DELETE FROM serie WHERE id = ?");
                if ($stmt->execute([$id])) {
                    // Deletar temporadas
                    $stmt = $this->db->prepare("DELETE FROM serie WHERE id = ?");
                    if ($stmt->execute([$id])){
                        // Deletar Episodios
                        $stmt = $this->db->prepare("DELETE FROM serie WHERE id = ?");
                        $stmt->execute([$id]);
                        if ($stmt->execute([$id])) {
                            return "series '{$modelo}' removido com sucesso!";
                        }
                    }
                }
                
            }
        } else {
            return "Midia não encontrada.";
        }


        foreach ($this->veiculos as $key => $veiculo) {
            if ($veiculo->getModelo() === $modelo && $veiculo->getPlaca() === $placa) {
                $id = $veiculo->getId();
                unset($this->veiculos[$key]);
                $this->veiculos = array_values($this->veiculos); // Reindexar array
                break;
            }
        }
        
        if ($id !== null) {
            $stmt = $this->db->prepare("DELETE FROM veiculos WHERE id = ?");
            if ($stmt->execute([$id])) {
                return "Veículo '{$modelo}' removido com sucesso!";
            }
            return "Erro ao remover veículo do banco de dados.";
        }
        
        return "Veículo não encontrado.";
    }

}

?>