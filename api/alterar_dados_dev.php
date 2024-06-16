<?php
session_start();

// Função para redirecionar o usuário para a página de erro 404
function redirecionarPara404()
{
    header("Location: 404.php");
    exit;
}

// Inclua o arquivo de configuração do banco de dados
include_once('config.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    redirecionarPara404();
}

$id_usuario = $_SESSION['id'];

// Verifica a conexão com o banco de dados
if ($mysqli->connect_errno) {
    echo "Falha ao conectar ao MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
}

// Consulta para obter os dados do usuário, incluindo a foto de perfil
$query_usuario = "SELECT nome, sobrenome, ddd, telefone, cep, rua, numero, complemento, bairro, cidade, estado, foto_perfil FROM tb_cadastro_developer WHERE id = ?";
$stmt_usuario = $mysqli->prepare($query_usuario);
if ($stmt_usuario === false) {
    echo "Erro na preparação da consulta: " . $mysqli->error;
    exit;
}
$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

if ($result_usuario->num_rows === 1) {
    $usuario = $result_usuario->fetch_assoc();
    $foto_nome = $usuario['foto_perfil'] ?? 'profile_padrao.png';

    // Define o caminho completo da imagem de perfil
    $caminho_imagem = 'assets/img/users/' . $foto_nome;

    // Verifica se o arquivo de imagem existe
    if (!file_exists($caminho_imagem)) {
        // Se não houver foto de perfil, define uma imagem padrão
        $foto_nome = 'profile_padrao.png';
    }
} else {
    // Usuário não encontrado na tabela, redireciona para 404
    redirecionarPara404();
}

// Variável para controlar a exibição da mensagem de sucesso
$exibirMensagemSucesso = false;

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Obtém os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $sobrenome = $_POST['sobrenome'] ?? '';
    $ddd = $_POST['ddd'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $rua = $_POST['rua'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $complemento = $_POST['complemento'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $estado = $_POST['estado'] ?? '';

    // Upload da foto de perfil, se uma nova imagem for fornecida
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $nome_arquivo = basename($_FILES['foto_perfil']['name']);
        $caminho_destino = 'assets/img/users/' . $nome_arquivo;

        // Move o arquivo para o diretório de destino
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_destino)) {
            // Atualiza o caminho da foto de perfil no banco de dados
            $query_update = "UPDATE tb_cadastro_developer SET 
                                nome = ?, sobrenome = ?, ddd = ?, telefone = ?, 
                                cep = ?, rua = ?, numero = ?, complemento = ?, 
                                bairro = ?, cidade = ?, estado = ?, foto_perfil = ?
                             WHERE id = ?";
            $stmt_update = $mysqli->prepare($query_update);
            if ($stmt_update === false) {
                echo "Erro na preparação da atualização: " . $mysqli->error;
                exit;
            }
            $stmt_update->bind_param(
                "ssssssssssssi",
                $nome,
                $sobrenome,
                $ddd,
                $telefone,
                $cep,
                $rua,
                $numero,
                $complemento,
                $bairro,
                $cidade,
                $estado,
                $nome_arquivo,
                $id_usuario
            );
            if ($stmt_update->execute()) {
                // Marca para exibir a mensagem de sucesso
                $exibirMensagemSucesso = true;
            } else {
                echo "Erro ao atualizar dados: " . $stmt_update->error;
            }
        } else {
            echo "Erro ao fazer upload da foto de perfil.";
        }
    } else {
        // Caso não haja upload de nova imagem, atualiza apenas os dados pessoais
        $query_update = "UPDATE tb_cadastro_developer SET 
                            nome = ?, sobrenome = ?, ddd = ?, telefone = ?, 
                            cep = ?, rua = ?, numero = ?, complemento = ?, 
                            bairro = ?, cidade = ?, estado = ?
                         WHERE id = ?";
        $stmt_update = $mysqli->prepare($query_update);
        if ($stmt_update === false) {
            echo "Erro na preparação da atualização: " . $mysqli->error;
            exit;
        }
        $stmt_update->bind_param(
            "sssssssssssi",
            $nome,
            $sobrenome,
            $ddd,
            $telefone,
            $cep,
            $rua,
            $numero,
            $complemento,
            $bairro,
            $cidade,
            $estado,
            $id_usuario
        );
        if ($stmt_update->execute()) {
            // Marca para exibir a mensagem de sucesso
            $exibirMensagemSucesso = true;
        } else {
            echo "Erro ao atualizar dados: " . $stmt_update->error;
        }
    }

    // Após a atualização, redireciona para a mesma página depois de 2 segundos
    echo '<script>setTimeout(function() { window.location = "' . $_SERVER['PHP_SELF'] . '"; }, 2000);</script>';
}
?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Description">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!--=============== REMIXICONS ===============-->
    <meta name='robots' content='max-image-preview:large' />
    <link rel="alternate" type="application/rss+xml" title="Make Dream Website &raquo; Feed" href="https://template.makedreamwebsite.com/feed/" />
    <link rel="alternate" type="application/rss+xml" title="Make Dream Website &raquo; Comments Feed" href="https://template.makedreamwebsite.com/comments/feed/" />
    <link rel="shortcut icon" href="assets/img/logo-oficial.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-600.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/material-icons/material-icons.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/material-icons/material-icons-outlined.woff2" as="font" type="font/woff2" crossorigin>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/dashboard_style.css">
    <link rel="stylesheet" href="assets/css/alterar_dados_style.css">

    <title>OnDev Dashboard</title>
