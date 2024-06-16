<?php
session_start();

// Inclua o arquivo de configuração do banco de dados
include_once('./login_new/config.php');

// Inicialize a resposta como um array
$response = array('success' => false, 'message' => '', 'dev_id' => null, 'user_id' => null);

// Verifique se os dados esperados foram enviados
if (isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];

    // Recupera o ID do desenvolvedor do serviço
    $query = "SELECT id_developer FROM tb_cad_servico_dev WHERE id = ?";
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, "i", $service_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $response['dev_id'] = $row['id_developer'];
        $response['success'] = true;

        // Se o usuário estiver logado, adicione o ID do usuário na resposta
        if (isset($_SESSION['id'])) {
            $response['user_id'] = $_SESSION['id'];
        }
    } else {
        $response['message'] = 'Serviço não encontrado.';
    }
} else {
    $response['message'] = 'Dados do serviço não fornecidos.';
}

// Retorna a resposta como JSON
echo json_encode($response);
?>