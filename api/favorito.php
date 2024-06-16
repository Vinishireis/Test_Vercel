<?php
// Inclui o arquivo config.php para obter a conexão com o banco de dados
include 'config.php';

// Atribui a conexão à variável $conn
$conn = $mysqli;

// Inicia a sessão
session_start();

// Definir valores padrão
$caminho_imagem = 'assets/img/users/profile_padrao.png';
$nome = 'Convidado';
$tipo_usuario = null;

// Verifica se o usuário está logado
if (isset($_SESSION['id'])) {
    $id = (int)$_SESSION['id']; // Cast para inteiro para segurança
    $sql = "
        SELECT 'user' AS tipo, id, foto_perfil, nome FROM tb_cadastro_users WHERE id = ?
        UNION ALL
        SELECT 'developer' AS tipo, id, foto_perfil, nome FROM tb_cadastro_developer WHERE id = ?
    ";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('ii', $id, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $tipo_usuario = $row['tipo'];
            $id = $row['id'];
            $foto_nome = $row['foto_perfil'];
            $nome = $row['nome'];
            $caminho_imagem = "assets/img/users/$foto_nome";
        } else {
            echo "Erro ao recuperar os dados do banco de dados.";
            exit;
        }
        $stmt->close();
    } else {
        echo "Erro ao preparar a consulta SQL: " . $mysqli->error;
    }
}

