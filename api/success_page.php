<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php'); // Redireciona para a página de login se o usuário não estiver logado
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação Enviada com Sucesso</title>
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Substitua pelo caminho para o seu arquivo CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .success-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-container h1 {
            color: #4CAF50;
            font-size: 24px;
        }
        .success-container p {
            font-size: 18px;
            color: #333;
        }
        .success-container .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .success-container .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h1>Solicitação Enviada com Sucesso!</h1>
        <p>Sua solicitação de contratação foi enviada com sucesso. Em breve, você receberá uma confirmação no seu e-mail.</p>
        <a href="index.php" class="btn">Voltar para a Página Inicial</a> <!-- Substitua pelo caminho da sua página inicial -->
    </div>
</body>
</html>
