<?php
$apiKey = '72872d046ca2baa1e585a796cd99ccda'; // <-- Substitua com sua API key real
$resultados = [];

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $query = urlencode($_GET['q']);
    $url = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&language=pt-BR&query=$query";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['results'])) {
        foreach ($data['results'] as $filme) {
            $resultados[] = [
            'id' => $filme['id'],
            'title' => $filme['title'],
            'overview' => $filme['overview']
        ];
        }

        // Salva o JSON em arquivo local (opcional)
        $nomeArquivo = 'resultado_' . preg_replace('/\W+/', '_', $_GET['q']) . '.json';
        file_put_contents($nomeArquivo, json_encode($resultados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
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
                <div class="card-form card-form-custom-index">
                    <div class="tittle">
                        Adicionar Novo Item Ao Carrinho
                    </div>
        
                    <!-- Formulário -->
                    <form method="get" class="d-flex flex-column">
                        <!-- Input nome -->
                        <div class="input-container">
                            <!-- Sistema de pesquisa com DB -->
                            <label for="name">Nome</label>
                            <input name='q' class="input white" type="text" id="searchInput" placeholder="Digite para buscar..." required value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                            <!-- Input para Entrar / Enviar dados -->
                        </div>
                        <input type="submit" value="Buscar Item" class="input-submit">

                        <?php if (!empty($resultados)): ?>
                            <div class="filmeBuscadoContainer">
                                <h3>Resultados:</h3>
                                <?php foreach ($resultados as $filme): ?>
                                    <div class="filmeBuscado">
                                        <div class="infosFilmeBuscado">
                                            <p><?= htmlspecialchars($filme['title']) ?></p>
                                            <p name ='id'    >ID: <?= $filme['id'] ?></p>
                                        </div>
                                        <input type="submit" value="Adicionar Item" class="input-submit ifb">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </form>
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