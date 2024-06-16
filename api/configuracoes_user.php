<?php
// Certifique-se de que você tem a sessão iniciada
session_start();

// Função para redirecionar o usuário para a página de erro 404
function redirecionarPara404() {
    header("Location: 404.php");
    exit;
}

// Verifica se o usuário está logado
if (isset($_SESSION['id'])) {
    // Inclua o arquivo de configuração do banco de dados
    include_once('config.php');

    // Verifique a conexão com o banco de dados
    if ($mysqli->connect_errno) {
        echo "Falha ao conectar ao MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        exit;
    }

    // Recupere o ID do usuário da sessão
    $usuario_id = $_SESSION['id'];

    // Consulta SQL para recuperar os dados do usuário da tabela tb_cadastro_users
    $query_dados_usuario = "SELECT id, foto_perfil, nome, sobrenome, ddd, telefone, cep, rua, numero, complemento, bairro, cidade, estado FROM tb_cadastro_users WHERE id = ?";
    $stmt_dados_usuario = $mysqli->prepare($query_dados_usuario);
    if ($stmt_dados_usuario === false) {
        echo "Erro na preparação da consulta: " . $mysqli->error;
        exit;
    }
    $stmt_dados_usuario->bind_param("i", $usuario_id);
    $stmt_dados_usuario->execute();
    $result_dados_usuario = $stmt_dados_usuario->get_result();

    // Verifica se a consulta foi bem-sucedida
    if ($result_dados_usuario->num_rows > 0) {
        // Extrair os dados do usuário do resultado da consulta
        $row_dados_usuario = $result_dados_usuario->fetch_assoc();
        $id = $row_dados_usuario['id'];
        $foto_nome = $row_dados_usuario['foto_perfil'];
        $nome = $row_dados_usuario['nome'];
        $sobrenome = $row_dados_usuario['sobrenome'];
        $ddd = $row_dados_usuario['ddd'];
        $telefone = $row_dados_usuario['telefone'];
        $cep = $row_dados_usuario['cep'];
        $rua = $row_dados_usuario['rua'];
        $numero = $row_dados_usuario['numero'];
        $complemento = $row_dados_usuario['complemento'];
        $bairro = $row_dados_usuario['bairro'];
        $cidade = $row_dados_usuario['cidade'];
        $estado = $row_dados_usuario['estado'];

        // Define o caminho completo da imagem
        $caminho_imagem = "assets/img/users/$foto_nome";
    } else {
        // Em caso de erro na consulta ou se não encontrou na tabela tb_cadastro_users
        redirecionarPara404();
    }

    // Verificar se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar os dados do formulário
        $email = $_POST['email'];
        // Aqui você pode processar outros dados do formulário conforme necessário
    }

} else {
    // Usuário não está logado, redirecionar para 404.php
    redirecionarPara404();
}

