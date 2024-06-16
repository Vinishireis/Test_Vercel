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
    <link rel="icon" href="assets\img\favicon\logo-oficial.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/bootstrap-grid.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-600.woff2" as="font" type="font/woff2" crossorigin>

    <!--=============== CSS ===============-->
    <link rel="preload" href="assets/fonts/material-icons/material-icons.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/material-icons/material-icons-outlined.woff2" as="font" type="font/woff2" crossorigin>

    <title>OnDev Política de Privacidade</title>
</head>

<body>

    <main class="main">

        <div class="main-inner">

            <!-- Begin mobile main menu -->
            <nav class="mmm">
                <div class="mmm-content">
                    <ul class="mmm-list">
                        <li>
                            <a href="index.php">Início</a>
                        </li>
                        <li>
                            <a href="about-us.php"> Sobre Nós</a>
                        </li>
                        <li>
                            <a href="services.php">Serviços</a>
                        </li>
                        <li>
                            <a href="plans.php">Planos</a>
                        </li>
                        <li>
                            <a href="news.php">Novidades</a>
                        </li>
                        <li>
                            <a href="contacts.php">Contato</a>
                        </li>
                        <li>
                            <div class="btn-group intro-btns">
                                <a href="login.php" class="btn btn-border btn-with-icon btn-small ripple">
                                    <span>Login</span>
                                    <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
                                        <use xlink:href="assets/img/sprite.svg#arrow-right"></use>
                                    </svg>
                                </a>
                                <a href="login_colaborator.php" class="btn btn-border btn-with-icon btn-small ripple">
                                    <span>Prestador</span>
                                    <svg class="btn-icon-right" viewBox="0 0 13 9" width="14" height="9">
                                        <use xlink:href="assets/img/sprite.svg#arrow-right"></use>
                                    </svg>
                                </a>
                            </div>
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
                                    <a href="index.php">Inicio</a>
                                    <i class="material-icons md-18">chevron_right</i>
                                </li>
                                <li>Política de Privacidade</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav><!-- End bread crumbs -->
            <h2><strong>Política de Privacidade da OnDev</strong></h2>
            <p><strong>Data Efetiva:</strong> 14 de março de 2024</p>
            <p>A OnDev ("nós", "nosso" ou "nossa") opera o website (o "Serviço").</p>
            <p>Esta página informa sobre nossas políticas referentes à coleta, uso e divulgação de dados pessoais quando você utiliza nosso Serviço e sobre as escolhas que você possui associadas a esses dados. Nossa Política de Privacidade para a OnDev é criada com a ajuda de.</p>

            <h3>Coleta e Uso de Informações</h3>
            <h4>Tipos de Dados Coletados</h4>

            <h5>Dados Pessoais</h5>
            <p>Ao utilizar nosso Serviço, podemos solicitar que você nos forneça certas informações pessoalmente identificáveis que podem ser usadas para contatá-lo ou identificá-lo ("Dados Pessoais"). As informações pessoalmente identificáveis podem incluir, mas não estão limitadas a:</p>
            <ul>
                <li>Endereço de e-mail</li>
                <li>Endereço, Estado, Província, Código Postal, Cidade</li>
                <li>Cookies e Dados de Utilização</li>
            </ul>

            <h5>Dados de Utilização</h5>
            <p>Também podemos coletar informações sobre como o Serviço é acessado e utilizado ("Dados de Utilização"). Estes Dados de Utilização podem incluir informações como o endereço de protocolo de Internet do seu computador (por exemplo, endereço IP), tipo de navegador, versão do navegador, as páginas do nosso Serviço que você visita, o horário e data da sua visita, o tempo gasto nessas páginas, identificadores de dispositivo exclusivos e outros dados de diagnóstico.</p>

            <h5>Dados de Rastreamento e Cookies</h5>
            <p>Utilizamos cookies e tecnologias de rastreamento similares para rastrear a atividade em nosso Serviço e armazenar certas informações. Os cookies são arquivos com pequena quantidade de dados que podem incluir um identificador anônimo exclusivo. Os cookies são enviados para o seu navegador a partir de um site e armazenados no seu dispositivo. Outras tecnologias de rastreamento também utilizadas são beacons, tags e scripts para coletar e rastrear informações e para melhorar e analisar nosso Serviço. Você pode instruir o seu navegador para recusar todos os cookies ou para indicar quando um cookie está sendo enviado. No entanto, se você não aceitar cookies, poderá não ser capaz de utilizar algumas partes do nosso Serviço.</p>

            <h5>Exemplos de Cookies que utilizamos:</h5>
            <ul>
                <li><strong>Cookies de Sessão:</strong> Utilizamos Cookies de Sessão para operar nosso Serviço.</li>
                <li><strong>Cookies de Preferências:</strong> Utilizamos Cookies de Preferências para lembrar suas preferências e diversas configurações.</li>
                <li><strong>Cookies de Segurança:</strong> Utilizamos Cookies de Segurança para fins de segurança.</li>
            </ul>

            <h3>Uso de Dados</h3>
            <p>A OnDev utiliza os dados coletados para diversos fins:</p>
            <ul>
                <li>Para fornecer e manter o Serviço</li>
                <li>Para notificá-lo sobre mudanças em nosso Serviço</li>
                <li>Para permitir que você participe de recursos interativos do nosso Serviço quando você optar por fazê-lo</li>
                <li>Para fornecer suporte ao cliente</li>
                <li>Para fornecer análises ou informações valiosas para que possamos melhorar o Serviço</li>
                <li>Para monitorar o uso do Serviço</li>
                <li>Para detectar, prevenir e resolver problemas técnicos</li>
            </ul>

            <h3>Transferência de Dados</h3>
            <p>Suas informações, incluindo Dados Pessoais, podem ser transferidas para — e mantidas em — computadores localizados fora do seu estado, província, país ou outra jurisdição governamental onde as leis de proteção de dados podem ser diferentes das da sua jurisdição.</p>
            <p>Se você estiver localizado fora dos Estados Unidos e optar por fornecer informações para nós, por favor, esteja ciente de que transferimos os dados, incluindo Dados Pessoais, para os Estados Unidos e os processamos lá. Seu consentimento para esta Política de Privacidade seguido de seu envio de tais informações representa sua concordância com essa transferência.</p>
            <p>A OnDev tomará todas as medidas razoavelmente necessárias para garantir que seus dados sejam tratados de forma segura e de acordo com esta Política de Privacidade e que nenhuma transferência de seus Dados Pessoais ocorra para uma organização ou país a menos que existam controles adequados em vigor, incluindo a segurança de seus dados e outras informações pessoais.</p>

            <h3>Divulgação de Dados</h3>
            <h4>Requisitos Legais</h4>
            <p>A OnDev pode divulgar os seus Dados Pessoais na crença de boa fé de que tal ação é necessária para:</p>
            <ul>
                <li>Cumprir uma obrigação legal</li>
                <li>Proteger e defender os direitos ou propriedade da OnDev</li>
                <li>Prevenir ou investigar possíveis irregularidades relacionadas com o Serviço</li>
                <li>Proteger a segurança pessoal dos usuários do Serviço ou do público</li>
                <li>Proteger contra a responsabilidade legal</li>
            </ul>

            <h3>Segurança dos Dados</h3>
            <p>A segurança dos seus dados é importante para nós, mas lembre-se de que nenhum método de transmissão pela Internet, ou método de armazenamento eletrônico é 100% seguro. Embora nos esforcemos para utilizar meios comercialmente aceitáveis para proteger seus Dados Pessoais, não podemos garantir sua segurança absoluta.</p>

            <h3>Prestadores de Serviço</h3>
            <p>Podemos empregar empresas e indivíduos terceirizados para facilitar o nosso Serviço ("Prestadores de Serviço"), para fornecer o Serviço em nosso nome, para realizar serviços relacionados com o Serviço ou para nos ajudar a analisar como o nosso Serviço é utilizado.</p>
            <p>Esses terceiros têm acesso aos seus Dados Pessoais apenas para realizar essas tarefas em nosso nome e são obrigados a não divulgar ou utilizar essas informações para qualquer outra finalidade.</p>

            <h4>Análises</h4>
            <p>Podemos usar fornecedores de serviços terceirizados para monitorar e analisar o uso do nosso Serviço.</p>
            <ul>
                <li><strong>Clicky</strong></li>
                <li><strong>Statcounter</strong></li>
            </ul>

            <h3>Links para Outros Sites</h3>
            <p>Nosso Serviço pode conter links para outros sites que não são operados por nós. Se você clicar em um link de terceiros, será direcionado para o site desse terceiro. Recomendamos fortemente que você reveja a Política de Privacidade de todos os sites que você visita.</p>
            <p>Não temos controle sobre e não assumimos responsabilidade pelo conteúdo, políticas de privacidade ou práticas de quaisquer sites ou serviços de terceiros.</p>

            <h3>Privacidade de Crianças</h3>
            <p>Nosso Serviço não se dirige a pessoas com menos de 18 anos ("Crianças").</p>
            <p>Não coletamos intencionalmente informações de identificação pessoal de menores de 18 anos. Se você é pai/mãe ou responsável legal e está ciente de que seu(s) filho(s) nos forneceu(ram) Dados Pessoais, entre em contato conosco. Se ficarmos cientes de que coletamos Dados Pessoais de crianças sem verificação do consentimento dos pais, tomamos medidas para remover essas informações de nossos servidores.</p>

            <h3>Alterações a Esta Política de Privacidade</h3>
            <p>Podemos atualizar nossa Política de Privacidade de tempos em tempos. Iremos notificá-lo sobre quaisquer alterações publicando a nova Política de Privacidade nesta página.</p>
            <p>Recomendamos que você revise esta Política de Privacidade periodicamente para quaisquer alterações. As alterações a esta Política de Privacidade são efetivas quando são publicadas nesta página.</p>

            <h3>Contate-Nos</h3>
            <p>Se você tiver alguma dúvida sobre esta Política de Privacidade, entre em contato conosco:</p>
            <ul>
                <li>Por e-mail: mail@example.com</li>
            </ul>

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

</body>

</html>