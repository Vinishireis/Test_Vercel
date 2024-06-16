<?php
$token = $_POST["token"];
$tipo_usuario = $_POST["tipo_usuario"];
$tabela = $tipo_usuario === "desenvolvedor" ? "tb_cadastro_developer" : "tb_cadastro_users";

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "config.php";

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
    die("o código expirou");
}

if (strlen($_POST["password"]) < 8) {
    die("A senha deve conter pelo menos 8 caracteres");
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    die("A senha deve conter pelo menos uma letra maiuscula");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("A senha deve conter pelo menos um número");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("As senhas devem corresponder");
}

$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE $tabela
        SET password = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $password, $user["id"]);
$stmt->execute();

echo "Senha atualizada. Agora você pode fazer login.";
?>