// Fechar a conexão com o banco de dados
$mysqli->close();
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

    <style>
        /* Estilos para os botões */
        .btn-download {
            background-color: #0467F1;
            /* Azul */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .btn-download:hover {
            background-color: #1859B4;
        }

        /* Estilos para os modais */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 15px;
            /* Bordas arredondadas */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            /* Sombra suave */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>

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
                <a href="dashuser.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Início</span>
                </a>
            </li>
            <li>
                <a href="myrequests.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Meus Pedidos</span>
                </a>
            </li>
            <li>
                <a href="favorite_developer.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Meus Desenvolvedores</span>
                </a>
            </li>
            <li>
                <a href="wishlist.php">
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">Lista de desejos</span>
                </a>
            </li>
            <li>
                <a href="alterar_dados.php">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text">Meu Perfil</span>
                </a>
            </li>

        </ul>
        <ul class="side-menu">
            <li class="active">
                <a href="configuracoes_user.php">
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

        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Configurações</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="alterar_dados_user.php">Informações</a>
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
            <h2>Central de controle</h2>
            </br>
            <button id="btnChangePassword" class="btn-download">Alterar Senha</button>
            <button id="btnChangeEmail" class="btn-download">Alterar E-mail</button>

            <!-- Modal para alterar senha -->
            <div id="passwordModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closePasswordModal">&times;</span>
                    <h2>Alterar Senha</h2>
                    <form id="updatePasswordForm" method="post">
                        <label for="password">Nova senha</label>
                        <input type="password" id="password" name="password">
                        <br>
                        <label for="password_confirmation">Repita a senha</label>
                        <input type="password" id="password_confirmation" name="password_confirmation">
                        <input class="btn-download" type="submit" name="submit" value="Salvar">
                    </form>
                    <div id="passwordMessage" style="display:none; margin-top:10px;"></div>
                </div>
            </div>

            <!-- Modal para alterar e-mail -->
            <div id="emailModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeEmailModal">&times;</span>
                    <h2>Alterar E-mail</h2>
                    <form id="updateEmailForm" method="post">
                        <label for="new_email">Novo e-mail</label>
                        <input type="email" id="new_email" name="new_email" required>

                        <label for="password_confirmation_email">Confirme a senha</label>
                        <input type="password" id="password_confirmation_email" name="password_confirmation_email" required>

                        <input class="btn-download" type="submit" name="submit" value="Salvar">
                    </form>
                    <div id="emailMessage" style="display:none; margin-top:10px;"></div>
                </div>
            </div>
        </main>
    </section>
    <!-- CONTENT -->
    </div>
    </div>

    </main><!-- End main -->

    <script>
        // Script para abrir e fechar os modais
        document.getElementById('btnChangePassword').onclick = function() {
            document.getElementById('passwordModal').style.display = 'block';
        }
        document.getElementById('btnChangeEmail').onclick = function() {
            document.getElementById('emailModal').style.display = 'block';
        }
        document.getElementById('closePasswordModal').onclick = function() {
            document.getElementById('passwordModal').style.display = 'none';
        }
        document.getElementById('closeEmailModal').onclick = function() {
            document.getElementById('emailModal').style.display = 'none';
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById('passwordModal')) {
                document.getElementById('passwordModal').style.display = 'none';
            }
            if (event.target == document.getElementById('emailModal')) {
                document.getElementById('emailModal').style.display = 'none';
            }
        }

        // ALTERAR SENHA
        document.getElementById('updatePasswordForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Previne o envio padrão do formulário

            let form = event.target;
            let formData = new FormData(form);

            fetch('assets/php/process_password_change.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('passwordMessage').innerText = data.message;
                    document.getElementById('passwordMessage').style.color = (data.status === 'success') ? 'green' : 'red';
                    document.getElementById('passwordMessage').style.display = 'block';

                    if (data.status === 'success') {
                        // Limpa os campos de senha
                        form.reset();
                        // Opcional: Oculta o formulário e exibe apenas a mensagem
                        form.style.display = 'none';
                    }
                })
                .catch(error => {
                    document.getElementById('passwordMessage').innerText = 'Erro na requisição.';
                    document.getElementById('passwordMessage').style.color = 'red';
                    document.getElementById('passwordMessage').style.display = 'block';
                    console.error('Error:', error);
                });
        });

        // ALTERAR E-MAIL
        document.getElementById('updateEmailForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Previne o envio padrão do formulário

            let form = event.target;
            let formData = new FormData(form);

            fetch('assets/php/process_email_change.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('emailMessage').innerText = data.message;
                    document.getElementById('emailMessage').style.color = (data.status === 'success') ? 'green' : 'red';
                    document.getElementById('emailMessage').style.display = 'block';

                    if (data.status === 'success') {
                        // Limpa os campos do formulário
                        form.reset();
                        // Oculta o formulário e exibe apenas a mensagem
                        form.style.display = 'none';
                    }
                })
                .catch(error => {
                    document.getElementById('emailMessage').innerText = 'Erro na requisição.';
                    document.getElementById('emailMessage').style.color = 'red';
                    document.getElementById('emailMessage').style.display = 'block';
                    console.error('Error:', error);
                });
        });
    </script>

    <script src="assets/js/dashboard.js"></script>
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/lozad/lozad.min.js"></script>
    <script src="assets/libs/device/device.js"></script>
    <script src="assets/libs/spincrement/jquery.spincrement.min.js"></script>


</body>

</html>