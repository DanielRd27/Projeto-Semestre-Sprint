<?php
session_start();

require "../services/streamingServices.php";
$streaming = new Streaming;
$filmes = $streaming->getFilmes();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['alugar'])) {
        $id = $_POST['id'];
        $tipo = $_POST['tipo'];
        
        $streaming->alugarFilme(3, $_SESSION['auth']['user_id']);
        header("Location: carrinho.php");
        exit;
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
            <a href="index.php"><img src="img/logo.png" alt=""></a>
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
                        <li><a class="dropdown-item" href="#">Meus filmes<i class="bi bi-film"></i></a></li>
                        <li><a class="dropdown-item text-primary" href="configAdmin.php">Configs Admin<i class="bi bi-building-gear"></i></i></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#">Sair</a></li>
                    </ul>
                    </div>
            </div>
        </div>
    </header>
    <div class="movie_destaque">
        <img src="img/GDG.png" alt="">
    </div>
    <div class="dropColor"></div>
    <main>
        <div class="container container-forms justify-content-between d-flex gap-5" id="containerMainPage">
              
            <div class="main-movie-info">
                <div class="tittle">
                    <h1>Guardiões da Galáxia</h1>
                </div>
                <div class="sub-dados">
                    <p>2014 . Ação/Ficção científica . 2h 2m</p>
                </div>
                <div class="sinopseAndPrice">
                    <h2>Sinopse</h2>
                    <p>O aventureiro do espaço Peter Quill torna-se presa de caçadores de recompensas depois que rouba a esfera de um vilão traiçoeiro, Ronan. Para escapar do perigo, ele faz uma aliança com um grupo de quatro extraterrestres. Quando Quill descobre que a esfera roubada possui um poder capaz de mudar os rumos do universo, ele e seu grupo deverão proteger o objeto para salvar o futuro da galáxia.</p>
                    <p class="priceMainMovie">Preço: R$19,99</p>
                </div>
                <div class="btn-mainMovie">
                    <button>Alugar Filme</button>
                </div>
            </div>

            <h2 class="white mt-5">Filmes Disponiveis</h2>
            <div class="slider-wrapper">
                <button class="slider-arrow left-arrow d-none">←</button>
                <div class="sliderMovies-container">
                    <div class="sliderMovies">
                        <?php foreach ($filmes as $filme): ?>
                            <div class="itemSlider">
                                <img class="imgSlider" src="<?= $filme->getImagemPath() ?>" alt="">
                                <div class="hover">
                                    <h1 class="title"><?= $filme->getTitulo() ?></h1>
        
                                    <p class="priceHover">R$ <?= number_format($filme->getPreco(), 2, ',', '.') ?> Por Dia</p>
        
                                    <h3 class="sinopseHover">Sinopse</h3>
        
                                    <p class="sinopseHoverConteudo"><?= $filme->getEncurtaSinopse()?></p>

                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= $filme->getId() ?>">
                                        <input type="hidden" name="tipo" value="<?= $filme->getTipo() ?>">
                                        <button type="submit" name="alugar" class="buttonHover">Alugar Este Filme</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="slider-arrow right-arrow d-none">→</button>
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