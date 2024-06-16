<?php
// Iniciar a sessão
session_start();

// Inclua o arquivo de configuração do banco de dados
include_once('config.php');

// Função para redirecionar para a página de login
function redirect_to_login()
{
    header('Location: login.php');
    exit;
}

// Verifica se o usuário está logado
if (isset($_SESSION['id'], $_SESSION['nome'])) {
    // Recupere o ID do usuário da sessão
    $user_id = $_SESSION['id'];
    $user_nome = $_SESSION['nome'];

    // Consulta SQL para recuperar os dados do usuário, incluindo a foto de perfil
    $query = "SELECT id, foto_perfil FROM tb_cadastro_users WHERE id = $user_id";
    $result = mysqli_query($mysqli, $query);

    // Verifica se a consulta foi bem-sucedida
    if ($result) {
        // Extrai os dados da imagem do resultado da consulta
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
        $foto_nome = $row['foto_perfil'];

        // Define o caminho completo da imagem
        $caminho_imagem = "assets/img/users/$foto_nome";
    } else {
        // Em caso de erro na consulta
        echo "Erro ao recuperar a foto de perfil do banco de dados.";
        exit;
    }
}

// Recupere o ID do serviço e o ID do desenvolvedor da URL
$servico_id = isset($_GET['service_id']) ? $_GET['service_id'] : null;
$developer_id = isset($_GET['developer_id']) ? $_GET['developer_id'] : null;

// Verifica se o ID do serviço foi fornecido
if ($servico_id === null || $developer_id === null) {
    echo "ID do serviço ou desenvolvedor não fornecido.";
    exit;
}

