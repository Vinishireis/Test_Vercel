<?php
include('config.php');

$login_sucesso = false;
$login_erro = false;
date_default_timezone_set('America/Sao_Paulo');

if (isset($_POST['email']) && isset($_POST['password'])) {
    if (strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if (strlen($_POST['password']) == 0) {
        echo "Preencha sua senha";
    } else {

        $email = $mysqli->real_escape_string($_POST['email']);
        $password = $_POST['password'];
        $usuario = null;
        $tipo_usuario = '';

        // Verifica o tipo de usuário selecionado
        if (isset($_POST['tipo_usuario']) && !empty($_POST['tipo_usuario'])) {
            $tipo_usuario = $mysqli->real_escape_string($_POST['tipo_usuario']);
            $tabela = '';

            // Determina a tabela correta baseada no tipo de usuário selecionado
            if ($tipo_usuario == 'consumidor') {
                $tabela = 'tb_cadastro_users';
            } elseif ($tipo_usuario == 'desenvolvedor') {
                $tabela = 'tb_cadastro_developer';
            }

            if ($tabela) {
                // Busca o usuário pelo e-mail na tabela correta
                $sql_code = "SELECT * FROM $tabela WHERE email = '$email'";
                $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
                if ($sql_query->num_rows == 1) {
                    $usuario = $sql_query->fetch_assoc();

                    // Verifica se o usuário está bloqueado
                    if ($usuario['bloqueado'] == 1) {
                        $login_erro = true; // Define que houve um erro de login
                        // Mensagem de erro será exibida no HTML abaixo
                    }
                }
            }
        }

        // Se não encontrou em nenhuma das tabelas acima, verifica na tabela de administradores
        if (!$usuario) {
            $sql_code = "SELECT * FROM administradores WHERE email = '$email'";
            $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
            if ($sql_query->num_rows == 1) {
                $usuario = $sql_query->fetch_assoc();
                $tipo_usuario = 'administrador';

                // Não é necessário verificar bloqueio para administradores
            }
        }

        // Se o usuário foi encontrado em alguma das tabelas
        if ($usuario) {
            // Verifica se a senha fornecida corresponde à senha armazenada no banco de dados
            if (isset($usuario['password']) && password_verify($password, $usuario['password'])) {
                $login_sucesso = true;

                if (!isset($_SESSION)) {
                    session_start();
                }

                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['tipo_usuario'] = $tipo_usuario;

                // Atualiza o campo ultima_login na tabela tb_cadastro_developer
                if ($tipo_usuario == 'desenvolvedor') {
                    $id_do_desenvolvedor = $usuario['id']; // Obtém o ID do desenvolvedor
                    $update_login_sql = "UPDATE tb_cadastro_developer SET ultima_login = NOW() WHERE id = ?";
                    if ($stmt = $mysqli->prepare($update_login_sql)) {
                        $stmt->bind_param("i", $id_do_desenvolvedor);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        die('Erro ao atualizar o último login: ' . $mysqli->error);
                    }

                    header("Location: dashboard.php");
                    exit(); // Certifique-se de sair do script após o redirecionamento
                } elseif ($tipo_usuario == 'consumidor') {
                    header("Location: dashuser.php");
                    exit(); // Certifique-se de sair do script após o redirecionamento
                } elseif ($tipo_usuario == 'administrador') {
                    header("Location: admin/dash_adm.php");
                    exit(); // Certifique-se de sair do script após o redirecionamento
                }
            } else {
                $login_erro = true; // Define que houve um erro de login
            }
        } else {
            $login_erro = true; // Define que houve um erro de login
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--=============== REMIXICONS ===============-->
    <link rel="icon" href="assets/img/favicon/logo-oficial.svg" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/bootstrap-grid.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login_styles.css">
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-600.woff2" as="font" type="font/woff2" crossorigin>

    <!-- ===== Ícones do Iconscout ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="assets/style.css">

    <style>
        /* Outros estilos podem ser mantidos ou ajustados conforme necessário */
        .error-message {
            color: #f44336;
            /* Cor vermelha para indicar erro */
            font-size: 14px;
            margin-top: 8px;
            text-align: center;
        }
    </style>
    <title>Formulário de Login e Registro</title>
</head>

<body>
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Login</span>

                <form method="post" action="#">
                    <div class="input-field">
                        <input type="text" name="email" placeholder="Digite seu e-mail" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" class="password" placeholder="Digite sua senha" required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>

                    <div class="radio-field">
                        <input type="radio" id="consumidor" name="tipo_usuario" value="consumidor" checked>
                        <label class="consumer" for="consumidor" class="text">Consumidor</label>
                        <input type="radio" id="desenvolvedor" name="tipo_usuario" value="desenvolvedor">
                        <label class="developer" for="desenvolvedor" class="text">Desenvolvedor</label>
                    </div>

                    <div class="checkbox-text">
                        <div class="checkbox-content">
                            <input type="checkbox" id="logCheck">
                            <label for="logCheck" class="text">Lembrar-me</label>
                        </div>

                        <a href="./rec_senha.php" class="text">Esqueceu a senha?</a>
                    </div>

                    <div class="input-field button">
                        <input type="submit" name="submit" value="Login">
                    </div>
                    <!-- Mensagem de erro -->
                    <?php if ($login_erro) : ?>
                        <?php if ($usuario && $usuario['bloqueado'] == 1) : ?>
                            <p class="error-message">Seu usuário está bloqueado. Entre em contato com o suporte.</p>
                        <?php else : ?>
                            <p class="error-message">Falha ao logar! E-mail ou senha incorretos.</p>
                        <?php endif; ?>
                    <?php endif; ?>

                </form>

                <div class="login-signup">
                    <span class="text">Não é membro?
                        <a href="./form2.php" class="text signup-link">Cadastre-se Agora</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Scripts ===== -->
    <script src="assets/js/login_script.js"></script>
    <!-- Coloque seus scripts aqui conforme necessário -->
</body>

</html>