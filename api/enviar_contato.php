<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Variáveis
  $nome = $_POST['nome'];
  $telefone = $_POST['telefone'];
  $email = $_POST['email'];
  $mensagem = $_POST['mensagem'];
  $data_envio = date('d/m/Y');
  $hora_envio = date('H:i:s');

    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Servidor SMTP do Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'ondev.org@gmail.com';  // Seu endereço de email
        $mail->Password = 'l i i l k n f d r r f m s fo c';  // Sua senha de email
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configurações do  destinatário
        $mail->addAddress('ondev.org@gmail.com', 'Nome do Destinatário');

        // Conteúdo do email
        $mail->isHTML(true);
        $mail->Subject = 'Formulário de Contato';
        $mail->Body    = "
            
         <html>
      <p><b>Nome: </b>$nome</p>
       <p><b>Telefone: </b>$telefone</p>
      <p><b>E-mail: </b>$email</p>
      <p><b>Mensagem: </b>$mensagem</p>
      <p>Este e-mail foi enviado em <b>$data_envio</b> às <b>$hora_envio</b></p>
    </html>
        ";
        $mail->AltBody = "
            Formulário de Contato\n
            Nome: {$nome}\n
            Telefone: {$telefone}\n
            Email: {$email}\n
            Mensagem:\n{$mensagem}
             Data:\n{$data_envio}
              Hora:\n{$hora_envio}
        ";

        $mail->send();
        echo 'Mensagem enviada com sucesso';
    } catch (Exception $e) {
        echo "A mensagem não pôde ser enviada. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo 'Método de solicitação inválido.';
}
?>