// Verificar se o ID do desenvolvedor foi passado e é um número inteiro
if (isset($_GET['developer_id']) && is_numeric($_GET['developer_id'])) {
    $developer_id = intval($_GET['developer_id']); // Converte para inteiro

    // Buscar dados do desenvolvedor usando uma consulta preparada para segurança
    $developer_sql = "SELECT * FROM tb_cadastro_developer WHERE id = ?";
    if ($stmt = $conn->prepare($developer_sql)) {
        $stmt->bind_param("i", $developer_id);
        $stmt->execute();
        $developer_result = $stmt->get_result();
        $developer = $developer_result->fetch_assoc();
        $stmt->close();
    } else {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    // Verificar se o desenvolvedor foi encontrado
    if ($developer) {
        // Buscar serviços do desenvolvedor usando uma consulta preparada para segurança
        $services_sql = "SELECT id, titulo, img, categoria, data_criacao, status, id_developer FROM tb_cad_servico_dev WHERE id_developer = ?";
        if ($stmt = $conn->prepare($services_sql)) {
            $stmt->bind_param("i", $developer_id);
            $stmt->execute();
            $services_result = $stmt->get_result();
        } else {
            die('Erro na preparação da consulta: ' . $conn->error);
        }

        // Consulta SQL para contar serviços contratados
        $count_services_sql = "SELECT COUNT(*) AS total_servicos FROM tb_servicos_contratados WHERE developer_id = ?";
        if ($stmt = $conn->prepare($count_services_sql)) {
            $stmt->bind_param("i", $developer_id);
            $stmt->execute();
            $count_services_result = $stmt->get_result();
            $count_services = $count_services_result->fetch_assoc();
            $total_servicos_contratados = $count_services['total_servicos'];
            $stmt->close();
        } else {
            die('Erro na preparação da consulta de contagem de serviços: ' . $conn->error);
        }

        // Consulta SQL para obter a última data de login
        $last_login_sql = "SELECT ultima_login FROM tb_cadastro_developer WHERE id = ?";
        if ($stmt = $conn->prepare($last_login_sql)) {
            $stmt->bind_param("i", $developer_id);
            $stmt->execute();
            $last_login_result = $stmt->get_result();
            $last_login_row = $last_login_result->fetch_assoc();
            $last_login = $last_login_row['ultima_login'];
            $stmt->close();
        } else {
            die('Erro na preparação da consulta de última login: ' . $conn->error);
        }

        if (!empty($last_login)) {
            // Data e hora atuais no fuso horário do servidor PHP
            $agora = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
            $ultimo_login = new DateTime($last_login); // Último login do desenvolvedor

            // Calcula a diferença entre as duas datas
            $intervalo = $agora->diff($ultimo_login);

            // Formata o intervalo para exibição amigável
            if ($intervalo->y > 0) {
                $tempo_decorrido = $intervalo->format('%y anos atrás');
            } elseif ($intervalo->m > 0) {
                $tempo_decorrido = $intervalo->format('%m meses atrás');
            } elseif ($intervalo->d > 0) {
                $tempo_decorrido = $intervalo->format('%d dias atrás');
            } elseif ($intervalo->h > 0) {
                $tempo_decorrido = $intervalo->format('%h horas atrás');
            } elseif ($intervalo->i > 0) {
                $tempo_decorrido = $intervalo->format('%i minutos atrás');
            } else {
                $tempo_decorrido = 'Recentemente';
            }
        } else {
            $tempo_decorrido = 'Nunca';
        }

        // Buscar avaliações do desenvolvedor usando uma consulta preparada para segurança
        $reviews_sql = "SELECT tb_avaliacoes.*, tb_cadastro_users.nome AS user_nome FROM tb_avaliacoes 
                        JOIN tb_cadastro_users ON tb_avaliacoes.user_id = tb_cadastro_users.id 
                        WHERE tb_avaliacoes.developer_id = ?";
        if ($stmt = $conn->prepare($reviews_sql)) {
            $stmt->bind_param("i", $developer_id);
            $stmt->execute();
            $reviews_result = $stmt->get_result();
        } else {
            die('Erro na preparação da consulta: ' . $conn->error);
        }
    } else {
        die('Desenvolvedor não encontrado.');
    }
} else {
    die('ID de desenvolvedor inválido.');
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
    <meta name='robots' content='max-image-preview:large' />
    <link rel="alternate" type="application/rss+xml" title="Make Dream Website &raquo; Feed" href="https://template.makedreamwebsite.com/feed/" />
    <link rel="alt ernate" type="application/rss+xml" title="Make Dream Website &raquo; Comments Feed" href="https://template.makedreamwebsite.com/comments/feed/" />
    <link rel="shortcut icon" href="assets/img/logo-oficial.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-600.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/material-icons/material-icons.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/material-icons/material-icons-outlined.woff2" as="font" type="font/woff2" crossorigin>

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/bootstrap-grid.css">
    <link rel="stylesheet" href="assets/css/perfil_product.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <title>OnDev</title>
</head>

<body>


    <!-- Begin mobile main menu -->
    <nav class="mmm">
        <div class="mmm-content">
            <ul class="mmm-list">
                <li><a href="index.php">Início</a></li>
                <li><a href="about-us.php">Sobre Nós</a></li>
                <li><a href="services.php">Serviços</a></li>
                <li><a href="plans.php">Planos</a></li>
                <li><a href="news.php">Novidades</a></li>
                <li><a href="contacts.php">Contato</a></li>

                <!-- Se o usuário não estiver logado, exibe o link de login -->
                <li>
                    <a href="login.php" data-title="Login">
                        <span>Login</span>
                    </a>
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
                    <!-- Modal -->
                    <div id="qrCodeModal" class="modal">
                        <div class="modal-content">
                            <p>Scaneie o QR Code para fazer Login</p>
                            <span class="close" onclick="closeModal()">&times;</span>
                            <iframe src="generate.php" frameborder="0" style="width:100%; height:400px;"></iframe>
                        </div>
                    </div>

                    <div class="col-auto d-block d-xl-none header-fixed-col">
                        <div class="main-mnu-btn">
                            <span class="bar bar-1"></span>
                            <span class="bar bar-2"></span>
                            <span class="bar bar-3"></span>
                            <span class="bar bar-4"></span>
                        </div>
                    </div>
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
                            <a href="services.php">Serviços disponíveis</a>
                            <i class="material-icons md-18">chevron_right</i>
                        </li>
                        <li>Ver perfil</li>
                    </ul>
                </div>
            </div>
        </div>
    </nav><!-- End bread crumbs -->

    <!---PERFIL -->
    <section class="dashboard section">
        <!-- Container Start -->
        <div class="container">
            <!-- Row Start -->
            <div class="row">
                <div class="col-md-4">
                    <div class="sidebar">
                        <!-- User Widget -->
                        <div class="widget user-dashboard-profile">
                            <!-- User Image -->
                            <div class="profile-thumb">
                                <img src="assets/img/users/<?php echo htmlspecialchars($developer['foto_perfil']); ?>" alt="" class="rounded-circle">
                            </div>
                            <!-- User Name -->
                            <h5 class="text-center"><?php echo htmlspecialchars($developer['nome']); ?></h5>
                            <p class="text-center"><?php echo htmlspecialchars($developer['sobrenome']); ?></p>
                        </div>
                        <div class="widget user-dashboard-menu">
                            <h6>Detalhes</h6>
                            <ul>
                                <li><a><i class="fa fa-briefcase"></i> Serviços Contratados: <?php echo htmlspecialchars($total_servicos_contratados); ?></a></li>
                                <li><a><i class="fa fa-clock"></i> Último Login: <?php echo htmlspecialchars($tempo_decorrido); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Developer Info -->
                    <div class="widget dashboard-container my-adslist">
                        <h3 class="widget-header">Perfil do desenvolvedor</h3>
                        <div class="developer-info">
                            <div class="info-item">
                                <h6>Nome Completo:</h6>
                                <p><?php echo htmlspecialchars($developer['nome'] . ' ' . $developer['sobrenome']); ?></p>
                            </div>
                            <div class="info-item">
                                <h6>Biografia:</h6>
                                <p><?php echo nl2br(htmlspecialchars($developer['biografia'] ?? 'N/A')); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Active Services -->
                    <div class="widget dashboard-container my-adslist">
                        <h3 class="widget-header">Serviços ativos de <?php echo htmlspecialchars($developer['nome']); ?></h3>
                        <table class="table table-responsive product-dashboard-table">
                            <thead>
                                <tr>
                                    <th>Imagem</th>
                                    <th>Título</th>
                                    <th class="text-center">Categoria</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($service = $services_result->fetch_assoc()) : ?>
                                    <tr>
                                        <td class="product-thumb">
                                            <img width="80px" height="auto" src="<?php echo htmlspecialchars($service['img']); ?>" alt="image description">
                                        </td>
                                        <td class="product-details">
                                            <a href="view_product.php?service_id=<?php echo htmlspecialchars($service['id']); ?>&developer_id=<?php echo htmlspecialchars($service['id_developer']); ?>">
                                                <h3 class="title"><?php echo htmlspecialchars($service['titulo']); ?></h3>
                                            </a>
                                            <span><strong>Criado em: </strong><time><?php echo htmlspecialchars(date('d/m/Y', strtotime($service['data_criacao']))); ?></time></span>
                                            <span class="status"><strong>Status: </strong><?php echo htmlspecialchars($service['status'] ?? 'N/A'); ?></span>
                                        </td>
                                        <td class="product-category"><span class="categories"><?php echo htmlspecialchars($service['categoria']); ?></span></td>
                                        <td class="action" data-title="Action">
                                            <div class="">
                                                <ul class="list-inline justify-content-center">
                                                    <li class="list-inline-item">
                                                        <a data-toggle="tooltip" data-placement="top" title="Ver" class="view" href="view_product.php?service_id=<?php echo htmlspecialchars($service['id']); ?>&developer_id=<?php echo htmlspecialchars($service['id_developer']); ?>">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php $services_result->free(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Reviews -->
                    <div class="widget dashboard-container my-adslist">
                        <h3 class="widget-header">Avaliações sobre <?php echo htmlspecialchars($developer['nome']); ?> como Desenvolvedor</h3>
                        <table class="table table-responsive product-dashboard-table">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Avaliação</th>
                                    <th>Comentário</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($review = $reviews_result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($review['user_nome']); ?></td>
                                        <td>
                                            <div class="rating">
                                                <?php for ($i = 0; $i < 5; $i++) : ?>
                                                    <span class="star"><?= $i < $review['avaliacao'] ? '★' : '☆' ?></span>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                        <td><?php echo nl2br(htmlspecialchars($review['comentario'])); ?></td>
                                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($review['data_avaliacao']))); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php $reviews_result->free(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Row End -->
        </div>
        <!-- Container End -->
    </section>
    <!---PERFIL FIM -->
    </div>

    <div class="bff">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="bff-container">
                        <p>OnDev <br class="d-sm-none"> Conheça os nossos</p>
                        <div class="btn-group justify-content-center justify-content-md-start">
                            <a href="services.html" class="btn btn-border btn-with-icon btn-small ripple">
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
                                <a href="index.html" class="logo" title="OnDev">
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
                                <li><a href="terms-and-conditions.html">Termos e Condições</a></li>
                                <li><a href="privacy-policy.html">Política de Privacidade</a></li>
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
    <script src="assets/js/script.js"></script>

</body>

</html>
<?php
// Fechar a conexão com o banco de dados
$mysqli->close();
?>