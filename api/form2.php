<?php
if (isset($_POST['submit'])) {
    include_once('config.php');

    // Função para lidar com o upload da imagem
    function uploadImagem($nomeCampo)
    {
        $diretorio = "../assets/img/users/";
        $imagemNome = $_FILES[$nomeCampo]['name'];
        $imagemTmp = $_FILES[$nomeCampo]['tmp_name'];
        $caminhoImagem = $diretorio . $imagemNome;

        // Verifica se o diretório existe, se não, cria o diretório
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0777, true);
        }

        // Move a imagem para o diretório especificado
        if (move_uploaded_file($imagemTmp, $caminhoImagem)) {
            return $caminhoImagem;
        } else {
            return null;
        }
    }

    // Verificar se foi enviada uma imagem
    if (isset($_FILES["foto_perfil"]) && !empty($_FILES["foto_perfil"]["name"])) {
        // Verificar se os campos de e-mail e senha correspondem aos campos de confirmação
        $email = $_POST['email'];
        $check_email = $_POST['check-email'];
        $password = $_POST['password'];
        $check_password = $_POST['check-password'];

        if ($email !== $check_email || $password !== $check_password) {
            echo "Os campos de e-mail ou senha não correspondem.";
            exit;
        }

        // Verifica se o campo tipo_usuario foi marcado
        if (isset($_POST['tipo_usuario']) && !empty($_POST['tipo_usuario'])) {
            $tipo_usuario = $_POST['tipo_usuario'][0]; // Assume-se que apenas uma opção será selecionada

            // Determina a tabela onde os dados serão inseridos baseado no tipo de usuário
            $tabela = ($tipo_usuario == 'consumidor') ? 'tb_cadastro_users' : 'tb_cadastro_developer';

            // Verificar se o e-mail já está em uso na tabela correspondente
            $check_email_query = "SELECT * FROM $tabela WHERE email = '$email'";
            $check_email_result = mysqli_query($mysqli, $check_email_query);
            if (mysqli_num_rows($check_email_result) > 0) {
                echo "O e-mail já está em uso nesta tabela.";
                exit;
            }

            // Verificação e processamento do upload da imagem
            $caminhoImagem = uploadImagem('foto_perfil');

            // Verificar se houve erro no upload da imagem
            if ($caminhoImagem === null) {
                echo "Erro ao fazer upload da imagem.";
                exit;
            }

            // Obter apenas o nome do arquivo da imagem
            $nomeImagem = basename($caminhoImagem);

            // Hash da senha usando password_hash()
            $senha_hash = password_hash($password, PASSWORD_DEFAULT);

            // Obter os demais dados do formulário
            $nome = $_POST['nome'];
            $sobrenome = $_POST['sobrenome'];
            $cpf = $_POST['cpf'];
            $data_nasc = $_POST['data_nasc'];
            $genero = $_POST['genero'];
            $ddd = $_POST['ddd'];
            $telefone = $_POST['telefone'];
            $cep = $_POST['cep'];
            $rua = $_POST['rua'];
            $numero = $_POST['numero'];
            $complemento = $_POST['complemento'];
            $bairro = $_POST['bairro'];
            $cidade = $_POST['cidade'];
            $estado = isset($_POST['estado']) ? $_POST['estado'] : null;

            // Validação da data de nascimento
            if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $data_nasc)) {
                echo "Formato de data inválido.";
                exit;
            }

            // Trunca o telefone para garantir que tenha no máximo 15 caracteres
            if (strlen($telefone) > 15) {
                $telefone = substr($telefone, 0, 15);
            }

            // Insere os dados na tabela correspondente
            $result = mysqli_prepare($mysqli, "INSERT INTO $tabela(nome, sobrenome, cpf, data_nasc, genero, ddd, telefone, email, password, cep, rua, numero, complemento, bairro, cidade, estado, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Verifica se a preparação da consulta foi bem-sucedida
            if ($result) {
                // Vincula os parâmetros à consulta preparada
                mysqli_stmt_bind_param($result, "sssssssssssssssss", $nome, $sobrenome, $cpf, $data_nasc, $genero, $ddd, $telefone, $email, $senha_hash, $cep, $rua, $numero, $complemento, $bairro, $cidade, $estado, $nomeImagem);

                // Executa a consulta preparada
                $executed = mysqli_stmt_execute($result);

                // Verifica se a inserção foi bem-sucedida
                if ($executed) {
                    // Armazena o nome da imagem na sessão
                    $_SESSION['foto_perfil'] = $nomeImagem;

                    // Redireciona para a página de login
                    header('Location: login.php');
                    exit; // Certifique-se de sair do script após o redirecionamento
                } else {
                    echo "Erro ao inserir dados no banco de dados.";
                }
            } else {
                echo "Erro ao preparar a declaração SQL.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== REMIXICONS ===============-->
    <link rel="icon" href="assets\img\favicon\logo-oficial.svg" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/bootstrap-grid.css">
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/source-sans-pro-v21-latin/source-sans-pro-v21-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="assets/fonts/montserrat-v25-latin/montserrat-v25-latin-600.woff2" as="font" type="font/woff2" crossorigin>

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="assets/css/form2_styles.css">

    <!-- ==== Link CSS Styles ==== -->
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/alerts-css@1.0.2/assets/css/alerts-css.min.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.0.10/css/all.css'>


    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Formulário de Cadastro</title>
</head>

<body>
    <!-- Seu formulário -->

    <div class="container-full">
        <header>Cadastre-se</header>

        <form method="post" action="form2.php" enctype="multipart/form-data"> <!-- Adicionado enctype para suportar upload de arquivos -->
            <div class="form first">
                <div class="details personal">
                    <span class="title">Detalhes Pessoais</span>

                    <div class="fields">
                        <div class="input-field">
                            <label>Nome</label>
                            <input type="text" name="nome" placeholder="Insira o seu primeiro nome" required>
                        </div>
                        <div class="input-field">
                            <label>Sobrenome</label>
                            <input type="text" name="sobrenome" placeholder="Insira o seu sobrenome" required>
                        </div>

                        <div class="input-field">
                            <label>Data de Nascimento</label>
                            <input type="date" name="data_nasc" placeholder="Insira a data de nascimento" required>
                        </div>

                        <div class="input-field">
                            <label>CPF</label>
                            <input type="text" name="cpf" placeholder="Insira o seu CPF" required>
                        </div>

                        <div class="input-field">
                            <label for="genero">Gênero</label>
                            <select id="genero" name="genero" required>
                                <option disabled selected>Selecione o gênero</option>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                                <option value="O">Outros</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <label for="ddd">DDD do Celular</label>
                            <input type="text" id="ddd" name="ddd" placeholder="Insira o DDD" required>
                        </div>
                        <div class="input-field">
                            <label for="telefone">Número de Celular</label>
                            <input type="text" id="telefone" name="telefone" placeholder="Insira o número de celular" required>
                        </div>

                        <div class="input-field">
                            <label>E-mail</label>
                            <input type="text" name="email" placeholder="Insira o seu e-mail" required>
                        </div>

                        <div class="input-field">
                            <label>Confirme seu e-mail</label>
                            <input type="text" name="check-email" placeholder="Confirme o seu e-mail" required>
                        </div>

                        <div class="input-field">
                            <label>Senha</label>
                            <input type="password" name="password" placeholder="Insira a sua senha" required>
                        </div>

                        <div class="input-field">
                            <label>Confirmar senha</label>
                            <input type="password" name="check-password" placeholder="Confirme a sua senha" required>
                        </div>
                    </div>

                    <div class="details ID">
                        <span class="title">Informações do Endereço</span>
                        <div class="fields">
                            <div class="input-field">
                                <label>CEP</label>
                                <input name="cep" type="text" id="cep" value="" size="10" maxlength="9" onblur="pesquisacep(this.value);" required />
                            </div>
                            <div class="input-field">
                                <label>Logradouro</label>
                                <input type="text" name="rua" id="rua" placeholder="Insira a sua rua" required>
                            </div>
                            <div class="input-field">
                                <label>Número</label>
                                <input type="number" name="numero" id="numero" placeholder="Insira o número da sua residência" required>
                            </div>
                            <div class="input-field">
                                <label>Complemento</label>
                                <input type="text" name="complemento" placeholder="Insira o complemento" required>
                            </div>
                            <div class="input-field">
                                <label>Bairro</label>
                                <input type="text" name="bairro" id="bairro" placeholder="Insira o nome do bairro" required>
                            </div>
                            <div class="input-field">
                                <label>Cidade</label>
                                <input type="text" name="cidade" id="cidade" placeholder="Insira a cidade" required>
                            </div>
                            <div class="input-field">
                                <label>Estado</label>
                                <input type="text" name="estado" id="uf" placeholder="Insira o estado" required>
                            </div>
                            <div class="input-field">
                                <label>Selecionar foto de perfil</label>
                                <input type="file" name="foto_perfil" accept="image/*"> <!-- Campo de envio de imagem -->
                            </div>

                            <br>
                            <br>
                            <div class="radio-users">

                                <!-- Checkbox para selecionar se é consumidor ou prestador de serviço -->
                                <label for="tipo_usuario">Selecione o tipo de usuário:</label><br>
                                <input type="radio" id="consumidor" name="tipo_usuario[]" value="consumidor">
                                <label for="consumidor">Consumidor</label><br>
                                <input type="radio" id="desenvolvedor" name="tipo_usuario[]" value="desenvolvedor">
                                <label for="desenvolvedor">Desenvolvedor</label><br><br>
                            </div>
                        </div>
                    </div>
                    <div class="button-container">
                        <a href="login.php" class="backBtn">
                            <i class="uil uil-navigator"></i>
                            <span class="btnText">Voltar</span>
                        </a>

                        <button type="submit" name="submit" class="submit">
                            <span class="btnText">Enviar</span>
                            <i class="uil uil-navigator"></i>
                        </button>
        </form>




        <!-- ===Script=== -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@gustavoquinalha/buttons-css@1.0.2/assets/js/buttons.min.js"></script>
        <script src="assets/js/script_form.js"></script>
        <script src="assets/js/viacep_script.js"></script>


</body>

</html>