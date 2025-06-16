<?php

require "../services/streamingServices.php";
require "../services/tmdb.php";
$streaming = new Streaming;
$tmdb = new Tmdb;

$apiKey = '72872d046ca2baa1e585a796cd99ccda';
$filmes = $streaming->getFilmes();
$series = $streaming->getSeries();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['busca']) && !empty(trim($_GET['busca']))) {
        $resultados = [];
        $query = urlencode($_GET['busca']);
        $resultados = $tmdb->pesquisar($query);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adicionar'])) {
        $newFilme = $tmdb->criarMidia($_POST['id']);
        $streaming->adicionarMidia($newFilme);
        
        
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Css link -->
    <link rel="stylesheet" href="css/style.css">

    <title>CineFlix - Login page</title>

</head>
<body>
    <!-- Cabeçalho -->
    <header class="header-index">
        <div class="logo">
            <!-- Logo da empresa -->
            <a href="index.html"><img src="img/logo.png" alt=""></a>
        </div>
        <div class="navbar">
            <div class="log-div">
                <div class="dropdown">
                    <!-- Ícone de Perfil (Ativador do Dropdown) -->
                    <button 
                        class="btn dropdown-toggle d-flex align-items-center navbarCustom" 
                        type="button" 
                        data-bs-toggle="dropdown"
                    >
                        <i class="bi bi-person-circle fs-4 me-2"></i>
                        <span>Bem-Vindo, Usuário</span>
                    </button>

                    <!-- Itens do Menu Dropdown -->
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="carrinho.php">Carrinho <i class="bi bi-cart"></i></a></li>
                        <li><a class="dropdown-item" href="#">Meus filmes<i class="bi bi-film"></i></a></li>
                        <li><a class="dropdown-item text-primary" href="#">Configs Admin<i class="bi bi-building-gear"></i></i></a></li>
                        <li><a class="dropdown-item text-primary" href="#">Configs Helper<i class="bi bi-building-gear"></i></i></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#">Sair</a></li>
                    </ul>
                    </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container container-forms justify-content-between d-flex gap-5" id="containerMainPage">
            
            <div class="forms d-flex justify-content-between">
                <!-- Form 1 -->
                <div class="card-form card-form-custom-index mb-5">
                    <div class="tittle">
                        Adicionar Novo Item Ao Carrinho
                    </div>
        
                    <!-- Formulário -->
                    <div class="d-flex flex-column" style="width: 100%;">
                        <form method="get" class="d-flex flex-column">
                            <!-- Input nome -->
                            <div class="input-container">
                                <!-- Sistema de pesquisa com DB -->
                                <label for="name">Nome</label>
                                <input name='busca' class="input white" type="text" id="searchInput" placeholder="Digite para buscar..." required value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
                                <!-- Input para Entrar / Enviar dados -->
                            </div>
                            <input type="submit" value="Buscar Item" class="input-submit">
                        </form>

                        <?php if (!empty($resultados)): ?>
                            <div class="filmeBuscadoContainer">
                                <h3>Resultados:</h3>
                                <?php foreach ($resultados as $filme): ?>
                                    <form method="post" class="d-flex flex-column">
                                        <div class="containerIFB">
                                            <div class="filmeBuscado">
                                                <div class="infosFilmeBuscado">
                                                    <p><?= htmlspecialchars($filme['title']) ?></p>
                                                    <p>ID: <?= $filme['id'] ?></p>
                                                    <input type="hidden" name="id" value="<?= $filme['id'] ?>">
                                                    <input type="hidden" name="adicionar" value="1"> <!-- para identificar no PHP -->
                                                </div>
                                            </div>
                                            <p>Sinopse:<br><br> <?= htmlspecialchars($filme['overview']) ?></p>
                                            <div class="containerInputIFB d-flex justify-content-end">
                                                <input type="submit" value="Adicionar Item" class="input-submit ifb">
                                            </div>
                                        </div>
                                    </form>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </div>

                    <!-- Filmes -->
                    <table>
                        <thead>
                            <tr>
                                <th>Capa</th>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Sinopse</th>
                                <th>Data de Lançamento</th>
                                <th>Gêneros</th>
                                <th>Duração (min)</th>
                                <th>Preço (R$)</th>
                                <th>Disponível</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filmes as $filme): ?>
                                <tr>
                                    <td><img class="imgTable" src="<?= $filme->getImagemPath() ?>"></td>
                                    <td><?= $filme->getDuracaoMinutos() ?></td>
                                    <td><?= htmlspecialchars($filme->getTitulo()) ?></td>
                                    <td><?= htmlspecialchars($filme->getSinopse()) ?></td>
                                    <td><?= $filme->getReleaseDate() ?></td>
                                    <td><?= htmlspecialchars($filme->getGeneros()) ?></td>
                                    <td><?= $filme->getId() ?></td>
                                    <td><?= number_format($filme->getPreco(), 2, ',', '.') ?></td>
                                    <td><?= $filme->isDisponivel() ? 'Sim' : 'Não' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Series -->
                    <table>
                        <thead>
                            <tr>
                                <th>Capa</th>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Sinopse</th>
                                <th>Data de Lançamento</th>
                                <th>Gêneros</th>
                                <th>Temporadas (Qtn)</th>
                                <th>Preço (R$)</th>
                                <th>Disponível</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($series as $serie): ?>
                                <tr>
                                    <td><img class="imgTable" src="<?= $serie->getImagemPath() ?>"></td>
                                    <td><?= $serie->getId() ?></td>
                                    <td><?= htmlspecialchars($serie->getTitulo()) ?></td>
                                    <td><?= htmlspecialchars($serie->getSinopse()) ?></td>
                                    <td><?= $serie->getReleaseDate() ?></td>
                                    <td><?= htmlspecialchars($serie->getGeneros()) ?></td>
                                    <td><?= count($serie->getTemporadasEpisodios()) ?></td>
                                    <td><?= number_format($serie->getPreco(), 2, ',', '.') ?></td>
                                    <td><?= $serie->isDisponivel() ? 'Sim' : 'Não' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>
    </main>

    <!-- Footer ( Rodapé ) -->
    <footer class="d-flex">
        CineFlix 2025 - Todos os direitos reservados
    </footer>


    <script src="js/script.js" defer></script>
    <!-- Srcipt bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>