<?php
$email = $_POST["email"];
$tipo_usuario = $_POST["tipo_usuario"];
$tabela = $tipo_usuario === "desenvolvedor" ? "tb_cadastro_developer" : "tb_cadastro_users";

$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$mysqli = require __DIR__ . "/config.php";

$sql = "UPDATE $tabela
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss", $token_hash, $expiry, $email);
$stmt->execute();

if ($mysqli->affected_rows) {
    $mail = require __DIR__ . "/mailer.php";
    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END
    Clique <a href="http://localhost/ondev_3/reset-password.php?token=$token&tipo_usuario=$tipo_usuario">aqui</a>
    para redefinir sua senha.
    END;

    try {
        $mail->send();
    } catch (Exception $e) {
        echo "Não foi possível enviar a mensagem. Erro do mailer: {$mail->ErrorInfo}";
    }
}

echo "Mensagem enviada, verifique sua caixa de entrada no e-mail.";
?>
