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

	// Consulta SQL para contar os pedidos do usuário
	$query_pedidos = "SELECT COUNT(*) AS total_pedidos FROM tb_servicos_contratados WHERE user_id = $id_usuario";
	$result_pedidos = mysqli_query($mysqli, $query_pedidos);

	// Verifica se a consulta foi bem-sucedida
	if ($result_pedidos) {
		// Extrai o número total de pedidos do resultado da consulta
		$row_pedidos = mysqli_fetch_assoc($result_pedidos);
		$total_pedidos = $row_pedidos['total_pedidos'];
	} else {
		// Em caso de erro na consulta
		echo "Erro ao recuperar o número de pedidos.";
		exit;
	}

	// Consulta SQL para contar os desenvolvedores favoritos do usuário
	$query_favoritos = "SELECT COUNT(*) AS total_favoritos FROM favorite_developers WHERE user_id = $id_usuario";
	$result_favoritos = mysqli_query($mysqli, $query_favoritos);

	// Verifica se a consulta foi bem-sucedida
	if ($result_favoritos) {
		// Extrai o número total de desenvolvedores favoritos do resultado da consulta
		$row_favoritos = mysqli_fetch_assoc($result_favoritos);
		$total_favoritos = $row_favoritos['total_favoritos'];
	} else {
		// Em caso de erro na consulta
		echo "Erro ao recuperar o número de desenvolvedores favoritos.";
		exit;
	}

	// Consulta SQL para contar os serviços desejados do usuário
	$query_wishlist = "SELECT COUNT(*) AS total_wishlist FROM wishlist WHERE user_id = $id_usuario";
	$result_wishlist = mysqli_query($mysqli, $query_wishlist);

	// Verifica se a consulta foi bem-sucedida
	if ($result_wishlist) {
		// Extrai o número total de serviços desejados do resultado da consulta
		$row_wishlist = mysqli_fetch_assoc($result_wishlist);
		$total_wishlist = $row_wishlist['total_wishlist'];
	} else {
		// Em caso de erro na consulta
		echo "Erro ao recuperar o número de serviços desejados.";
		exit;
	}

	// Consulta SQL para recuperar os pedidos recentes do usuário
	$query_pedidos_recentes = "
        SELECT sc.id, sc.data_contratacao, sc.status, csd.titulo AS nome_servico, cdu.nome AS nome_developer, cdu.foto_perfil AS foto_developer
        FROM tb_servicos_contratados sc
        JOIN tb_cad_servico_dev csd ON sc.service_id = csd.id
        JOIN tb_cadastro_developer cdu ON sc.developer_id = cdu.id
        WHERE sc.user_id = $id_usuario
        ORDER BY sc.data_contratacao DESC
        LIMIT 5
    ";
	$result_pedidos_recentes = mysqli_query($mysqli, $query_pedidos_recentes);

	// Verifica se a consulta foi bem-sucedida
	$pedidos_recentes = [];
	if ($result_pedidos_recentes) {
		while ($row = mysqli_fetch_assoc($result_pedidos_recentes)) {
			$pedidos_recentes[] = $row;
		}
	} else {
		// Em caso de erro na consulta
		echo "Erro ao recuperar os pedidos recentes.";
		exit;
	}

	// Função para retornar o span correto baseado no status
	function getStatusSpan($status)
	{
		$statusClasses = [
			'Pendente' => 'status pending',
			'Em progresso' => 'status process',
			'Completo' => 'status completed',
			'Cancelado' => 'status canceled'
		];

		$class = isset($statusClasses[$status]) ? $statusClasses[$status] : 'unknown';
		return "<span class='status $class'>$status</span>";
	}
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


	<title>OnDev Dashboard</title>
</head>

<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="index.php" class="brand">
			<img src="assets/img/logo-oficial.png">
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="#">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Inicio</span>
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
					<h1>Olá <?php echo $nome; ?>!</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right'></i></li>
						<li>
							<a class="active" href="#">Início</a>
						</li>
					</ul>
				</div>
				<a href="index.php" class="btn-download">
					<i class='bx bx-home'></i>
					<span class="text">Voltar ao Início</span>
				</a>
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-calendar-check'></i>
					<span class="text">
						<h3><?php echo isset($total_pedidos) ? $total_pedidos : '0'; ?></h3>
						<p>Meus Pedidos</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group'></i>
					<span class="text">
						<h3><?php echo isset($total_favoritos) ? $total_favoritos : '0'; ?></h3>
						<p>Dev Favoritos</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-dollar-circle'></i>
					<span class="text">
						<h3><?php echo isset($total_wishlist) ? $total_wishlist : '0'; ?></h3>
						<p>Serviços Desejados</p>
					</span>
				</li>
			</ul>


			<!-- Pedidos Recentes -->
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Pedidos Recentes</h3>
						<i class='bx bx-search'></i>
						<i class='bx bx-filter'></i>
					</div>
					<table>
						<thead>
							<tr>
								<td>
									<img src="assets/img/users/profile_padrao.png">
									<p>Nome do Serviço</p>
								</td>
								<td>Data de Contratação</td>
								<td>Status</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($pedidos_recentes as $pedido) { ?>
								<tr>
									<td>
										<img src="<?php echo 'assets/img/users/' . htmlspecialchars($pedido['foto_developer'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto do Desenvolvedor" onerror="this.src='assets/img/users/profile-padrao.png';">
										<p><?php echo htmlspecialchars($pedido['nome_servico'], ENT_QUOTES, 'UTF-8'); ?></p>
									</td>
									<td><?php echo date('d-m-Y', strtotime($pedido['data_contratacao'])); ?></td>
									<td><?php echo getStatusSpan($pedido['status']); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			</div>
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