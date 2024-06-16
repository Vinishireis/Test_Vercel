<?php
session_start();

if (isset($_SESSION['id'])) {
    include_once('config.php');

    $id_usuario = intval($_SESSION['id']);

    // Verifica se o usuário está na tabela tb_cadastro_developer
    $query_developer = "SELECT id, foto_perfil FROM tb_cadastro_developer WHERE id = ?";
    $stmt_developer = $mysqli->prepare($query_developer);
    $stmt_developer->bind_param("i", $id_usuario);
    $stmt_developer->execute();
    $result_developer = $stmt_developer->get_result();

    if ($result_developer->num_rows === 0) {
        // Se não for developer, redireciona para 404.php ou para onde desejar
        header("Location: 404.php");
        exit;
    }

    // Se chegou aqui, é um developer
    $row = $result_developer->fetch_assoc();
    $id = intval($row['id']);
    $foto_nome = htmlspecialchars($row['foto_perfil'], ENT_QUOTES, 'UTF-8');
    $caminho_imagem = "assets/img/users/$foto_nome";

    // Agora continua com as operações para desenvolvedores
    $query_contratados = "SELECT sc.id, s.titulo AS titulo_servico, s.valor, s.tempo, s.img, sc.data_contratacao, 
                          u.nome AS nome_consumidor, u.sobrenome AS sobrenome_consumidor, sc.status
                          FROM tb_servicos_contratados AS sc
                          INNER JOIN tb_cad_servico_dev AS s ON sc.service_id = s.id
                          INNER JOIN tb_cadastro_users AS u ON sc.user_id = u.id
                          WHERE sc.developer_id = ?";
    $stmt_contratados = $mysqli->prepare($query_contratados);
    $stmt_contratados->bind_param("i", $id_usuario);
    $stmt_contratados->execute();
    $result_contratados = $stmt_contratados->get_result();

    // Agora você pode continuar usando $result_contratados para processar os dados

} else {
    // Se não houver sessão ativa, redireciona para login.php
    header("Location: login.php");
    exit;
}

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

    <title>OnDev Dashboard</title>
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #F9F9F9;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .img-thumbnail {
            max-width: 100px;
            border-radius: 5px;
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
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Início</span>
                </a>
            </li>
            <li class="active">
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
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">Mensagens</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-group'></i>
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
                    <h1>Serviços contratados</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="callings_dev.php">Solicitações</a>
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
            <!-- SERVIÇOS -->
            </br>
            <div class="container">
                <h1>Lista de Serviços Contratados</h1>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Data de Contratação</th>
                            <th>Imagem</th>
                            <th>Título do Serviço</th>
                            <th>Nome do Consumidor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_contratados && $result_contratados->num_rows > 0) : ?>
                            <?php while ($row = $result_contratados->fetch_assoc()) : ?>
                                <?php
                                $data_contratacao_formatada = date('d/m/Y', strtotime($row['data_contratacao']));
                                $caminho_imagem_servico = htmlspecialchars($row['img'], ENT_QUOTES, 'UTF-8');
                                $img_tag = file_exists($caminho_imagem_servico) ?
                                    "<img src=\"$caminho_imagem_servico\" alt=\"Imagem do Serviço\" class=\"img-thumbnail\" style=\"max-width: 100px;\">" :
                                    "Imagem não encontrada.";
                                ?>
                                <tr id="servico-<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <td><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($data_contratacao_formatada, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= $img_tag ?></td>
                                    <td><?= htmlspecialchars($row['titulo_servico'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($row['nome_consumidor'] . ' ' . $row['sobrenome_consumidor'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <select data-id="<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>" data-status="<?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?>" onchange="atualizarStatus(this)">
                                            <option value="Pendente" <?= $row['status'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                                            <option value="Em progresso" <?= $row['status'] == 'Em progresso' ? 'selected' : '' ?>>Em progresso</option>
                                            <option value="Completo" <?= $row['status'] == 'Completo' ? 'selected' : '' ?>>Completo</option>
                                            <option value="Cancelado" <?= $row['status'] == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button onclick="cancelarServico(<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>)" <?= $row['status'] == 'Cancelado' ? 'disabled' : '' ?>>Cancelar Serviço</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7">Nenhum serviço contratado encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    </div>
    </div>

    </main><!-- End main -->

    <script>
        function removerServico(id) {
            if (confirm('Tem certeza de que deseja remover este serviço?')) {
                fetch('assets/php/remove_servico_dev.php?id=' + id, {
                        method: 'GET'
                    })
                    .then(response => response.text())
                    .then(resposta => {
                        if (resposta.includes('Serviço removido com sucesso')) {
                            const linha = document.getElementById('servico-' + id);
                            if (linha) {
                                linha.style.display = 'none';
                            }
                        } else {
                            alert('Erro ao remover o serviço: ' + resposta);
                        }
                    })
                    .catch(error => console.error('Erro:', error));
            }
        }

        function atualizarStatus(selectElement) {
            const id = selectElement.getAttribute('data-id');
            const status = selectElement.value;

            fetch('assets/php/update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(status)
                })
                .then(response => response.text())
                .then(() => {
                    console.log('Status atualizado com sucesso');
                    verificarStatus();
                })
                .catch(error => console.error('Erro:', error));
        }

        function cancelarServico(id) {
            if (confirm('Tem certeza de que deseja cancelar este serviço?')) {
                fetch('assets/php/cancelar_servico.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id=' + encodeURIComponent(id) + '&status=Cancelado'
                    })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            alert('Serviço cancelado com sucesso.');
                            location.reload();
                        } else {
                            alert('Erro: ' + response.message);
                        }
                    })
                    .catch(error => console.error('Erro:', error));
            }
        }

        function verificarStatus() {
            document.querySelectorAll('select[data-status]').forEach(select => {
                const status = select.getAttribute('data-status');
                const id = select.getAttribute('data-id');
                const botaoCancelar = document.querySelector('button[onclick="cancelarServico(' + id + ')"]');
                if (status === 'Cancelado') {
                    select.disabled = true;
                    if (botaoCancelar) {
                        botaoCancelar.disabled = true;
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', verificarStatus);
    </script>

    <script src="assets/js/dashboard.js"></script>
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/lozad/lozad.min.js"></script>
    <script src="assets/libs/device/device.js"></script>
    <script src="assets/libs/spincrement/jquery.spincrement.min.js"></script>

</body>

</html>