<?php 
$token = $_GET["token"];
$tipo_usuario = $_GET["tipo_usuario"];
$tabela = $tipo_usuario === "desenvolvedor" ? "tb_cadastro_developer" : "tb_cadastro_users";

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/config.php";

$sql = "SELECT * FROM $tabela WHERE reset_token_hash = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
    die("código não encontrado");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("seu código expirou");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recuperação de senha</title>
    <meta charset="UTF-8">
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #333;
}

form {
    background-color: #fff;
    max-width: 300px;
    margin: 50px auto;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0,0,0,.05);
    border-radius: 5px;
}

label {
    display: block;
    margin-top: 10px;
}

input[type="password"],
input[type="hidden"] {
    width: calc(100% - 20px);
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 3px;
}

button {
   width: calc(100% - 20px);
   background-color:#007bff; 
   color:white; 
   border:none; 
   padding :10px; 
   cursor:pointer;
   border-radius: 3px;
   margin-top: 10px;
}
    </style>
</head>
<body>
    <h1>Redefina sua senha</h1>

    <form method="post" action="process-reset-password.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="hidden" name="tipo_usuario" value="<?= htmlspecialchars($tipo_usuario) ?>">

        <label for="password">Nova senha</label>
        <input type="password" id="password" name="password">

        <label for="password_confirmation">Repita a senha</label>
        <input type="password" id="password_confirmation" name="password_confirmation">

        <button>Enviar</button>
    </form>
</body>
</html>
