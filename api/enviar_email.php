<?php
session_start();
include_once('config.php');
$mail = include 'mailer.php';

// Configuração para exibição de erros (para desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar se todos os campos necessários foram recebidos
    $required_fields = ['nome', 'contato', 'informacoes', 'service_id', 'user_id', 'developer_id'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "O campo {$field} é obrigatório."]);
            exit;
        }
    }

    // Recuperar os dados recebidos do formulário
    $nome = $_POST['nome'];
    $contato = $_POST['contato'];
    $informacoes = $_POST['informacoes'];
    $service_id = $_POST['service_id'];
    $user_id = $_POST['user_id'];
    $developer_id = $_POST['developer_id'];

    // Recuperar o email do desenvolvedor
    $sql = "SELECT email FROM tb_cadastro_developer WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $developer_id);
    $stmt->execute();
    $stmt->bind_result($email_developer);
    $stmt->fetch();
    $stmt->close();

    // Recuperar o email do consumidor
    $sql_consumer = "SELECT email FROM tb_cadastro_users WHERE id = ?";
    $stmt_consumer = $mysqli->prepare($sql_consumer);
    $stmt_consumer->bind_param("i", $user_id);
    $stmt_consumer->execute();
    $stmt_consumer->bind_result($email_consumer);
    $stmt_consumer->fetch();
    $stmt_consumer->close();

    // Recuperar detalhes do serviço
    $sql_service = "SELECT titulo, descricao, valor, tempo, img FROM tb_cad_servico_dev WHERE id = ?";
    $stmt_service = $mysqli->prepare($sql_service);
    $stmt_service->bind_param("i", $service_id);
    $stmt_service->execute();
    $stmt_service->bind_result($titulo_servico, $descricao_servico, $valor_servico, $tempo_servico, $img_servico);
    $stmt_service->fetch();
    $stmt_service->close();

    if ($email_developer && $email_consumer) {
        try {
            // Ajuste para UTF-8 no assunto e corpo do e-mail
            $mail->CharSet = 'UTF-8';

            // Definir a URL base
            $base_url = "http://192.168.1.108/ondev_3/";

            // Caminho completo da imagem do serviço
            $img_servico_path = $base_url . $img_servico;

            // Verificar se a imagem é acessível
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . parse_url($img_servico_path, PHP_URL_PATH))) {
                echo json_encode(['success' => false, 'message' => 'Imagem do serviço não encontrada no servidor.']);
                exit;
            }

            // Content-ID único para a imagem
            $cid = 'service_image_' . md5($img_servico_path);

            // Configurações do email para o desenvolvedor
            $mail->setFrom('ondev.org@gmail.com', 'Ondev');
            $mail->addAddress($email_developer);
            $mail->Subject = 'Solicitação de Contratação de Serviço';
            $mail->isHTML(true);
            $mail->Body = "
            <html>
            <head>
                <style>
                    .email-container { font-family: Arial, sans-serif; }
                    .email-header { background-color: #f8f8f8; padding: 10px; text-align: center; }
                    .email-body { margin: 20px; }
                    .service-details { border: 1px solid #ddd; padding: 10px; margin-top: 20px; }
                    .service-image { max-width: 100%; height: auto; }
                </style>
            </head>
            <body class='email-container'>
                <div class='email-header'>
                    <h1>Nova Solicitação de Contratação de Serviço</h1>
                </div>
                <div class='email-body'>
                    <p>Você recebeu uma nova solicitação de contratação:</p>
                    <div class='service-details'>
                        <h2>{$titulo_servico}</h2>
                        <p><strong>Descrição:</strong> {$descricao_servico}</p>
                        <p><strong>Valor:</strong> R$ " . number_format($valor_servico, 2, ',', '.') . "</p>
                        <p><strong>Tempo estimado:</strong> {$tempo_servico} dias</p>
                        <p><strong>Informações adicionais:</strong> {$informacoes}</p>
                        <!-- Adicione os estilos inline para a imagem -->
                        <img src='cid:{$cid}' alt='Imagem do serviço' class='service-image' style='max-width: 100%; height: auto; display: block; margin: 0 auto;'>
                    </div>
                    <p><strong>Cliente:</strong> {$nome}</p>
                    <p><strong>Contato:</strong> {$contato}</p>
                </div>
            </body>
            </html>";

            // Anexar a imagem ao email
            $mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'] . parse_url($img_servico_path, PHP_URL_PATH), $cid);

            $mail->send();

            // Email de confirmação para o consumidor
            $mail->clearAddresses();
            $mail->addAddress($email_consumer);
            $mail->Subject = 'Confirmação de Solicitação de Serviço';
            $mail->isHTML(true);
            $mail->Body = "
            <html>
            <head>
                <style>
                    .email-container { font-family: Arial, sans-serif; }
                    .email-header { background-color: #f8f8f8; padding: 10px; text-align: center; }
                    .email-body { margin: 20px; }
                    .service-details { border: 1px solid #ddd; padding: 10px; margin-top: 20px; }
                    .service-image { max-width: 100%; height: auto; }
                </style>
            </head>
            <body class='email-container'>
                <div class='email-header'>
                    <h1>Confirmação de Solicitação de Serviço</h1>
                </div>
                <div class='email-body'>
                    <p>Sua solicitação de contratação foi enviada com sucesso. Aqui estão os detalhes:</p>
                    <div class='service-details'>
                        <h2>{$titulo_servico}</h2>
                        <p><strong>Descrição:</strong> {$descricao_servico}</p>
                        <p><strong>Valor:</strong> R$ " . number_format($valor_servico, 2, ',', '.') . "</p>
                        <p><strong>Tempo estimado:</strong> {$tempo_servico} dias</p>
                        <img src='cid:{$cid}' alt='Imagem do serviço' class='service-image' style='max-width: 100%; height: auto; display: block; margin: 0 auto;'>
                    </div>
                    <p>Em breve, entraremos em contato para mais detalhes.</p>
                </div>
            </body>
            </html>";

            // Anexar a imagem ao email de confirmação também
            $mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'] . parse_url($img_servico_path, PHP_URL_PATH), $cid);

            $mail->send();

            // Insere os dados na tabela de serviços contratados com status "Pendente"
            $query = "INSERT INTO tb_servicos_contratados (service_id, user_id, developer_id, data_contratacao, status) VALUES (?, ?, ?, NOW(), 'pendente')";
            $stmt = mysqli_prepare($mysqli, $query);
            mysqli_stmt_bind_param($stmt, 'iii', $service_id, $user_id, $developer_id);

            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'message' => 'Solicitação enviada com sucesso e registrada no banco de dados.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Solicitação enviada com sucesso, mas ocorreu um erro ao registrar no banco de dados.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Erro ao enviar a solicitação. Mailer Error: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Desenvolvedor ou consumidor não encontrado.']);
}
} 
else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitação inválido.']);
}

$mysqli->close();
?>