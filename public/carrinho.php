<?php

require "../services/streamingServices.php";
$streaming = new Streaming;
$filmes = $streaming->getFilmes();

$filmesAlugados = $streaming->getFilmesAlugados();  


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['excluirCarrinho'])) {
        $titulo = $_POST['titulo'];
        $tipo = $_POST['tipo'];
        
        foreach ($filmes as $filme) {
            if ($filme->getTitulo() == $titulo) {
                $filme->carrinhoOff();
                header("Location: carrinho.php");
                exit;
            }
        }
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
                    <div class="card-form card-form-custom-index mb-5">
                    <!-- Filmes -->
                    <div class="tittle">
                        Filmes No Carrinho
                    </div>
                    <div class="itensCarrinhos">
                        <?php foreach ($filmesAlugados as $filme): ?>
                            <form class="itemCarrinho" method="post">

                                <div class="topTittle">
                                    <p>#1</p>
                                    <p class="precoTopTittle">Preço:</p>
                                </div>
                                <div class="conteudoCarrinho">
                                    <img src="<?= $filme->getImagemPath() ?>" alt="">
                                    <div class="infosFilmeCarrinho">
                                        <div class="topInfos">
                                            <div class="tittleAndSubInfos">
                                                <p class="tittleCarrinho"><?= $filme->getTitulo()?></p>
                                                <p class="subInfosCarrinho"><?= $filme->getReleaseDate() ?></p>
                                            </div>
                                            <div class="preco">R$ <?= number_format($filme->getPreco(), 2, ',', '.')?></div>
                                        </div>
        
                                        <div class="sinopseCarrinho">
                                            <h1>Sinopse</h1>
                                            <p><?= htmlspecialchars($filme->getEncurtaSinopse()) ?></p>
                                        </div>
        
                                        <span class="filmeDisponivel red"><?php if(!$filme->isDisponivel()) {
                                            echo 'Indisponivel';}?></span>
        
                                        <div class="inputs">
                                            <div class="dias">
                                                <p>Quantos Dias Você Deseja Alugar</p>
                                                <select name="diasSelect">
                                                    <option value="1">1 Dia</option>
                                                    <option value="2">2 Dias</option>
                                                    <option value="3">3 Dias</option>
                                                    <option value="4">4 Dias</option>
                                                    <option value="5">5 Dias</option>
                                                    <option value="6">6 Dias</option>
                                                    <option value="7">7 Dias</option>
                                                    <option value="8">8 Dias</option>
                                                    <option value="9">9 Dias</option>
                                                    <option value="10">10 Dias</option>
                                                </select>
                                            </div>
                                            
                                            <input type="hidden" name="titulo" value="<?= $filme->getTitulo() ?>">
                                            <input type="hidden" name="tipo" value="<?= $filme->getTipo() ?>">
                                            <button type="submit" name="excluirCarrinho" class="red">Excluir Item Do Carrinho</button>
                                        </div>
        
        
                                    </div>
                                </div>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Table do carrinho -->
            <div class="card-form-custom-index"></div>
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