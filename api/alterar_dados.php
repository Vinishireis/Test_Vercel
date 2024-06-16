<?php
// Certifique-se de que você tem a sessão iniciada
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['id'])) {
    // Inclua o arquivo de configuração do banco de dados
    include_once('config.php');

    // Recupere o ID do usuário da sessão
    $id_usuario = $_SESSION['id'];
    $nome = $_SESSION['nome'];

    // Consulta SQL para recuperar os dados do usuário, incluindo a foto de perfil
    $query = "SELECT id, foto_perfil FROM tb_cadastro_users WHERE id = $id_usuario";
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

    // Consulta SQL para buscar os dados do usuário
    $sql = "SELECT * FROM tb_cadastro_users WHERE id = $id_usuario";

    // Executar a consulta
    $resultado = $mysqli->query($sql);

    // Verificar se a consulta foi bem-sucedida e se encontrou algum usuário
    if ($resultado && $resultado->num_rows > 0) {
        // Recuperar os dados do usuário
        $usuario = $resultado->fetch_assoc();
    } else {
        echo "Erro ao buscar informações do usuário.";
    }

    // Verificar se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar os dados do formulário
        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];
        $ddd = $_POST['ddd'];
        $telefone = $_POST['telefone'];
        $cep = $_POST['cep'];
        $rua = $_POST['rua'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade'];
        $estado = $_POST['estado'];

        // Atualizar a consulta SQL com os campos padrão
        $sql_update = "UPDATE tb_cadastro_users SET 
                       nome = '$nome', 
                       sobrenome = '$sobrenome',
                       ddd = '$ddd', 
                       telefone = '$telefone', 
                       cep = '$cep', 
                       rua = '$rua', 
                       numero = '$numero', 
                       complemento = '$complemento', 
                       bairro = '$bairro', 
                       cidade = '$cidade', 
                       estado = '$estado'";

        // Verificar se uma nova foto de perfil foi enviada
        if (!empty($_FILES['foto_perfil']['name'])) {
            // Caminho de destino da imagem
            $foto_perfil = $_FILES['foto_perfil'];
            $foto_nome = basename($foto_perfil['name']);
            $foto_temp = $foto_perfil['tmp_name'];
            $caminho_imagem = "assets/img/users/$foto_nome";

            // Mover a imagem para o diretório desejado
            if (move_uploaded_file($foto_temp, $caminho_imagem)) {
                // Atualizar a consulta SQL para incluir a nova foto
                $sql_update .= ", foto_perfil = '$foto_nome'";
            } else {
                echo "Erro ao fazer upload da imagem.";
                exit();
            }
        }

        // Verificar se a senha foi fornecida e não está vazia
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql_update .= ", password = '$password'";
        }

        // Finalizar a consulta SQL com a cláusula WHERE
        $sql_update .= " WHERE id = $id_usuario";

        // Executar a consulta de atualização
        if ($mysqli->query($sql_update) === TRUE) {
            echo "Dados atualizados com sucesso!";
            // Redirecionar para a mesma página para exibir os dados atualizados
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Erro ao atualizar os dados: " . $mysqli->error;
        }
    }
} else {
    echo "Usuário não está logado.";
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
            <li class="active">
                <a href="alterar_dados.php">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text">Meu Perfil</span>
                </a>
            </li>

        </ul>
        <ul class="side-menu">
            <li>
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
            <form id="updateForm" method="post" action="#" enctype="multipart/form-data">

             <!-- Exibir a foto de perfil existente -->
             <?php if (!empty($foto_nome)) : ?>
                <div class="profile_img">
                    <h3>Foto de Perfil Atual:</h3>
                    <img src="<?php echo $caminho_imagem; ?>" alt="Foto de Perfil"  style="width: 150px; height: 150px; border-radius: 50%; border: 2px solid #ddd; padding: 5px;">
                </div>
            <?php endif; ?>

                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo $usuario['nome']; ?>"><br><br>

                <label for="sobrenome">Sobrenome:</label>
                <input type="text" name="sobrenome" id="sobrenome" value="<?php echo $usuario['sobrenome']; ?>"><br><br>

                <label for="ddd">DDD:</label>
                <input type="text" name="ddd" id="ddd" value="<?php echo $usuario['ddd']; ?>"><br><br>

                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" value="<?php echo $usuario['telefone']; ?>"><br><br>

                <label for="cep">CEP:</label>
                <input type="text" name="cep" id="cep" value="<?php echo $usuario['cep']; ?>"><br><br>

                <label for="rua">Rua:</label>
                <input type="text" name="rua" id="rua" value="<?php echo $usuario['rua']; ?>"><br><br>

                <label for="numero">Número:</label>
                <input type="text" name="numero" id="numero" value="<?php echo $usuario['numero']; ?>"><br><br>

                <label for="complemento">Complemento:</label>
                <input type="text" name="complemento" id="complemento" value="<?php echo $usuario['complemento']; ?>"><br><br>

                <label for="bairro">Bairro:</label>
                <input type="text" name="bairro" id="bairro" value="<?php echo $usuario['bairro']; ?>"><br><br>

                <label for="cidade">Cidade:</label>
                <input type="text" name="cidade" id="cidade" value="<?php echo $usuario['cidade']; ?>"><br><br>

                <label for="estado">Estado:</label>
                <input type="text" name="estado" id="estado" value="<?php echo $usuario['estado']; ?>"><br><br>

                <label for="foto_perfil">Foto de Perfil:</label>
                <input type="file" name="foto_perfil" id="foto_perfil"><br><br>

                <input class="btn-download" type="submit" name="submit" value="Salvar">
            </form>

           
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    </div>
    </div>

    </main><!-- End main -->


    <script src="assets/js/dashboard.js"></script>
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/lozad/lozad.min.js"></script>
    <script src="assets/libs/device/device.js"></script>
    <script src="assets/libs/spincrement/jquery.spincrement.min.js"></script>

</body>

</html>