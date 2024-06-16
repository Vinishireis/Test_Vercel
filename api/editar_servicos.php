<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    echo "Usuário não logado.";
    exit;
}

include_once('config.php');

// Recupere o ID do usuário da sessão
$id_usuario = $_SESSION['id'];
$nome = $_SESSION['nome'];

// Recupere o ID do usuário da sessão
$usuario_id = $_SESSION['id'];
$id_developer = $_SESSION['id'];

    // Consulta SQL para recuperar os dados do usuário, incluindo a foto de perfil
    $query = "SELECT id, foto_perfil FROM tb_cadastro_developer WHERE id = $id_usuario";
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

// Verifique se o ID do serviço foi passado na URL
if (!isset($_GET['id'])) {
    echo "ID do serviço não fornecido.";
    exit;
}

$servico_id = $_GET['id'];

// Consulta SQL para recuperar os dados do serviço e verificar se pertence ao desenvolvedor logado
$query = "SELECT * FROM tb_cad_servico_dev WHERE id = ? AND id_developer = ?";
$stmt = mysqli_prepare($mysqli, $query);
mysqli_stmt_bind_param($stmt, "ii", $servico_id, $id_developer);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Verifica se o serviço foi encontrado
if ($row = mysqli_fetch_assoc($result)) {
    $titulo = $row['titulo'];
    $descricao = $row['descricao'];
    $instrucao = $row['instrucao'];
    $categoria = $row['categoria'];
    $valor = $row['valor'];
    $tempo = $row['tempo'];
    $img = $row['img'];
} else {
    echo "Serviço não encontrado ou não pertence a este desenvolvedor.";
    exit;
}

// Função para lidar com o upload da imagem
function uploadImagem($nomeCampo) {
    $diretorio = "assets/img/services/";
    $imagemNome = $_FILES[$nomeCampo]['name'];
    $imagemTmp = $_FILES[$nomeCampo]['tmp_name'];
    $caminhoImagem = $diretorio . $imagemNome;

    // Move a imagem para o diretório especificado
    if (move_uploaded_file($imagemTmp, $caminhoImagem)) {
        return $caminhoImagem;
    } else {
        return null;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se foi enviada uma nova imagem
    if (isset($_FILES["img"]) && !empty($_FILES["img"]["name"])) {
        $caminhoImagem = uploadImagem('img');
        if ($caminhoImagem === null) {
            echo "Erro ao fazer upload da imagem.";
            exit;
        }
    } else {
        $caminhoImagem = $img; // Mantém a imagem atual se nenhuma nova imagem foi enviada
    }

    // Coletar os dados do formulário
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $instrucao = $_POST['instrucao'];
    $categoria = $_POST['categoria'];
    $valor = $_POST['valor'];
    $tempo = $_POST['tempo'];

    // Prepara a consulta para atualizar os dados na tabela
    $query_update = "UPDATE tb_cad_servico_dev SET titulo = ?, descricao = ?, instrucao = ?, categoria = ?, valor = ?, tempo = ?, img = ? WHERE id = ? AND id_developer = ?";
    $stmt_update = mysqli_prepare($mysqli, $query_update);
    mysqli_stmt_bind_param($stmt_update, 'ssssdisii', $titulo, $descricao, $instrucao, $categoria, $valor, $tempo, $caminhoImagem, $servico_id, $id_developer);

    if (mysqli_stmt_execute($stmt_update)) {
        header("Location: dashviewserv.php");
        exit;
    } else {
        echo "Erro ao atualizar o serviço: " . mysqli_error($mysqli);
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
    /* Estilos específicos para o formulário */
    form {
        display: flex;
        flex-direction: column;
    }
    label {
        font-weight: bold;
        margin-bottom: 5px;
    }
    input, select {
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }
    textarea {
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
        resize: vertical;
    }
    button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 3px;
        cursor: pointer;
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
				<a href="dashboard.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Início</span>
				</a>
			</li>
            <li>
                <a href="callings_dev.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Solicitações</span>
                </a>
            </li>
			<li class="active">
				<a href="dash_servicos.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Criar Serviços</span>
				</a>
			</li>
            <li>
				<a href="dashviewserv.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Meus Serviços</span>
				</a>
			</li>
			<li>
				<a href="alterar_dados_dev.php">
					<i class='bx bxs-doughnut-chart' ></i>
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
					<i class='bx bxs-cog' ></i>
					<span class="text">Configurações</span>
				</a>
			</li>
			<li>
				<a href="logout.php" class="logout">
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
					<input type="search" placeholder="Buscar...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
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
					<h1>Cadastro de serviços</h1>
					<ul class="breadcrumb">
						<li>
							<a href="dash_servicos.php">Criar Serviços</a>
						</li>
                        <li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="dashviewserv.php">Meus Serviços</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="dashboard.php">Início</a>
						</li>
					</ul>
				</div>
				<a href="index.php" class="btn-download">
					<i class='bx bx-home' ></i>
					<span class="text">Voltar ao Início</span>
				</a>
			</div>
            <!-- ALTERAR DADOS-->
            </br>
            <div class="container">
                <h1>Criar Serviço</h1>

                <form action="editar_servicos.php?id=<?php echo $servico_id; ?>" method="POST" enctype="multipart/form-data" onsubmit="this.querySelector('button[type=submit]').disabled = true;">
        <label for="titulo">Título do Serviço:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
        
        <label for="descricao">Descrição do Serviço:</label>
        <textarea id="descricao" name="descricao" rows="4" required><?php echo htmlspecialchars($descricao); ?></textarea>
        
        <label for="instrucao">Instruções ao Comprador:</label>
        <textarea id="instrucao" name="instrucao" rows="4"><?php echo htmlspecialchars($instrucao); ?></textarea>

        <label for="categoria">Categoria:</label>
        <select id="categoria" name="categoria" required>
            <option disabled>Selecione a categoria</option>
            <option value="Sites" <?php if ($categoria == 'Sites') echo 'selected'; ?>>Desenvolvimento de sites</option>
            <option value="Mobile" <?php if ($categoria == 'Mobile') echo 'selected'; ?>>App mobile</option>
            <option value="Design" <?php if ($categoria == 'Design') echo 'selected'; ?>>Design gráfico</option>
            <!-- Adicione outras opções de categoria aqui -->
        </select>
        
        <label for="valor">Preço:</label>
        <input type="number" id="valor" name="valor" min="50" value="<?php echo htmlspecialchars($valor); ?>" required>
        
        <label for="tempo">Tempo para Entregar (Dias | mínimo 1 dia):</label>
        <input type="number" id="tempo" name="tempo" min="1" value="<?php echo htmlspecialchars($tempo); ?>" required>
        
        <label for="imagem">Imagem:</label>
        <input type="file" id="imagem" name="img" accept="image/*">
        <?php if ($img): ?>
            <p>Imagem atual: <img src="<?php echo htmlspecialchars($img); ?>" alt="Imagem do serviço" width="100"></p>
        <?php endif; ?>

        <p>Após o envio, o seu serviço será analisado por um de nossos especialistas. Em caso de irregularidade, você receberá um e-mail para ser retificado ou na plataforma com as instruções a respeito do que deverá ser alterado. Caso o serviço enviado seja aprovado, você será notificado e o serviço será publicado.</p>
        
        <label for="termos">Ao criar o seu serviço você concorda com os <a href="#">Termos e Condições</a>.</label>
        <input type="checkbox" id="termos" required>
        <button type="submit">Salvar Alterações</button>
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

</body></html>