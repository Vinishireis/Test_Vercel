<?php
session_start();

// Função para redirecionar o usuário para a página de erro 404
function redirecionarPara404()
{
	header("Location: 404.php");
	exit;
}

// Verifica se o usuário está logado
if (isset($_SESSION['id'])) {
	$id_usuario = $_SESSION['id'];
	$nome = $_SESSION['nome'];

	// Inclua o arquivo de configuração do banco de dados
	include_once('config.php');

	// Verifique a conexão com o banco de dados
	if ($mysqli->connect_errno) {
		echo "Falha ao conectar ao MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		exit;
	}

	// Consulta para obter os dados do usuário, incluindo a foto de perfil e a biografia
	$query_usuario = "SELECT nome, foto_perfil, biografia FROM tb_cadastro_developer WHERE id = ?";
	$stmt_usuario = $mysqli->prepare($query_usuario);
	if ($stmt_usuario === false) {
		echo "Erro na preparação da consulta: " . $mysqli->error;
		exit;
	}
	$stmt_usuario->bind_param("i", $id_usuario);
	$stmt_usuario->execute();
	$result_usuario = $stmt_usuario->get_result();

	if ($result_usuario->num_rows === 1) {
		$row_usuario = $result_usuario->fetch_assoc();
		$nome = $row_usuario['nome'];
		$foto_perfil = $row_usuario['foto_perfil'];
		$biografia = $row_usuario['biografia']; // Adiciona a biografia ao array $row_usuario

		// Define o caminho completo da imagem de perfil
		$caminho_imagem = 'assets/img/users/' . $foto_perfil;

		// Verifica se o arquivo de imagem existe
		if (file_exists($caminho_imagem)) {
			// Exibe a imagem usando a tag <img>
			$imagem_perfil = "<img src='$caminho_imagem' alt='Foto de perfil'>";
		} else {
			// Se não houver foto de perfil, exibe uma imagem padrão
			$imagem_perfil = "<img src='/assets/img/users/profile_padrao.png' alt='Foto de Perfil padrão'>";
		}
	} else {
		// Usuário não encontrado na tabela, redireciona para 404
		redirecionarPara404();
	}
} else {
	// Usuário não está logado, redireciona para a página 404
	redirecionarPara404();
}

// Consulta para contar o número de serviços contratados pelo desenvolvedor
$query_contagem = "SELECT COUNT(*) AS total_servicos FROM tb_servicos_contratados WHERE developer_id = ?";
$stmt_contagem = $mysqli->prepare($query_contagem);
if ($stmt_contagem === false) {
	echo "Erro na preparação da consulta: " . $mysqli->error;
	exit;
}
$stmt_contagem->bind_param("i", $id_usuario);
$stmt_contagem->execute();
$result_contagem = $stmt_contagem->get_result();

if ($result_contagem->num_rows > 0) {
	$row_contagem = $result_contagem->fetch_assoc();
	$total_servicos = $row_contagem['total_servicos'];
} else {
	$total_servicos = 0; // Se não houver registros, define como zero
}

// Consulta para obter os últimos pedidos com a foto do desenvolvedor
$query_pedidos = "SELECT u.nome AS nome_contratante, s.data_contratacao, s.status, u.foto_perfil AS foto_developer
                  FROM tb_servicos_contratados s
                  LEFT JOIN tb_cadastro_users u ON s.user_id = u.id
                  WHERE s.developer_id = ?
                  ORDER BY s.data_contratacao DESC
                  LIMIT 5"; // Limita a 5 últimos pedidos, por exemplo
$stmt_pedidos = $mysqli->prepare($query_pedidos);
if ($stmt_pedidos === false) {
	echo "Erro na preparação da consulta: " . $mysqli->error;
	exit;
}
$stmt_pedidos->bind_param("i", $id_usuario);
$stmt_pedidos->execute();
$result_pedidos = $stmt_pedidos->get_result();

// Array para armazenar os resultados dos pedidos
$pedidos_recentes = [];
while ($row = $result_pedidos->fetch_assoc()) {
	$pedidos_recentes[] = $row;
}