</head>

<body>


    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="index.php" class="brand">
            <img src="assets/img/logo-oficial.png">
        </a>
        <ul class="side-menu top">
            <li>
                <a href="dashboard.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Início</span>
                </a>
            </li>
            <li>
                <a href="callings_dev.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Solicitações</span>
                </a>
            </li>
            <li>
                <a href="dash_servicos.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Criar Serviços</span>
                </a>
            </li>
            <li>
                <a href="dashviewserv.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Meus Serviços</span>
                </a>
            </li>
            <li class="active">
                <a href="alterar_dados_dev.php">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text">Meus Dados</span>
                </a>
            </li>
            <!--
            <li>
				<a href="#">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Mensagens</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-group' ></i>
					<span class="text">Equipe</span>
				</a>
			</li>
            -->
        </ul>
        <ul class="side-menu">
            <li>
                <a href="config_developer.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Configurações</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->



    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categorias</a>
            <form action="#">
                <div class="form-input">
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">8</span>
            </a>
            <a href="#" class="profile">
                <?php
                // Verifica se o arquivo de imagem existe
                if (file_exists($caminho_imagem)) {
                    // Exibe a imagem usando a tag <img>
                    echo "<img src='$caminho_imagem' alt='Foto de perfil'>";
                } else {
                    // Se não houver foto de perfil, exibe uma imagem padrão
                    echo '<img src="caminho_da_imagem_padrao" alt="Foto de Perfil padrão">';
                }
                ?>
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Configurações</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="alterar_dados_user.php">Alterar dados</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="dashboard.php">Início</a>
                        </li>
                    </ul>
                </div>
                <a href="index.php" class="btn-download">
                    <i class='bx bx-home'></i>
                    <span class="text">Voltar ao Início</span>
                </a>
            </div>

            <!-- ALTERAR DADOS-->
            </br>
            <h2>Alterar Dados do Usuário</h2>
            </br>
            <form id="updateForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                <!-- Exibir a foto de perfil existente -->
                <?php if (!empty($foto_nome)) : ?>
                    <div id="currentProfile">
                        <h3>Foto de Perfil Atual:</h3>
                        <img src="assets/img/users/<?php echo htmlspecialchars($foto_nome); ?>" alt="Foto de Perfil">
                    </div>
                <?php endif; ?>

                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>">

                <label for="sobrenome">Sobrenome:</label>
                <input type="text" name="sobrenome" id="sobrenome" value="<?php echo htmlspecialchars($usuario['sobrenome'] ?? ''); ?>">

                <label for="ddd">DDD:</label>
                <input type="text" name="ddd" id="ddd" value="<?php echo htmlspecialchars($usuario['ddd'] ?? ''); ?>">

                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>">

                <label for="cep">CEP:</label>
                <input type="text" name="cep" id="cep" value="<?php echo htmlspecialchars($usuario['cep'] ?? ''); ?>">

                <label for="rua">Rua:</label>
                <input type="text" name="rua" id="rua" value="<?php echo htmlspecialchars($usuario['rua'] ?? ''); ?>">

                <label for="numero">Número:</label>
                <input type="text" name="numero" id="numero" value="<?php echo htmlspecialchars($usuario['numero'] ?? ''); ?>">

                <label for="complemento">Complemento:</label>
                <input type="text" name="complemento" id="complemento" value="<?php echo htmlspecialchars($usuario['complemento'] ?? ''); ?>">

                <label for="bairro">Bairro:</label>
                <input type="text" name="bairro" id="bairro" value="<?php echo htmlspecialchars($usuario['bairro'] ?? ''); ?>">

                <label for="cidade">Cidade:</label>
                <input type="text" name="cidade" id="cidade" value="<?php echo htmlspecialchars($usuario['cidade'] ?? ''); ?>">

                <label for="estado">Estado:</label>
                <input type="text" name="estado" id="estado" value="<?php echo htmlspecialchars($usuario['estado'] ?? ''); ?>">

                <label for="foto_perfil">Nova Foto de Perfil:</label>
                <input type="file" name="foto_perfil" id="foto_perfil">

                <input type="submit" name="submit" value="Atualizar">
            </form>
        </main>
        <!-- MAIN -->
    </section>


    <script>
        // Verifica se a mensagem de sucesso deve ser exibida
        <?php if ($exibirMensagemSucesso) : ?>
            alert('Dados atualizados com sucesso!');
        <?php endif; ?>

        function toggleTheme() {
            var body = document.body;
            if (body.classList.contains('dark-mode')) {
                body.classList.remove('dark-mode');
                body.classList.add('light-mode');
            } else {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
            }
        }
    </script>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/lozad/lozad.min.js"></script>
    <script src="assets/libs/device/device.js"></script>
    <script src="assets/libs/spincrement/jquery.spincrement.min.js"></script>

</body>

</html>