// Consulta SQL para recuperar os dados do serviço
$query = "SELECT * FROM tb_cad_servico_dev WHERE id = ?";
$stmt = mysqli_prepare($mysqli, $query);
mysqli_stmt_bind_param($stmt, "i", $servico_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Verifica se o serviço foi encontrado
if ($row = mysqli_fetch_assoc($result)) {
    $titulo = isset($row['titulo']) ? $row['titulo'] : '';
    $descricao = isset($row['descricao']) ? $row['descricao'] : '';
    $instrucao = isset($row['instrucao']) ? $row['instrucao'] : '';
    $categoria = isset($row['categoria']) ? $row['categoria'] : '';
    $valor = isset($row['valor']) ? $row['valor'] : '';
    $tempo = isset($row['tempo']) ? $row['tempo'] : '';
    $img = isset($row['img']) ? $row['img'] : '';
} else {
    echo "Serviço não encontrado.";
    exit;
}

// Consulta SQL para recuperar as avaliações do serviço
$query_avaliacoes = "
    SELECT a.avaliacao, a.comentario, u.nome, u.foto_perfil
    FROM tb_avaliacoes a
    JOIN tb_cadastro_users u ON a.user_id = u.id
    WHERE a.service_id = ?
";
$stmt_avaliacoes = mysqli_prepare($mysqli, $query_avaliacoes);
mysqli_stmt_bind_param($stmt_avaliacoes, "i", $servico_id);
mysqli_stmt_execute($stmt_avaliacoes);
$result_avaliacoes = mysqli_stmt_get_result($stmt_avaliacoes);

// Consulta SQL para recuperar os dados do desenvolvedor
$query_developer = "SELECT nome, sobrenome, foto_perfil FROM tb_cadastro_developer WHERE id = ?";
$stmt_developer = mysqli_prepare($mysqli, $query_developer);
mysqli_stmt_bind_param($stmt_developer, "i", $developer_id);
mysqli_stmt_execute($stmt_developer);
$result_developer = mysqli_stmt_get_result($stmt_developer);

// Verifica se o desenvolvedor foi encontrado
if ($row_developer = mysqli_fetch_assoc($result_developer)) {
    $nome_developer = isset($row_developer['nome']) ? $row_developer['nome'] : '';
    $sobrenome = isset($row_developer['sobrenome']) ? $row_developer['sobrenome'] : '';
    $foto_perfil = isset($row_developer['foto_perfil']) ? $row_developer['foto_perfil'] : '';
} else {
    echo "Desenvolvedor não encontrado.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="utf-8">
    <meta name="description" content="Description">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
    <link rel="icon" href="assets/img/favicon/logo-oficial.svg" type="image/x-icon">

    <!--=============== REMIX ICONS ===============-->
    <link rel="stylesheet" href="assets/css/servicos_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/bootstrap-grid.css">
    <link rel="stylesheet" href="assets/css/style.css">



    <title>Serviços Disponíveis</title>

    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .service-details {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }

        .service-details img {
            max-width: 300px;
            margin-right: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .details {
            flex: 1;
        }

        .details h2 {
            font-size: 1.5rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .details p {
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .details .price {
            font-size: 1.8rem;
            margin-top: 20px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .developer-details {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .developer-details img.profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid #007bff;
        }

        .developer-details .details {
            flex: 1;
        }

        .developer-details .details h2 {
            font-size: 1.5rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .developer-details .details p {
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .developer-details .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .developer-details .btn:hover {
            background-color: #0056b3;
        }

        .reviews {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .reviews h2 {
            font-size: 1.8rem;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .review {
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .review .review-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .review .review-header img.review-profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .review .review-header .reviewer-name {
            font-weight: bold;
            font-size: 1.2rem;
            color: #333;
        }

        .review .review-body {
            margin-left: 60px;
            /* Para alinhar com a foto de perfil */
        }

        .review .rating {
            color: #ffd700;
            /* Cor das estrelas de rating */
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .review .rating .star {
            margin-right: 2px;
        }

        .review .comment {
            line-height: 1.6;
            color: #555;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.5);
            overflow: hidden;
            /* Removendo a configuração overflow: auto; */
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.5);
            overflow: hidden;
            /* Evita a barra de rolagem extra */
        }

        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 600px;
            margin: auto;
            max-height: calc(100% - 40px);
            /* Altura máxima para ajustar ao tamanho da tela */
            overflow-y: auto;
            /* Adiciona rolagem vertical apenas quando necessário */
        }

        .modal-content h2 {
            margin-top: 0;
        }

        .modal-images {
            text-align: center;
            margin-bottom: 20px;
        }

        .modal-img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            max-height: 300px;
            /* Altura máxima para a imagem */
        }

        .modal-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .modal-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .modal-table td:first-child {
            width: 30%;
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .modal-form input,
        .modal-form textarea {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .modal-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        .modal-form button:hover {
            background-color: #45a049;
        }
    </style>
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
                                                    <?php if (isset($caminho_imagem, $user_nome)) : ?>
                                                        <div onclick="toggle()" class="profile-dropdown-btn">
                                                            <div class="profile-img" style="background-image: url('<?php echo htmlspecialchars($caminho_imagem, ENT_QUOTES, 'UTF-8'); ?>');"></div>
                                                            <span><?php echo htmlspecialchars($user_nome, ENT_QUOTES, 'UTF-8'); ?> <i class="fa-solid fa-angle-down"></i></span>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="profile-dropdown-btn">
                                                            <span>Usuário</span>
                                                        </div>
                                                    <?php endif; ?>
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
                            <a href="services.php">Serviços disponíveis</a>
                            <i class="material-icons md-18">chevron_right</i>
                        </li>
                        <li>Ver detalhes</li>
                    </ul>
                </div>
            </div>
        </div>
    </nav><!-- End bread crumbs -->

    <div class="container">
        <h1><?= htmlspecialchars($titulo) ?></h1>

        <div class="service-details">
            <img src="<?= htmlspecialchars($img) ?>" alt="Imagem do serviço">
            <div class="details">
                <h2>Descrição</h2>
                <p><?= nl2br(htmlspecialchars($descricao)) ?></p>
                <h2>Instruções</h2>
                <p><?= nl2br(htmlspecialchars($instrucao)) ?></p>
                <h2>Categoria</h2>
                <p><?= htmlspecialchars($categoria) ?></p>
                <h2>Tempo para Entrega</h2>
                <p><?= htmlspecialchars($tempo) ?> dias</p>
                <h2 class="price">Preço: R$ <?= htmlspecialchars($valor) ?></h2>
                <button id="contratar-btn" class="btn">Contratar Serviço</button>
                <button id="save-service-btn" class="btn">Salvar</button>
            </div>
        </div>

        <div class="developer-details">
            <img src="assets/img/users/<?= htmlspecialchars($foto_perfil) ?>" alt="Foto do desenvolvedor" class="profile-pic">
            <div class="details">
                <h2>Sobre o Desenvolvedor</h2>
                <p><strong>Nome:</strong> <?= htmlspecialchars($nome_developer) ?></p>
                <p><strong>Biografia:</strong> <?= nl2br(htmlspecialchars($sobrenome)) ?></p>
                <button id="favorite-developer-btn" class="btn">Favoritar</button>
            </div>
        </div>
    </div>

    <div class="reviews">
        <h2>Avaliações</h2>
        <?php while ($row_avaliacao = mysqli_fetch_assoc($result_avaliacoes)) : ?>
            <div class="review">
                <div class="review-header">
                    <img src="assets/img/users/<?= htmlspecialchars($row_avaliacao['foto_perfil']) ?>" alt="Foto de perfil" class="review-profile-pic">
                    <span class="reviewer-name"><?= htmlspecialchars($row_avaliacao['nome']) ?></span>
                </div>
                <div class="review-body">
                    <div class="rating">
                        <?php for ($i = 0; $i < 5; $i++) : ?>
                            <span class="star"><?= $i < $row_avaliacao['avaliacao'] ? '★' : '☆' ?></span>
                        <?php endfor; ?>
                    </div>
                    <p class="comment"><?= nl2br(htmlspecialchars($row_avaliacao['comentario'])) ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Contratar Serviço</h2>
            <div class="modal-images">
                <img src="<?= htmlspecialchars($img) ?>" alt="Imagem do serviço" class="modal-img">
            </div>
            <table class="modal-table">
                <tr>
                    <td>Descrição</td>
                    <td><?= nl2br(htmlspecialchars($descricao)) ?></td>
                </tr>
                <tr>
                    <td>Instruções</td>
                    <td><?= nl2br(htmlspecialchars($instrucao)) ?></td>
                </tr>
                <tr>
                    <td>Categoria</td>
                    <td><?= htmlspecialchars($categoria) ?></td>
                </tr>
                <tr>
                    <td>Tempo para Entrega</td>
                    <td><?= htmlspecialchars($tempo) ?> dias</td>
                </tr>
                <tr>
                    <td>Preço</td>
                    <td>R$ <?= htmlspecialchars($valor) ?></td>
                </tr>
                <tr>
                    <td>Sobre o Desenvolvedor</td>
                    <td>
                        <strong>Nome:</strong> <?= htmlspecialchars($nome_developer) ?><br>
                        <strong>Biografia:</strong> <?= nl2br(htmlspecialchars($sobrenome)) ?>
                    </td>
                </tr>
            </table>
            <form id="contratarForm" method="POST" action="enviar_email.php" class="modal-form">
                <label for="nome">Nome:</label><br>
                <input type="text" id="nome" name="nome" value="<?= isset($user_nome) ? htmlspecialchars($user_nome) : '' ?>" required readonly><br>
                <label for="contato">Contato:</label><br>
                <input type="text" id="contato" name="contato" required><br>
                <label for="informacoes">Informações adicionais:</label><br>
                <textarea id="informacoes" name="informacoes" required></textarea><br>
                <input type="hidden" id="service_id" name="service_id" value="<?= isset($servico_id) ? htmlspecialchars($servico_id) : '' ?>">
                <input type="hidden" id="user_id" name="user_id" value="<?= isset($user_id) ? htmlspecialchars($user_id) : '' ?>">
                <input type="hidden" id="developer_id" name="developer_id" value="<?= isset($developer_id) ? htmlspecialchars($developer_id) : '' ?>">
                <button type="button" id="enviarFormulario" class="btn">Enviar</button>
            </form>
        </div>
    </div>



    <script>
        // Script JavaScript para lidar com o clique no botão Contratar
        var contratarBtn = document.getElementById('contratar-btn');
        contratarBtn.addEventListener('click', function() {
            // Verifica se o usuário está logado antes de prosseguir
            <?php if (!isset($_SESSION['id'], $_SESSION['nome'])) : ?>
                // Redireciona para a página de login se não estiver logado
                window.location.href = 'login.php';
            <?php endif; ?>
        });

        document.getElementById('enviarFormulario').addEventListener('click', function() {
            var enviarBtn = document.getElementById('enviarFormulario');
            enviarBtn.disabled = true; // Desativar o botão
            enviarBtn.textContent = 'Aguarde...'; // Alterar o texto do botão

            var nome = document.getElementById('nome').value;
            var contato = document.getElementById('contato').value;
            var informacoes = document.getElementById('informacoes').value;
            var serviceId = document.getElementById('service_id').value;
            var userId = document.getElementById('user_id').value;
            var developerId = document.getElementById('developer_id').value;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'enviar_email.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    enviarBtn.disabled = false; // Reativar o botão após a resposta do servidor
                    enviarBtn.textContent = 'Enviar'; // Restaurar o texto do botão
                    if (xhr.status == 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                window.location.href = 'success_page.php'; // Substitua com a página de sucesso
                            } else {
                                alert(response.message);
                            }
                        } catch (e) {
                            alert('Erro ao processar a resposta do servidor.');
                            console.error(e);
                        }
                    } else {
                        alert('Erro na requisição. Status: ' + xhr.status);
                    }
                }
            };

            var formData = 'nome=' + encodeURIComponent(nome) +
                '&contato=' + encodeURIComponent(contato) +
                '&informacoes=' + encodeURIComponent(informacoes) +
                '&service_id=' + encodeURIComponent(serviceId) +
                '&user_id=' + encodeURIComponent(userId) +
                '&developer_id=' + encodeURIComponent(developerId);
            xhr.send(formData);
        });

        var modal = document.getElementById('modal');
        var btn = document.getElementById("contratar-btn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }


        document.getElementById('save-service-btn').addEventListener('click', function() {
            var serviceId = <?= $servico_id ?>;
            console.log("Salvando serviço com ID:", serviceId);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'assets/php/salvar_servico.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert(xhr.responseText);
                    if (xhr.responseText === 'Serviço salvo com sucesso.') {
                        document.getElementById('save-service-btn').textContent = 'Salvo';
                    }
                }
            };
            xhr.send('service_id=' + serviceId);
        });

        document.getElementById('favorite-developer-btn').addEventListener('click', function() {
            var developerId = <?= $developer_id ?>;
            console.log("Favoritando desenvolvedor com ID:", developerId);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'assets/php/favoritar_developer.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert(xhr.responseText);
                    if (xhr.responseText === 'Desenvolvedor favoritado com sucesso.') {
                        document.getElementById('favorite-developer-btn').textContent = 'Favorito';
                    }
                }
            };
            xhr.send('developer_id=' + developerId);
        });
    </script>
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

    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/lozad/lozad.min.js"></script>
    <script src="assets/libs/device/device.js"></script>
    <script src="assets/libs/spincrement/jquery.spincrement.min.js"></script>
    <script src="assets/libs/pristine/pristine.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/forms.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="qrcode.min.js"></script>



    <script src="assets/js/script_servicos.js"></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
    <script src="assets/js/script.js"></script>

</body>

</html>