// Função para retornar o span correto baseado no status com cores
function getStatusSpan($status)
{
	$statusColors = [
		'Pendente' => 'color: orange;',
		'Em progresso' => 'color: blue;',
		'Completo' => 'color: green;',
		'Cancelado' => 'color: red;'
	];

	$style = isset($statusColors[$status]) ? $statusColors[$status] : 'color: black;';
	return "<span style='$style'>$status</span>";
}

// Consulta para contar o número de serviços marcados como "Completo"
$query_total_servicos = "SELECT COUNT(*) AS total_servicos
                         FROM tb_servicos_contratados
                         WHERE developer_id = ? AND status = 'Completo'";
$stmt_total_servicos = $mysqli->prepare($query_total_servicos);
if ($stmt_total_servicos === false) {
	echo "Erro na preparação da consulta: " . $mysqli->error;
	exit;
}
$stmt_total_servicos->bind_param("i", $id_usuario);
$stmt_total_servicos->execute();
$result_total_servicos = $stmt_total_servicos->get_result();

if ($result_total_servicos->num_rows > 0) {
	$row_total_servicos = $result_total_servicos->fetch_assoc();
	$total_servicos_completos = $row_total_servicos['total_servicos'];
} else {
	$total_servicos_completos = 0; // Se não houver serviços completos, define como zero
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
		<a href="index.html" class="brand">
			<img src="assets/img/logo-oficial.png">
		</a>
		<ul class="side-menu top">
			<li class="active">
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
			<li>
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
				<?php echo $imagem_perfil; ?>
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
						<h3><?php echo $total_servicos; ?></h3>
						<p>Novos Serviços</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-dollar-circle'></i>
					<span class="text">
						<h3><?php echo $total_servicos; ?></h3>
						<p>Serviços Realizados</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group'></i>
					<span class="text">
						<h3>2834</h3>
						<p>Visitas no perfil</p>
					</span>
				</li>
			</ul>

			<!-- Pedidos Recentes -->
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Pedidos Recentes</h3>
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
										<img src="<?php echo isset($pedido['foto_developer']) ? 'assets/img/users/' . htmlspecialchars($pedido['foto_developer'], ENT_QUOTES, 'UTF-8') : 'assets/img/users/profile_padrao.png'; ?>" alt="Foto do Desenvolvedor" onerror="this.src='assets/img/users/profile_padrao.png';">
										<p><?php echo htmlspecialchars($pedido['nome_contratante'], ENT_QUOTES, 'UTF-8'); ?></p>
									</td>
									<td><?php echo date('d/m/Y', strtotime($pedido['data_contratacao'])); ?></td>
									<td><?php echo getStatusSpan($pedido['status']); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>

				<div class="todo">
					<div class="head">
						<h3>Biografia</h3>
					</div>
					<ul>
						<li style="width: 100%;">
							<?php if (!empty($row_usuario['biografia'])) : ?>
								<p><?php echo htmlspecialchars($row_usuario['biografia']); ?></p>
								<!-- Exibe botão "Alterar" apenas se houver biografia preenchida -->
								<button onclick="mostrarFormulario()">Alterar</button>
							<?php else : ?>
								<!-- Caso não haja biografia preenchida, exibe texto indicativo -->
								<p>Nenhuma biografia cadastrada.</p>
							<?php endif; ?>

							<!-- Formulário para editar ou adicionar biografia -->
							<form id="formBiografia" action="assets/php/salvar_biografia.php" method="POST" style="display: none;">
								<textarea name="biografia" rows="4" style="width: 100%;"><?php echo htmlspecialchars($row_usuario['biografia'] ?? ''); ?></textarea>
								<br>
								<button type="submit">Salvar</button>
							</form>
						</li>
					</ul>
				</div>

				<script>
					// Função para mostrar o formulário ao clicar no botão "Alterar"
					function mostrarFormulario() {
						document.getElementById('formBiografia').style.display = 'block';
					}
				</script>
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