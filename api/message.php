
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Description">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">

    <title>OnDev Contato</title>
</head>

<body>
                        
                            <form action="assets/php/enviar_contato.php" id="contactForm" class="form-submission contact-form contact-form-padding" novalidate>
                                <input type="hidden" name="Subject" value="Formulário de Contato">
                                <div class="row gutters-default">
                                    <div class="col-12">
                                        <h3>Formulário de Contato</h3>
                                    </div>
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="form-field">
                                            <label for="contact-name" class="form-field-label">Nome Completo</label>
                                            <input type="text" class="form-field-input" name="nome" autocomplete="off" id="contact-name" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="form-field">
                                            <label for="contact-phone" class="form-field-label">Número de Telefone</label>
                                            <input type="tel" class="form-field-input" name="telefone" autocomplete="off" id="contact-phone" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-12">
                                        <div class="form-field">
                                            <label for="contact-email" class="form-field-label">Endereço de Email</label>
                                            <input type="email" class="form-field-input" name="email" autocomplete="off" id="contact-email" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-field">
                                            <label for="contact-message" class="form-field-label">Sua Mensagem</label>
                                            <textarea name="mensagem" class="form-field-input" id="contact-message" cols="30" rows="6"></textarea>
                                        </div>
                                        <div class="form-btn">
                                            <button type="submit" class="btn btn-w240 ripple">Enviar Mensagem</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                      


</body>

</html>