

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <!-- Css link -->
    <link rel="stylesheet" href="css/style.css">

    <title>CineFlix - Login page</title>
</head>
<body class="body-size">
    <!-- Cabeçalho -->
    <header>
        <div class="logo">
            <!-- Logo da empresa -->
            <img src="img/logo.png" alt="">
        </div>
    </header>

    <!-- Main ( Parte principal )-->
    <main class="container d-flex justify-content-center align-items-center">
        <div class="container-pai">
            <div class="card-container loginActive d-flex" id="mainCard">
                <!-- Cards com class "redirection" vai fazer aparecer seu respectivo card, por js ou php -->
                <div class="esquerda">
                    <section class="card-redirection card-register-redirection esquerda-card" id="card-loginAside">
                        <h2>Bem vindo de volta ao</h2>
                        <h1>Cine<span>Flix</span></h1> <!-- Span para alterar a cor da letra -->

                        <p>Ainda não tem uma conta?</p>
                        <button class="btn btn-black btn-custom" id="btn-redirection-register">Criar conta</button>
                    </section> 

                    <!-- Card de register -->
                    <!-- section com display none, register -->
                    <section class="card-form card-register esquerda-card" id="card-register">
                        <!-- Tituo principal -->
                        <h1 class="uppercase">Cadastro</h1>

                        <!-- Formulário -->
                        <form method="post" class="d-flex flex-column">
                            <!-- Input usuario -->
                            <div class="input-container">
                                <label for="user">Usuário</label>
                                <input type="text" name="user" class="input custom-input-loginAndRegister" id="email_input_login" required>
                            </div>
                            <!-- Input email -->
                            <div class="input-container">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="input custom-input-loginAndRegister" id="email_input_login" required>
                            </div>
                            <!-- Input senha -->
                            <div class="input-container">
                                <label for="senha">Senha</label>
                                <input type="password" name="senha" class="input custom-input-loginAndRegister" id="password_input_login" required>
                            </div>

                            <a href="#">Já tem uma conta?</a>

                            <!-- Input para Entrar / Enviar dados -->
                            <input type="submit" value="CADASTRAR" class="input-submit">
                        </form>

                    </section>
                </div>
                <div class="direita">
                    <!-- Redirection cards login e register -->
                    <!-- section com display none, "redireciona" para register -->
                    <section class="card-redirection card-login-redirection direita-card" id="card-registerAside"> 
                        <h2>Bem vindo ao</h2>
                        <h1>Cine<span>Flix</span></h1> <!-- Span para alterar a cor da letra -->

                        <p>Já tem uma conta?</p>
                        <button class="btn btn-black btn-custom" id="btn-redirection-login">Fazer login</button>
                    </section> 
                    <section class="card-form card-login direita-card" id="card-login">
                        <!-- Tittle principal -->
                        <h1 class="uppercase">Entrar</h1>

                        <!-- Formulário -->
                        <form method="post" class="d-flex flex-column">
                            <!-- Input email -->
                            <div class="input-container">
                                <label for="user">Usuário</label>
                                <input type="text" name="user" class="input custom-input-loginAndRegister" id="user_input_login" required>
                            </div>
                            <!-- Input senha -->
                            <div class="input-container">
                                <label for="senha">Senha</label>
                                <input type="password" name="senha" class="input custom-input-loginAndRegister" id="password_input_login" required>
                            </div>

                            <a href="#">Ainda não tem conta?</a>

                            <!-- Input para Entrar / Enviar dados -->
                            <input type="submit" value="ENTRAR" class="input-submit">
                        </form>

                    </section>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer ( Rodapé ) -->
    <footer class="d-flex">
        CineFlix 2025 - Todos os direitos reservados
    </footer>


    <!-- Srcipt bootstrap -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
     <script src="js/script.js"></script>
</body>
</html>