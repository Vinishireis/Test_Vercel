<?php
// Inclui o arquivo config.php para obter a conexão com o banco de dados
include 'config.php';

// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['id'])) {
    // Atribui o valor da variável de sessão 'nome' à variável $nome
    $nome = $_SESSION['nome'];

    // Obtém o objeto mysqli da configuração
    $mysqli = include 'config.php';

    // Prepara a consulta SQL para buscar o perfil do usuário
    $id = $_SESSION['id']; // Armazena o valor em uma variável para segurança
    $sql = "SELECT id, foto_perfil FROM tb_cadastro_users tb_cadastro_developer WHERE id = $id";

    // Executa a consulta SQL
    $result = $mysqli->query($sql);

    // Verifica se a consulta foi bem-sucedida
    if ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $foto_nome = $row['foto_perfil'];
        $caminho_imagem = "assets/img/users/$foto_nome";
    } else {
        echo "Erro ao recuperar a foto de perfil do banco de dados.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Description">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">

    <!--=============== REMIXICONS ===============-->
    <link rel="icon" href="assets\img\favicon\logo-oficial.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/bootstrap-grid.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-600.woff2" as="font" type="font/woff2" crossorigin>

    <!--=============== CSS ===============-->
    <link rel="preload" href="assets/fonts/material-icons/material-icons.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/material-icons/material-icons-outlined.woff2" as="font" type="font/woff2" crossorigin>

    <title>OnDev Termos e Condições</title>
</head>

<body>

    <main class="main">

        <div class="main-inner">

            <!-- Begin mobile main menu -->
            <nav class="mmm">
                <div class="mmm-content">
                    <ul class="mmm-list">
                        <li>
                            <a href="index.php">Início</a>
                        </li>
                        <li>
                            <a href="about-us.php"> Sobre Nós</a>
                        </li>
                        <li>
                            <a href="services.php">Serviços</a>
                        </li>
                        <li>
                            <a href="plans.php">Planos</a>
                        </li>
                        <li>
                            <a href="news.php">Novidades</a>
                        </li>
                        <li>
                            <a href="contacts.php">Contato</a>
                        </li>
                        <li>
                            <div class="btn-group intro-btns">
                                <a href="login.php" class="btn btn-border btn-with-icon btn-small ripple">
                                    <span>Login</span>
                                    <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                        <use xlink:href="assets/img/sprite.svg#arrow-right"></use>
                                    </svg>
                                </a>
                                <a href="login_colaborator.php" class="btn btn-border btn-with-icon btn-small ripple">
                                    <span>Prestador</span>
                                    <svg class="btn-icon-right" viewBox="0 0 13 9" width="14" height="9">
                                        <use xlink:href="assets/img/sprite.svg#arrow-right"></use>
                                    </svg>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End mobile main menu -->

            <header class="header header-minimal">
                <nav class="header-fixed">
                    <div class="container">
                        <div class="row flex-nowrap align-items-center justify-content-between">
                            <div class="col-auto header-fixed-col logo-wrapper">
                                <a href="index.php" class="logo" title="OnDev">
                                    <img src="assets/img/logo-oficial.png" class="enlarged-logo" alt="OnDev">
                                </a>
                            </div>

                            <div class="col-auto col-xl col-static header-fixed-col d-none d-xl-block">
                                <div class="row flex-nowrap align-items-center justify-content-end">
                                    <div class="col header-fixed-col d-none d-xl-block col-static">

                                        <!-- Begin main menu -->
                                        <nav class="main-mnu">
                                            <ul class="main-mnu-list">
                                                <li>
                                                    <a href="index.php" data-title="Início">
                                                        <span>Início</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="about-us.php" data-title="Sobre Nós">
                                                        <span>Sobre Nós</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="services.php" data-title="Serviços">
                                                        <span>Serviços</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="plans.php" data-title="Planos">
                                                        <span>Planos</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="news.php" data-title="Novidades">
                                                        <span>Novidades</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="contacts.php" data-title="Contato">
                                                        <span>Contato</span>
                                                    </a>
                                                </li>
                                                <?php if (isset($_SESSION['id'])) : ?>
                                                    <!-- Se o usuário estiver logado, exibe o nome do usuário e o botão de logout -->
                                                    <li>
                                                        <div class="profile-dropdown">
                                                            <div onclick="toggle()" class="profile-dropdown-btn">
                                                                <div class="profile-img" style="background-image: url('<?php echo htmlspecialchars($caminho_imagem, ENT_QUOTES, 'UTF-8'); ?>');"></div>
                                                                <span><?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?> <i class="fa-solid fa-angle-down"></i></span>
                                                            </div>
                                                            <ul class="profile-dropdown-list">
                                                                <li class="profile-dropdown-list-item">
                                                                    <a href="<?php echo ($tipo_usuario === 'developer') ? 'alterar_dados_dev.php' : 'alterar_dados.php'; ?>">
                                                                        <i class="fa-regular fa-user"></i> Editar Perfil
                                                                    </a>
                                                                </li>
                                                                <li class="profile-dropdown-list-item">
                                                                    <a href="#">
                                                                        <i class="fa-regular fa-envelope"></i> Mensagens
                                                                    </a>
                                                                </li>
                                                                <li class="profile-dropdown-list-item">
                                                                    <a href="<?php echo ($tipo_usuario === 'developer') ? 'dashboad.php' : 'dashuser.php'; ?>">
                                                                        <i class="fa-solid fa-chart-line"></i> Dashboard
                                                                    </a>
                                                                </li>
                                                                <li class="profile-dropdown-list-item">
                                                                    <a href="<?php echo ($tipo_usuario === 'developer') ? 'config_developer.php' : 'configuracoes_user.php'; ?>">
                                                                        <i class="fa-solid fa-sliders"></i> Configurações
                                                                    </a>
                                                                </li>
                                                                <li class="profile-dropdown-list-item">
                                                                    <a href="./contacts.php">
                                                                        <i class="fa-regular fa-circle-question"></i> Ajuda e Suporte
                                                                    </a>
                                                                </li>
                                                                <hr />
                                                                <li class="profile-dropdown-list-item">
                                                                    <a href="logout.php">
                                                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Log out
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php else : ?>
                                                    <!-- Se o usuário não estiver logado, exibe um menu genérico -->
                                                    <li>
                                                        <div class="profile-dropdown">
                                                            <div onclick="toggle()" class="profile-dropdown-btn">
                                                                <div class="profile-img" style="background-image: url('assets/img/users/profile_padrao.png');"></div>
                                                                <span>Convidado<i class="fa-solid fa-angle-down"></i></span>
                                                            </div>
                                                            <ul class="profile-dropdown-list">
                                                                <li class="profile-dropdown-list-item">
                                                                    <a href="login.php">
                                                                        <i class="fa-regular fa-user"></i> Login
                                                                    </a>
                                                                </li>
                                                                <li class="profile-dropdown-list-item">
                                                                    <a href="form2.php">
                                                                        <i class="fa-solid fa-chart-line"></i> Registrar
                                                                    </a>
                                                                </li>
                                                                <li class="profile-dropdown-list-item">
                                                                    <a href="./contacts.php">
                                                                        <i class="fa-regular fa-circle-question"></i> Ajuda e Suporte
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            <!-- End main menu -->
                            <div class="col-auto d-block d-xl-none header-fixed-col">
                                <div class="main-mnu-btn">
                                    <span class="bar bar-1"></span>
                                    <span class="bar bar-2"></span>
                                    <span class="bar bar-3"></span>
                                    <span class="bar bar-4"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <!-- Begin bread crumbs -->
            <nav class="bread-crumbs">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <ul class="bread-crumbs-list">
                                <li>
                                    <a href="index.php">Inicio</a>
                                    <i class="material-icons md-18">chevron_right</i>
                                </li>
                                <li>Termos e Condições</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav><!-- End bread crumbs -->

            <div class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-heading heading-center section-heading-animate">
                                <h1>Termos e Condições</h1>
                            </div>
                            <div class="content">
                                <p>Última atualização 14/03/2024</p>
                                <h2> <strong> 1. Introdução </strong></h2>
                                <p> 1.1. Bem-vindo aos Termos de Uso da Ondev, uma plataforma dedicada a conectar clientes a prestadores de serviços.
                                    Ao utilizar nossos serviços, você concorda com os termos descritos abaixo.</p>
                                <h2><strong> 2. Cadastro e Conta do Usuário </strong> </h2>

                                <p> 2.1. Para utilizar a plataforma Ondev, é necessário realizar um cadastro. Os usuários concordam em fornecer informações precisas e atualizadas. </p>
                                <p> 2.2. Os prestadores de serviço têm a opção de se cadastrar como pessoa física ou jurídica, sendo responsáveis pela veracidade das informações apresentadas. </p>
                                <p> 2.3. A segurança da conta é de responsabilidade do usuário, que deve manter suas credenciais de login de forma confidencial. </p>
                                <h2> <strong> 3. Uso da Plataforma </strong></h2>

                                <p> 3.1. A Ondev proporciona um ambiente para a conexão eficiente entre clientes e prestadores de serviço. Os usuários comprometem-se a utilizar a plataforma de maneira ética e em conformidade com as leis aplicáveis.</h2>
                                <p> 3.2. Qualquer violação destes termos pode resultar na suspensão ou encerramento da conta do usuário. </p>
                                <h2><strong> 4. Conteúdo do Usuário </strong></h2>

                                <p> 4.1. Os prestadores de serviço retêm a propriedade do conteúdo que publicam na plataforma. </p>
                                <p> 4.2. A Ondev reserva-se o direito de remover ou modificar qualquer conteúdo que viole estes Termos de Uso. </p>
                                <h2> <strong> 5. Pagamentos e Anúncios </strong></h2>

                                <p> 5.1. Prestadores de serviço pessoa jurídica concordam em pagar uma taxa para anunciar seus serviços na plataforma. </p>
                                <p> 5.2. A Ondev oferece diversas formas de pagamento para facilitar transações financeiras entre a plataforma e os prestadores de serviço. </p>
                                <h2><strong> 6. Responsabilidades e Limitações </strong></h2>

                                <p> 6.1. A Ondev não se responsabiliza por danos diretos, indiretos, incidentais ou consequenciais decorrentes do uso da plataforma. </p>
                                <p> 6.2. A Ondev reserva-se o direito de suspender ou encerrar contas que violem estes Termos de Uso. </p>
                                <h2><strong> 7. Modificações e Atualizações </strong></h2>

                                <p> 7.1. A Ondev reserva-se o direito de modificar estes Termos de Uso a qualquer momento, notificando os usuários sobre quaisquer alterações. </p>
                                <h2><strong> 8. Cancelamento e Reembolso </strong></h2>

                                <p> 8.1. Em caso de cancelamento por parte do prestador de serviço, a política de reembolso é regida pelos termos específicos da plataforma, sujeita a avaliação individual. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="bff">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="bff-container">
                            <p>OnDev <br class="d-sm-none"> Conheça os nossos</p>
                            <div class="btn-group justify-content-center justify-content-md-start">
                                <a href="services.php" class="btn btn-border btn-with-icon btn-small ripple">
                                    <span>Serviços</span>
                                    <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                        <use xlink:href="assets/img/sprite.svg#arrow-right"></use>
                                    </svg>
                                </a>
                                <a href="#" class="btn btn-border btn-with-icon btn-small ripple">
                                    <span>Colaboradores</span>
                                    <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                        <use xlink:href="assets/img/sprite.svg#arrow-right"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Begin footer -->
        <footer class="footer footer-minimal">
            <div class="footer-main">
                <div class="container">
                    <div class="row items align-items-center">
                        <div class="col-lg-3 col-md-4 col-12 item">
                            <div class="widget-brand-info">
                                <div class="widget-brand-info-main">
                                    <a href="index.php" class="logo" title="OnDev">
                                        <img data-src="assets/img/logo-white.svg" class="lazy" width="133" height="36" src="assets/img/logo-oficial.png" alt="OnDev" data-loaded="true" style="opacity: 1;">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md item">
                            <div class="footer-item">
                                <nav class="footer-nav">
                                    <ul class="footer-mnu footer-mnu-line">
                                        <li><a href="#!" class="hover-link" data-title="Início"><span>Início</span></a></li>
                                        <li><a href="#!" class="hover-link" data-title="Sobre Nós"><span>Sobre Nós</span></a></li>
                                        <li><a href="#!" class="hover-link" data-title="Serviços"><span>Serviços</span></a></li>
                                        <li><a href="#!" class="hover-link" data-title="Novidades"><span>Novidades</span></a></li>
                                        <li><a href="#!" class="hover-link" data-title="Contato"><span>Contato</span></a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row justify-content-between items">
                        <div class="col-md-auto col-12 item">
                            <nav class="footer-links">
                                <ul>
                                    <li><a href="terms-and-conditions.php">Termos e Condições</a></li>
                                    <li><a href="privacy-policy.php">Política de Privacidade</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-md-auto col-12 item">
                            <div class="copyright">© 2024 OnDev. All rights reserved.</div>
                        </div>
                    </div>
                </div>
            </div>
        </footer><!-- End footer -->

    </main><!-- End main -->

    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/lozad/lozad.min.js"></script>
    <script src="assets/libs/device/device.js"></script>
    <script src="assets/libs/spincrement/jquery.spincrement.min.js"></script>
    <script src="assets/libs/pristine/pristine.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/forms.js"></script>
</body>

</html>