<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- ===== Ícones do Iconscout ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="assets/css/login_styles.css">
         
    <title>Formulário de Login e Registro</title> 
</head>
<body>
    
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Informe seu E-mail</span>

                <form method="post" action="send-password-reset.php">
                    <div class="input-field">
                        <input type="text" name="email" placeholder="Digite seu e-mail" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>

                    <div class="radio-field">
                        <input type="radio" id="consumidor" name="tipo_usuario" value="consumidor" checked>
                        <label for="consumidor" class="text">Consumidor</label>
                        <input type="radio" id="desenvolvedor" name="tipo_usuario" value="desenvolvedor">
                        <label for="desenvolvedor" class="text">Desenvolvedor</label>
                    </div>

                
                    <div class="checkbox-text">
                        <div class="input-field button">
                            <input type="submit" name="submit" value="Enviar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./assets/script.js"></script> 
</body>
</html>