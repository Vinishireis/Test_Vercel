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

            // Consulta SQL para recuperar os desenvolvedores favoritos do usuário
            $query_favoritos = "SELECT d.id, d.nome, d.sobrenome, d.foto_perfil
            FROM favorite_developers AS fd
            INNER JOIN tb_cadastro_developer AS d ON fd.developer_id = d.id
            WHERE fd.user_id = ?";
            $stmt_favoritos = mysqli_prepare($mysqli, $query_favoritos);
            mysqli_stmt_bind_param($stmt_favoritos, "i", $id_usuario);
            mysqli_stmt_execute($stmt_favoritos);
            $result_favoritos = mysqli_stmt_get_result($stmt_favoritos);

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
<meta name='robots' content='max-image-preview:large'/>
   <link rel="alternate" type="application/rss+xml" title="Make Dream Website &raquo; Feed" href="https://template.makedreamwebsite.com/feed/"/>
   <link rel="alternate" type="application/rss+xml" title="Make Dream Website &raquo; Comments Feed" href="https://template.makedreamwebsite.com/comments/feed/"/>
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

   <title>OnDev Dashboard</title>
   <style>
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        td img {
            max-width: 100px;
            height: auto;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>

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
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Início</span>
				</a>
			</li>
			<li>
				<a href="myrequests.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Meus Pedidos</span>
				</a>
			</li>
            <li class="active">
				<a href="#">
					<i class='bx bxs-group' ></i>
					<span class="text">Meus Desenvolvedores</span>
				</a>
			</li>
            <li>
				<a href="wishlist.php">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Lista de desejos</span>
				</a>
			</li>
			<li>
				<a href="alterar_dados.php">
					<i class='bx bxs-doughnut-chart' ></i>
					<span class="text">Meu Perfil</span>
				</a>
            </li>	
		</ul>
		<ul class="side-menu">
			<li>
				<a href="configuracoes_user.php">
					<i class='bx bxs-cog' ></i>
					<span class="text">Configurações</span>
				</a>
			</li>
			<li>
				<a href="login_new/logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
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
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categorias</a>
			<form action="#">
				<div class="form-input">
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
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
					<h1>Desenvolvedores Favoritos</h1>
					<ul class="breadcrumb">
						<li>
							<a href="wishlist.php">Meus desenvolvedores</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="dashuser.php">Início</a>
						</li>
					</ul>
				</div>
				<a href="index.php" class="btn-download">
					<i class='bx bx-home' ></i>
					<span class="text">Voltar ao Início</span>
				</a>
			</div>
            <!-- LISTA DE SERVIÇOS -->
            <div class="container">
    <h1>Desenvolvedores Favoritos</h1>

    <?php if (isset($result_favoritos) && mysqli_num_rows($result_favoritos) > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Sobrenome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_favorito = mysqli_fetch_assoc($result_favoritos)) { ?>
                    <tr>
                        <td>
                            <?php
                            // Define o caminho completo da imagem
                            $caminho_imagem2 = "assets/img/users/" . $row_favorito['foto_perfil'];
                            // Verifica se o arquivo de imagem existe
                            if (file_exists($caminho_imagem2)) {
                                // Exibe a imagem usando a tag <img>
                                echo "<img src='$caminho_imagem2' alt='Foto de perfil'>";
                            } else {
                                // Se não houver foto de perfil, exibe uma imagem padrão
                                echo '<img src="caminho_da_imagem_padrao" alt="Foto de Perfil padrão">';
                            }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($row_favorito['nome']) ?></td>
                        <td><?= htmlspecialchars($row_favorito['sobrenome']) ?></td>
                        <td><a href="assets/php/remove_developer.php?id=<?= $row_favorito['id'] ?>">Remover</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>Nenhum desenvolvedor favorito encontrado.</p>
    <?php } ?>
</div>
</main>

<script>
    function removerDesenvolvedor(id) {
        if (confirm('Tem certeza de que deseja remover este desenvolvedor favorito?')) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'assets/php/remove_developer.php?id=' + id, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Atualizar a tabela após remover o desenvolvedor favorito
                    atualizarTabelaDesenvolvedores();
                }
            };
            xhr.send();
        }
    }

    function atualizarTabelaDesenvolvedores() {
        // Atualize a tabela aqui após a remoção do desenvolvedor favorito
        location.reload(); // Isso irá recarregar a página para refletir as mudanças
    }
</script>

<script src="assets/js/dashboard.js"></script>
<script src="assets/libs/jquery/jquery.min.js"></script>
<script src="assets/libs/lozad/lozad.min.js"></script>
<script src="assets/libs/device/device.js"></script>
<script src="assets/libs/spincrement/jquery.spincrement.min.js"></script>

</body>
</html>