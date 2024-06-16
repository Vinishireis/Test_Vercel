<?php

$usuario = 'root';
$senha = '@Vinishireis2005';
$database = 'login_new';
$host = 'localhost';

// Cria a conexão com o banco de dados
$mysqli = new mysqli($host, $usuario, $senha, $database);

// Verifica se houve erros na conexão
if($mysqli->connect_errno) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->connect_error);
}

// Verifica se a conexão foi estabelecida com sucesso
if (!$mysqli->ping()) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->error);
}

return $mysqli;
?>