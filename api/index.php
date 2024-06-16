<?php
// Inclui o arquivo config.php para obter a conexão com o banco de dados
include 'config.php';

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
	<link rel="alternate" type="application/rss+xml" title="Make Dream Website &raquo; Comments Feed" href="https://template.makedreamwebsite.com/comments/feed/" />
	<link rel="icon" href="assets\img\favicon\logo-oficial.svg" type="image/x-icon">
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
                            <!-- End main menu -->
                        </div>
                    </div>
                </div>
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
            </div>
        </div>
    </nav>
</header>


	<div class="section-bgc intro">
		<div class="intro-item intro-item-type-1" style="background-image: url('assets/img/intro-img1.jpg');">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="intro-content" style="--margin-left: 4rem;">
							<div class="section-heading shm-none">
								<div class="section-subheading">OnDev</div>
								<h1>"#1 em inovação tecnológica" </h1>
								<p class="section-desc">OnDev é uma empresa líder no setor de tecnologia, reconhecida como a número um em inovação. Seus produtos e serviços revolucionários estão moldando o futuro e impulsionando o progresso em diversas áreas, desde a inteligência artificial até a computação em nuvem.</p>
							</div>
							<div class="btn-group intro-btns">
								<a href="services.php" class="btn btn-border btn-with-icon btn-small ripple">
									<span>Serviços</span>
									<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
										<use xlink:href="assets/img/sprite.svg#arrow-right"></use>
									</svg>
								</a>
								<a href="404.php" class="btn btn-border btn-with-icon btn-small ripple">
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
	</div>

	<section class="section">
		<div class="container">
			<div class="row">
				<header class="col-12">
					<div class="section-heading heading-center">
						<div class="section-subheading">Áreas que atendemos</div>
						<h2>Nossos Serviços</h2>
					</div>
				</header>
				<div class="col-lg-4 col-md-6 col-12 item">
					<a href="#!" class="iitem item-style iitem-hover">
						<div class="iitem-icon">
							<i class="material-icons material-icons-outlined md-48">developer_mode</i>
						</div>
						<div class="iitem-icon-bg">
							<i class="material-icons material-icons-outlined">developer_mode</i>
						</div>
						<h3 class="iitem-heading item-heading-large">Desenvolvimento Web</h3>
						<div class="iitem-desc">O Desenvolvimento Web da OnDev é uma fusão de criatividade e tecnologia, resultando em experiências online excepcionais.</div>
					</a>
				</div>
				<div class="col-lg-4 col-md-6 col-12 item">
					<a href="#!" class="iitem item-style iitem-hover">
						<div class="iitem-icon">
							<i class="material-icons material-icons-outlined md-48">mobile_friendly</i>
						</div>
						<div class="iitem-icon-bg">
							<i class="material-icons material-icons-outlined">mobile_friendly</i>
						</div>
						<h3 class="iitem-heading item-heading-large">Aplicações Mobile</h3>
						<div class="iitem-desc">A aplicação móvel da OnDev é uma ferramenta poderosa que oferece conveniência e eficiência aos seus usuários.</div>
					</a>
				</div>
				<div class="col-lg-4 col-md-12 col-12 item">
					<a href="#!" class="iitem item-style iitem-hover">
						<div class="iitem-icon">
							<i class="material-icons material-icons-outlined md-48">cloud_download</i>
						</div>
						<div class="iitem-icon-bg">
							<i class="material-icons material-icons-outlined">cloud_download</i>
						</div>
						<h3 class="iitem-heading item-heading-large">Desenvolvimento Cloud</h3>
						<div class="iitem-desc">O Desenvolvimento Cloud da OnDev é uma abordagem inovadora e centrada no cliente para criar e gerenciar aplicativos e serviços na nuvem.</div>
					</a>
				</div>
				<div class="section-footer col-12 section-footer-animate">
					<div class="btn-group align-items-center justify-content-center">
						<a href="services.php" class="btn btn-with-icon btn-w240 ripple">
							<span>Visualizar todos os Serviços</span>
							<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
								<use xlink:href="assets/img/sprite.svg#arrow-right"></use>
							</svg>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section section-bgc">
		<div class="container">
			<div class="row litems">
				<header class="col-12">
					<div class="section-heading heading-center">
						<div class="section-subheading">Algumas Razões</div>
						<h2>Porque Escolher Nós</h2>
					</div>
				</header>
				<div class="col-lg-4 col-md-6 col-12 litem">
					<div class="ini">
						<div class="ini-count">01</div>
						<div class="ini-info">
							<h3 class="ini-heading item-heading-large">Alta Qualidade <br> em Prestadores</h3>
							<div class="ini-desc">
								<p>We use top-notch hardware to develop the most efficient apps for our customers</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 col-12 litem">
					<div class="ini">
						<div class="ini-count">02</div>
						<div class="ini-info">
							<h3 class="ini-heading item-heading-large">Dedicado 24 Horas <br> Com Suporte </h3>
							<div class="ini-desc">
								<p>You can rely on our 24/7 tech support that will gladly solve any app issue you may have.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 col-12 litem">
					<div class="ini">
						<div class="ini-count">03</div>
						<div class="ini-info">
							<h3 class="ini-heading item-heading-large">Colaboradores Treinados</h3>
							<div class="ini-desc">
								<p>If you are not satisfied with our apps, we will return your money in the first 30 days.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 col-12 litem">
					<div class="ini">
						<div class="ini-count">04</div>
						<div class="ini-info">
							<h3 class="ini-heading item-heading-large">Agíl e Rápido <br> </h3>
							<div class="ini-desc">
								<p>This type of approach to our work helps our specialists to quickly develop better apps.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 col-12 litem">
					<div class="ini">
						<div class="ini-count">05</div>
						<div class="ini-info">
							<h3 class="ini-heading item-heading-large">Desenvolvimento em Apps <br> com especialistas</h3>
							<div class="ini-desc">
								<p>We also develop free apps that can be downloaded online without any payments.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 col-12 litem">
					<div class="ini">
						<div class="ini-count">06</div>
						<div class="ini-info">
							<h3 class="ini-heading item-heading-large">Alto Nível <br> em Serviços</h3>
							<div class="ini-desc">
								<p>All our products have high usability allowing users to easily operate the apps.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section section-without-padding-bottom">
		<div class="container">
			<div class="row items spincrement-container">
				<div class="col-xl-3 col-md-6 col-12 item">
					<div class="counter-min">
						<div class="counter-min-block">
							<div class="counter-min-ico">
								<i class="material-icons material-icons-outlined md-36">history</i>
							</div>
							<div class="counter-min-numb spincrement" data-from="0" data-to="2">0</div>
						</div>
						<div class="counter-min-info">
							<h4 class="counter-min-heading">Anos de Experiencia</h4>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-md-6 col-12 item">
					<div class="counter-min">
						<div class="counter-min-block">
							<div class="counter-min-ico">
								<i class="material-icons material-icons-outlined md-36">chat</i>
							</div>
							<div class="counter-min-numb spincrement" data-from="0" data-to="40">0</div>
						</div>
						<div class="counter-min-info">
							<h4 class="counter-min-heading">Reviews</h4>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-md-6 col-12 item">
					<div class="counter-min">
						<div class="counter-min-block">
							<div class="counter-min-ico">
								<i class="material-icons material-icons-outlined md-36">assignment_ind</i>
							</div>
							<div class="counter-min-numb spincrement" data-from="0" data-to="160">0</div>
						</div>
						<div class="counter-min-info">
							<h4 class="counter-min-heading">Colaboradores</h4>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-md-6 col-12 item">
					<div class="counter-min">
						<div class="counter-min-block">
							<div class="counter-min-ico">
								<i class="material-icons material-icons-outlined md-36">phonelink_setup</i>
							</div>
							<div class="counter-min-numb"><span class="spincrement" data-from="0" data-to="2">0</span>K</div>
						</div>
						<div class="counter-min-info">
							<h4 class="counter-min-heading">Apps Desenvolvidos</h4>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="row">
				<header class="col-12">
					<div class="section-heading heading-center">
						<div class="section-subheading">Reviews dos nossos clientes</div>
						<h2>O que as pessoas dizem</h2>
					</div>
				</header>
				<div class="col-lg-4 col-md-6 col-12 item">
					<div class="reviews-item item-style">
						<div class="reviews-item-header">
							<div class="reviews-item-img">
								<img data-src="assets/img/auth-img-1.jpg" class="img-cover lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
							</div>
							<div class="reviews-item-info">
								<h4 class="reviews-item-name item-heading">Catherine Williams</h4>
								<div class="reviews-item-position">Regular client</div>
							</div>
						</div>
						<div class="reviews-item-text">
							<p>"Os serviços da OnDev são simplesmente excepcionais! Como cliente há mais de dois anos, posso afirmar com confiança que sua abordagem proativa e orientada para resultados é incomparável. Desde o primeiro contato até a entrega final, sua equipe demonstra um profissionalismo exemplar e um compromisso inabalável com a satisfação do cliente."</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 col-12 item">
					<div class="reviews-item item-style">
						<div class="reviews-item-header">
							<div class="reviews-item-img">
								<img data-src="assets/img/auth-img-2.jpg" class="img-cover lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
							</div>
							<div class="reviews-item-info">
								<h4 class="reviews-item-name item-heading">Rupert Wood</h4>
								<div class="reviews-item-position">Regular client</div>
							</div>
						</div>
						<div class="reviews-item-text">
							<p>A OnDev não apenas entrega soluções de alta qualidade, mas também se destaca por sua capacidade de entender as necessidades específicas de cada cliente e adaptar suas soluções de acordo. Seja desenvolvendo aplicativos móveis inovadores ou implementando sistemas de inteligência artificial avançados, a OnDev sempre supera as expectativas..</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-12 col-12 item">
					<div class="reviews-item item-style">
						<div class="reviews-item-header">
							<div class="reviews-item-img">
								<img data-src="assets/img/auth-img-3.jpg" class="img-cover lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
							</div>
							<div class="reviews-item-info">
								<h4 class="reviews-item-name item-heading">Samantha Brown</h4>
								<div class="reviews-item-position">Regular client</div>
							</div>
						</div>
						<div class="reviews-item-text">
							<p>Em resumo, se você está procurando uma empresa de tecnologia que ofereça resultados tangíveis e um serviço ao cliente excepcional, não procure mais. A OnDev é a escolha certa para impulsionar o sucesso de seus projetos e alcançar seus objetivos de negócios."</p>
						</div>
					</div>
				</div>
				<footer class="section-footer col-12 section-footer-animate">
					<div class="btn-group align-items-center justify-content-center">
						<a href="#!" class="btn btn-with-icon btn-w240 ripple">
							<span>Visualizar todas as Reviews</span>
							<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
								<use xlink:href="assets/img/sprite.svg#arrow-right"></use>
							</svg>
						</a>
					</div>
				</footer>
			</div>
		</div>
	</section>

	<section class="section section-bgc">
		<div class="container">
			<div class="row items">
				<header class="col-12">
					<div class="section-heading heading-center">
						<div class="section-subheading">Mais Informações</div>
						<h2>Últimas Notícias</h2>
					</div>
				</header>
				<div class="col-lg-4 col-md-6 col-12 item">
					<article class="news-item item-style">
						<a href="news-post.php" class="news-item-img el">
							<img data-src="assets/img/news-img-1.jpg" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
						</a>
						<div class="news-item-info">
							<div class="news-item-date">07/01/2021</div>
							<h3 class="news-item-heading item-heading">
								<a href="news-post.php" title="Benefits Of Async/Await">Benefits Of Async/Await</a>
							</h3>
							<div class="news-item-desc">
								<p>Asynchronous functions are a good and bad thing in JavaScript.</p>
							</div>
						</div>
					</article>
				</div>
				<div class="col-lg-4 col-md-6 col-12 item">
					<article class="news-item item-style">
						<a href="news-post.php" class="news-item-img el">
							<img data-src="assets/img/news-img-2.jpg" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
						</a>
						<div class="news-item-info">
							<div class="news-item-date">05/01/2021</div>
							<h3 class="news-item-heading item-heading">
								<a href="news-post.php" title="Key Considerations Of IPaaS">Key Considerations Of IPaaS</a>
							</h3>
							<div class="news-item-desc">
								<p>Digital transformation requires cloud appropriate adoption</p>
							</div>
						</div>
					</article>
				</div>
				<div class="col-lg-4 col-md-6 col-12 item">
					<article class="news-item item-style">
						<a href="news-post.php" class="news-item-img el">
							<img data-src="assets/img/news-img-3.jpg" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
						</a>
						<div class="news-item-info">
							<div class="news-item-date">01/01/2021</div>
							<h3 class="news-item-heading item-heading">
								<a href="news-post.php" title="Hibernate Query Language">Hibernate Query Language</a>
							</h3>
							<div class="news-item-desc">
								<p>In this tutorial, we will discuss the Hibernate Query Language. </p>
							</div>
						</div>
					</article>
				</div>
				<footer class="section-footer col-12 item section-footer-animate">
					<div class="btn-group align-items-center justify-content-center">
						<a href="news.php" class="btn btn-with-icon btn-w240 ripple">
							<span>Visualizar todas Notícias</span>
							<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
								<use xlink:href="assets/img/sprite.svg#arrow-right"></use>
							</svg>
						</a>
					</div>
				</footer>
			</div>
		</div>
	</section>

	</div>

	<div class="bff">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="bff-container">
						<p>OnDev <br class="d-sm-none"> Conheça os nossos</p>
						<div class="btn-group justify-content-center justify-content-md-start">
							<a href="services.php" class="btn btn-border btn-with-icon btn-small ripple">
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