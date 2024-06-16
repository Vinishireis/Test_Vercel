<?php
if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['id']) && basename($_SERVER['PHP_SELF']) != "site.php") {
    // Redireciona para a página de login se não estiver autenticado
    header("Location: login.php");
    exit();
}
?>
