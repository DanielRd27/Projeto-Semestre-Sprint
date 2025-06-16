<?php

require "../services/streamingServices.php";
require "../services/tmdb.php";
$streaming = new Streaming;
$tmdb = new Tmdb;
$editar = false;


$apiKey = '72872d046ca2baa1e585a796cd99ccda';
// $filmes = $streaming->getFilmes();
$filmes = $streaming->getFilmes();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['busca']) && !empty(trim($_GET['busca']))) {
        $resultados = [];
        $query = urlencode($_GET['busca']);
        $resultados = $tmdb->pesquisar($query);
    }

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adicionar'])) {
        $newFilme = $tmdb->criarMidia($_POST['id'], $_POST['titulo']);
        $streaming->adicionarMidia($newFilme);
        header("Location: " . $_SERVER['PHP_SELF']);

    } elseif (isset($_POST['deletar'])) {
        $idToDelete = ($_POST['id']);
        $tipoToDelete = ($_POST['tipo']);
        $streaming->deletarMidia($tipoToDelete, $idToDelete);
        header("Location: " . $_SERVER['PHP_SELF']);

    } elseif (isset($_POST['prepararEdit'])) {
        $idToEdit = ($_POST['id']);
        $tipoToEdit = ($_POST['tipo']);
        
        $itemEditar = $streaming->prepararEdit($tipoToEdit, $idToEdit);
        $editar = true;

    } elseif (isset($_POST['salvarEdicao'])) {
        $idToEdit = $_POST['id'];
        $tipoToEdit = $_POST['tipo'];
        $preco = floatval($_POST['preco']);

        $itemEditar = $streaming->prepararEdit($tipoToEdit, $idToEdit);
        $streaming->editarMidia($itemEditar, $preco);

        header("Location: " . $_SERVER['PHP_SELF']);
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
            <a href="index.php  "><img src="img/logo.png" alt=""></a>
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
                        <li><a class="dropdown-item text-primary" href="configAdmin.php">Configs Admin<i class="bi bi-building-gear"></i></i></a></li>
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
            <div class="forms forms-configadmin d-flex justify-content-between">
                <!-- Form 1 -->
                <div class="card-form card-form-custom-index mb-5">
                    <div class="tittle">
                        Adicionar Novo Item
                    </div>
        
                    <!-- Formulário -->
                    <div class="d-flex flex-column" style="width: 100%;">
                        <form method="get" class="d-flex flex-column">
                            <!-- Input nome -->
                            <div class="input-container">
                                <!-- Sistema de pesquisa com DB -->
                                <label for="searchInput">Nome</label>
                                <input name='busca' class="input white" type="text" id="searchInput" placeholder="Digite para buscar..." required>
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
                                                    <input type="hidden" name="titulo" value="<?= $filme['title'] ?>">
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
                </div>

                <!-- Form 2 - EDIT -->
                <?php if ($editar && isset($itemEditar)): ?>
                    <div class="card-form card-form-custom-index mb-5">
                        <div class="tittle">
                            Editar preço
                        </div>
                        <div class="d-flex flex-column mb-5" style="width: 100%;">
                            <form method="post" class="d-flex flex-column">
                                <!-- ID e Tipo escondidos -->
                                <input type="hidden" name="id" value="<?= $itemEditar->getId() ?>">
                                <input type="hidden" name="tipo" value="<?= $itemEditar->getTipo() ?>">
                                <input type="hidden" name="salvarEdicao" value="1">

                                <!-- Campo de título somente leitura -->
                                <div class="input-container">
                                    <label>Título</label>
                                    <input name="titulo" class="input white" type="text" 
                                        value="<?= htmlspecialchars($itemEditar->getTitulo()) ?>" readonly>
                                </div>

                                <!-- Campo de edição de preço -->
                                <div class="input-container">
                                    <label>Preço (R$)</label>
                                    <input name="preco" class="input white" type="number" step="0.01" min="0" 
                                        value="<?= htmlspecialchars($itemEditar->getPreco()) ?>" required>
                                </div>

                                <input type="submit" value="Salvar Alterações" class="input-submit">
                            </form>
                        </div>
                    </div>
                <?php endif; ?>


                <!-- Form/Tabela  -->
                <div class="card-form card-form-custom-index mb-5">
                    <!-- Filmes -->
                    <div class="tittle">
                        Filmes
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th><?= $teste ?></th>
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
                                    <form method="post">
                                        <td><img class="imgTable" src="<?= $filme->getImagemPath() ?>"></td>
                                        <td><?= $filme->getId() ?></td>
                                        <td><?= htmlspecialchars($filme->getTitulo()) ?></td>
                                        <td><?= htmlspecialchars($filme->getEncurtaSinopse()) ?></td>
                                        <td><?= $filme->getReleaseDate() ?></td>
                                        <td><?= htmlspecialchars($filme->getGeneros()) ?></td>
                                        <td><?= $filme->getDuracaoMinutos() ?></td>
                                        <td><?= number_format($filme->getPreco(), 2, ',', '.') ?></td>
                                        <td><?= $filme->isDisponivel() ? 'Sim' : 'Não' ?></td>
                                        <!-- Verificar -->
                                        <td>
                                            <button type="submit" name="deletar" class="btn btn-danger btn-sm delete-btn mb-5">
                                                <i class="bi bi-trash me-1"></i>Deletar
                                            </button>
                                            <button type="submit" name="prepararEdit" class="btn btn-primary btn-sm delete-btn">
                                                <i class="bi bi-pen"></i>Editar
                                            </button>
                                        </td>
                                        <input type="hidden" name="id" value="<?= $filme->getId() ?>">
                                        <input type="hidden" name="preco" value="<?= $filme->getPreco() ?>">
                                        <input type="hidden" name="tipo" value="<?= $filme->getTipo() ?>">
                                    </form>
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